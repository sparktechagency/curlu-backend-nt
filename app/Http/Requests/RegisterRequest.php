<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|string|email|max:60',
            'image' => 'nullable',
            'address' => 'string|min:2|max:100',
            'phone' => 'nullable|numeric|digits_between:8,12',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required|string|in:Male,Female',
            'role_type' => ['required', Rule::in(['USER', 'ADMIN', 'SUPER ADMIN', 'PROFESSIONAL'])],
        ];
    }
}
