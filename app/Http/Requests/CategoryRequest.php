<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_name' => 'required|string|min:2|unique:categories',
            'category_image' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];
    }
}
