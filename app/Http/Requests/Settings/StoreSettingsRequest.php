<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingsRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'insurance_rate' => 'required|numeric',
            'time_zone' => 'required|string',
            'currency' => 'required|string',
            'max_bid_increment' => 'required|numeric',
            'auction_duration' => 'required|integer',
            'notification_email' => 'nullable|email',
            'support_phone_number' => 'nullable|string',
            'default_language' => 'required|string',
            'minimum_balance_required' => 'required|numeric',
            'refund_policy' => 'nullable|string',
            'maintenance_mode' => 'boolean',
            'service_fee_percentage' => 'required|numeric',
            'tax_rate' => 'required|numeric',
            'website_title' => 'required|string',
        ];
    }
}
