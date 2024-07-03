<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalonServiceRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'salon_id' => 'required|exists:salons,id',
            'category_id' => 'required|exists:categories,id',
            'service_name' => 'required|string|max:255',
            'service_description' => 'nullable|string',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'service_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'service_status' => 'required|in:active,inactive',
        ];
    }
}
