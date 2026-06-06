<?php

namespace App\Http\Requests\Payment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Allow the current user to submit this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for the payment form.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'card_holder' => ['required', 'string', 'max:100'],
            'card_number' => ['required', 'string', 'min:13', 'max:19', 'regex:/^[0-9\s-]+$/'],
            'expiry' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv' => ['required', 'digits_between:3,4'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'card_holder.required' => 'Card holder name is required',
            'card_holder.string' => 'Card holder name must be a string',
            'card_holder.max' => 'Card holder name may not be greater than 100 characters',

            'card_number.required' => 'Card number is required',
            'card_number.string' => 'Card number must be a string',
            'card_number.min' => 'Card number must be at least 13 digits',
            'card_number.max' => 'Card number may not be greater than 19 digits',
            'card_number.regex' => 'Card number may only contain numbers, spaces, and hyphens',

            'expiry.required' => 'Expiry date is required',
            'expiry.regex' => 'Expiry date must be in MM/YY format',

            'cvv.required' => 'CVV is required',
            'cvv.digits_between' => 'CVV must be 3 or 4 digits',
        ];
    }
}
