<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')?->customer_id;

        return [
            'customer_group_id' => ['required', 'exists:customer_groups,customer_group_id'],
            'firstname'         => ['required', 'string', 'max:32'],
            'lastname'          => ['required', 'string', 'max:32'],
            'email'             => ['required', 'email', Rule::unique('customers', 'email')->ignore($customerId, 'customer_id')],
            'telephone'         => ['required', 'string', 'max:32'],
            'password'          => [$customerId ? 'nullable' : 'required', 'string', 'min:8'],
            'newsletter'        => ['boolean'],
            'status'            => ['boolean'],
            'safe'              => ['boolean'],
        ];
    }
}
