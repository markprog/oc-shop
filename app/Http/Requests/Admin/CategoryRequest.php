<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id'           => ['nullable', 'exists:categories,category_id'],
            'image'               => ['nullable', 'string'],
            'top'                 => ['boolean'],
            'column'              => ['required', 'integer', 'min:1'],
            'sort_order'          => ['required', 'integer'],
            'status'              => ['boolean'],
            'descriptions'        => ['required', 'array'],
            'descriptions.*.name' => ['required', 'string', 'max:255'],
            'filter_ids'          => ['nullable', 'array'],
            'filter_ids.*'        => ['exists:filters,filter_id'],
        ];
    }

    public function categoryData(): array
    {
        return $this->only(['parent_id', 'image', 'top', 'column', 'sort_order', 'status']);
    }
}
