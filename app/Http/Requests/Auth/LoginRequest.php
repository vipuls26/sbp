<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:12',
        ];
    }

    public function messages()
    {
        return [
            // email
            'email.required' => 'Email is required',
            'email.string' => 'Email must be in string',
            'email.email' => 'Email must be a valid email address',
            'email.max' => 'Email must be at most 255 characters',

            // password
            'password.required' => 'Password is required',
            'password.string' => 'Password must be in string',
            'password.min' => 'Password must be at least 6 characters',
            'password.max' => 'Password must be at most 12 characters',
        ];
    }
}
