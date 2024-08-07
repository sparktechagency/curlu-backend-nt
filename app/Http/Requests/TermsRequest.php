<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TermsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255|unique:terms_conditions,title',
            'description' => 'required',
        ];
    }
}
