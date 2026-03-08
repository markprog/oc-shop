<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => ['required', 'string', 'max:64'],
            'text'   => ['required', 'string', 'min:25'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }
}
