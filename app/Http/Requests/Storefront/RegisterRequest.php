<?php

namespace App\Http\Requests\Storefront;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:32'],
            'lastname'  => ['required', 'string', 'max:32'],
            'email'     => ['required', 'email', 'max:96', 'unique:customers,email'],
            'telephone' => ['required', 'string', 'max:32'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'newsletter'=> ['boolean'],
            'agree'     => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'agree.accepted' => 'You must agree to the privacy policy.',
        ];
    }
}
