<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopCategoryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_name' => 'required|string|min:2|unique:shop_categories',
        ];
    }
}
