<?php

namespace App\Http\Controllers\Storefront\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\View\View;

class ManufacturerController extends Controller
{
    public function index(): View
    {
        $manufacturers = Manufacturer::orderBy('name')->get();
        return view('storefront.catalog.manufacturer', compact('manufacturers'));
    }

    public function show(int $id): View
    {
        $manufacturer = Manufacturer::with('products.description', 'products.images')->findOrFail($id);
        return view('storefront.catalog.manufacturer_products', compact('manufacturer'));
    }
}
