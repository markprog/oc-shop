<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname'  => ['required', 'string', 'max:32'],
            'lastname'   => ['required', 'string', 'max:32'],
            'company'    => ['nullable', 'string', 'max:40'],
            'address_1'  => ['required', 'string', 'max:128'],
            'address_2'  => ['nullable', 'string', 'max:128'],
            'city'       => ['required', 'string', 'max:128'],
            'postcode'   => ['required', 'string', 'max:10'],
            'country_id' => ['required', 'exists:countries,country_id'],
            'zone_id'    => ['nullable', 'exists:zones,zone_id'],
        ];
    }
}
