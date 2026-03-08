<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'model'               => ['required', 'string', 'max:64'],
            'sku'                 => ['nullable', 'string', 'max:64'],
            'upc'                 => ['nullable', 'string', 'max:12'],
            'ean'                 => ['nullable', 'string', 'max:14'],
            'jan'                 => ['nullable', 'string', 'max:13'],
            'isbn'                => ['nullable', 'string', 'max:17'],
            'mpn'                 => ['nullable', 'string', 'max:64'],
            'location'            => ['nullable', 'string', 'max:128'],
            'price'               => ['required', 'numeric', 'min:0'],
            'tax_class_id'        => ['nullable', 'exists:tax_classes,tax_class_id'],
            'quantity'            => ['required', 'integer'],
            'minimum'             => ['required', 'integer', 'min:1'],
            'subtract'            => ['boolean'],
            'stock_status_id'     => ['required', 'exists:stock_statuses,stock_status_id'],
            'shipping'            => ['boolean'],
            'weight'              => ['nullable', 'numeric'],
            'length'              => ['nullable', 'numeric'],
            'width'               => ['nullable', 'numeric'],
            'height'              => ['nullable', 'numeric'],
            'status'              => ['boolean'],
            'manufacturer_id'     => ['nullable', 'exists:manufacturers,manufacturer_id'],
            'image'               => ['nullable', 'string'],
            'sort_order'          => ['required', 'integer'],
            'date_available'      => ['nullable', 'date'],
            'descriptions'        => ['required', 'array'],
            'descriptions.*.name' => ['required', 'string', 'max:255'],
            'category_ids'        => ['nullable', 'array'],
            'category_ids.*'      => ['exists:categories,category_id'],
        ];
    }

    /**
     * Return only product table fields.
     */
    public function productData(): array
    {
        return $this->only([
            'model', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'location',
            'price', 'tax_class_id', 'quantity', 'minimum', 'subtract',
            'stock_status_id', 'shipping', 'weight', 'length', 'width', 'height',
            'status', 'manufacturer_id', 'image', 'sort_order', 'date_available',
        ]);
    }
}
