<?php

namespace App\Http\Requests\Car;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'start_price' => 'required|numeric|gt:0',
            'sale_price' => 'required|numeric|gt:0',
            'auction_day' => 'required|date'
//            'start_time' => 'required|date|date_format:Y-m-d H:i:s',
//            'end_time' => 'required|date|after:start_time|date_format:Y-m-d H:i:s',
        ];
    }
}
