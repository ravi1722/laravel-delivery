<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRestaurantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->isAdmin() || Auth::user()->isRestaurantOwner();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                  => 'required|string|min:3|max:255',
            'description'           => 'nullable|string|max:1000',
            'cuisine_type'          => 'required|string|max:100',
            'phone'                 => 'required|string|digits:10|regex:/^[6-9]\d{9}$/|unique:restaurants,phone',
            'email'                 => 'nullable|email|unique:restaurants,email',
            'address'               => 'required|string|max:500',
            'city'                  => 'required|string|max:100',
            'state'                 => 'required|string|max:100',
            'pincode'               => 'required|digits:6',
            'minimum_order'         => 'required|numeric|min:0',
            'delivery_time'         => 'required|integer|min:5|max:120',
            'delivery_fee'          => 'required|numeric|min:0',
            'logo'                  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex'           => 'Please enter a valid Indian mobile number.',
            'pincode.digits'        => 'Pincode must be exactly 6 digits.',
        ];
    }
}
