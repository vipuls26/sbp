<?php

namespace App\Http\Requests\Plan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PlanAdd extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', 'unique:plans,name'],
            'description' => ['nullable', 'string'],
            'pricing' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'in:monthly,annual'],
            'stripe_price_id' => ['required', 'string', 'max:255', 'unique:plans,stripe_price_id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            // plan name
            'name.required' => 'Plan name is required',
            'name.string' => 'Plan name must be a string',
            'name.max' => 'Plan name may not be greater than 50 characters',
            'name.unique' => 'Plan name already exists',

            // plan description
            'description.string' => 'Plan description must be a string',

            // plan pricing
            'pricing.required' => 'Plan pricing is required',
            'pricing.numeric' => 'Plan pricing must be a number',
            'pricing.min' => 'Plan pricing must be a positive value',

            // plan duration
            'duration.required' => 'Plan duration is required',
            'duration.in' => 'Plan duration must be either "monthly" or "annual"',

            // stripe price
            'stripe_price_id.required' => 'Stripe price id is required',
            'stripe_price_id.string' => 'Stripe price id must be a string',
            'stripe_price_id.max' => 'Stripe price id may not be greater than 255 characters',
            'stripe_price_id.unique' => 'Stripe price id already exists',
        ];
    }
}
