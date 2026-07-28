<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRestaurantRequest extends FormRequest
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
        $restaurant_id = Auth::user()->restaurant->id;
        return [
            'name'          => ['sometimes', 'string', 'min:3', 'max:255', 'regex:/^[A-Za-z0-9\s&\'-]+$/'],
            'description'   => 'sometimes|nullable|string|max:1000',
            'cuisine_type'  => 'sometimes|required|string|max:100',
            'phone'         => ['sometimes', 'required', 'string', 'regex:/^[6-9]\d{9}$/', Rule::unique('restaurants', 'phone')->ignore($restaurant_id)],
            'email'         => ['sometimes', 'nullable', 'email', Rule::unique('restaurants', 'email')->ignore($restaurant_id)],
            'address'       => 'sometimes|required|string|max:500',
            'city'          => 'sometimes|required|string|max:100',
            'state'         => 'sometimes|required|string|max:100',
            'pincode'       => 'sometimes|required|digits:6',
            'minimum_order' => 'sometimes|required|numeric|min:0',
            'delivery_time' => 'sometimes|required|integer|min:5|max:120',
            'delivery_fee'  => 'sometimes|required|numeric|min:0',
            'logo'          => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image'   => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
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
