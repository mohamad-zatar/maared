<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_reference_id' => $this->customer_reference_id,
            'name' => $this->name,
            'balance' => $this->balance,
            'available_balance' => $this->available_balance,
            'reserved_balance' => $this->reserved_balance,
            // Add other fields you want to include
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
