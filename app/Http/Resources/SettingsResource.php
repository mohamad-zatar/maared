<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'insurance_rate' => $this->insurance_rate,
            'time_zone' => $this->time_zone,
            'currency' => $this->currency,
            'max_bid_amount' => $this->max_bid_amount,
            'default_language' => $this->default_language,
            'minimum_bid_amount' => $this->minimum_bid_amount,
            'refund_policy' => $this->refund_policy,
            'maintenance_mode' => $this->maintenance_mode,
            'service_fee_percentage' => $this->service_fee_percentage,
            'tax_rate' => $this->tax_rate,
            'website_title' => $this->website_title,
        ];
    }
}
