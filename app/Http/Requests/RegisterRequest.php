<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|regex:/^[6-9]\d{9}$/',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:customer,restaurant_owner,delivery_agent'
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex'    => 'Please enter a valid Indian mobile number.',
            'email.unique'   => 'This email is already registered.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}
