<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shop_category_id' => 'required',
            'product_name' => 'required',
            'product_link' => 'required|url',
            'product_details' => 'required',
            'product_image' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];
    }
}
