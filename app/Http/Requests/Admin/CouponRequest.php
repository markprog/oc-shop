<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', 'max:128'],
            'code'                 => ['required', 'string', 'max:20', 'alpha_num'],
            'type'                 => ['required', 'in:P,F'],
            'discount'             => ['required', 'numeric', 'min:0'],
            'login'                => ['boolean'],
            'shipping'             => ['boolean'],
            'total'                => ['nullable', 'numeric', 'min:0'],
            'date_start'           => ['nullable', 'date'],
            'date_end'             => ['nullable', 'date', 'after_or_equal:date_start'],
            'uses_total'           => ['nullable', 'integer', 'min:0'],
            'uses_customer'        => ['nullable', 'integer', 'min:0'],
            'status'               => ['boolean'],
            'product_ids'          => ['nullable', 'array'],
            'product_ids.*'        => ['exists:products,product_id'],
            'category_ids'         => ['nullable', 'array'],
            'category_ids.*'       => ['exists:categories,category_id'],
        ];
    }

    public function couponData(): array
    {
        return $this->only([
            'name', 'code', 'type', 'discount', 'login', 'shipping',
            'total', 'date_start', 'date_end', 'uses_total', 'uses_customer', 'status',
        ]);
    }
}
