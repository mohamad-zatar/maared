<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'customer_id' => 'nullable|unique:customers',
            'name' => 'nullable|string|max:255',
            'balance_add' => 'nullable|numeric|min:0.01',
            'balance_deduct' => 'nullable|numeric|min:0.01',
        ];
    }
}
