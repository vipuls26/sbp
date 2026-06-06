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
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'razorpay_payment_id.required' => 'Payment id is required',
            'razorpay_order_id.required' => 'Order id is required',
            'razorpay_signature.required' => 'Signature is required',
        ];
    }
}
