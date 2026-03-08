<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\AttributeGroup;
use App\Models\Category;
use App\Models\Download;
use App\Models\Filter;
use App\Models\Language;
use App\Models\Manufacturer;
use App\Models\Option;
use App\Models\Product;
use App\Models\StockStatus;
use App\Models\SubscriptionPlan;
use App\Models\TaxClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('descriptions');

        if ($request->filled('name')) {
            $query->whereHas('descriptions', fn($q) => $q->where('name', 'like', '%' . $request->name . '%'));
        }
        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->orderByDesc('product_id')->paginate(20)->withQueryString();

        return view('admin.catalog.product.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.catalog.product.form', $this->formData());
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = Product::create($request->productData());

        $this->syncRelations($product, $request);

        return redirect()->route('admin.catalog.product.index')
            ->with('success', 'Product added.');
    }

    public function edit(Product $product): View
    {
        $product->load('descriptions', 'images', 'categories', 'options.values', 'attributes', 'discounts', 'specials', 'downloads', 'filters', 'related', 'subscriptionPlans');
        return view('admin.catalog.product.form', array_merge(['product' => $product], $this->formData()));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->productData());
        $this->syncRelations($product, $request);

        return redirect()->route('admin.catalog.product.index')
            ->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('admin.catalog.product.index')
            ->with('success', 'Product deleted.');
    }

    private function formData(): array
    {
        return [
            'languages'         => Language::where('status', true)->get(),
            'categories'        => Category::with('description')->get(),
            'manufacturers'     => Manufacturer::orderBy('name')->get(),
            'taxClasses'        => TaxClass::all(),
            'stockStatuses'     => StockStatus::all(),
            'options'           => Option::with('description', 'values.description')->get(),
            'attributeGroups'   => AttributeGroup::with('description', 'attributes.description')->get(),
            'downloads'         => Download::with('description')->get(),
            'filters'           => Filter::with('description')->get(),
            'subscriptionPlans' => SubscriptionPlan::with('description')->get(),
        ];
    }

    private function syncRelations(Product $product, ProductRequest $request): void
    {
        // Descriptions
        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $product->descriptions()->updateOrCreate(
                ['language_id' => $langId],
                $desc
            );
        }

        // Categories
        $product->categories()->sync($request->input('category_ids', []));

        // Images
        if ($request->has('images')) {
            $product->images()->delete();
            foreach ($request->input('images', []) as $i => $img) {
                $product->images()->create(['image' => $img['image'], 'sort_order' => $i]);
            }
        }

        // Options
        $product->options()->delete();
        foreach ($request->input('options', []) as $opt) {
            $productOption = $product->options()->create([
                'option_id'    => $opt['option_id'],
                'value'        => $opt['value'] ?? '',
                'required'     => (bool) ($opt['required'] ?? false),
                'sort_order'   => $opt['sort_order'] ?? 0,
            ]);
            foreach ($opt['values'] ?? [] as $val) {
                $productOption->values()->create($val);
            }
        }

        // Attributes
        $product->attributes()->delete();
        foreach ($request->input('attributes', []) as $attr) {
            $product->attributes()->create($attr);
        }

        // Discounts
        $product->discounts()->delete();
        foreach ($request->input('discounts', []) as $disc) {
            $product->discounts()->create($disc);
        }

        // Specials
        $product->specials()->delete();
        foreach ($request->input('specials', []) as $spec) {
            $product->specials()->create($spec);
        }

        // Related products
        $product->related()->sync($request->input('related_ids', []));

        // Downloads
        $product->downloads()->sync($request->input('download_ids', []));

        // Filters
        $product->filters()->sync($request->input('filter_ids', []));
    }
}
