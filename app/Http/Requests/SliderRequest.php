<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slider_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slider_name' => 'required|string|min:2|max:100',
        ];
    }
}
