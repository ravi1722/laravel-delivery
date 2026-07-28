<?php

namespace App\Http\Requests\Menu;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMenuItemRequest extends FormRequest
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
            'category_id'      => 'required|exists:menu_categories,id',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string|max:500',
            'price'            => 'required|numeric|min:0',
            'discount_price'   => 'nullable|numeric|lt:price',
            'food_type'        => 'required|in:veg,non_veg,egg',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'preparation_time' => 'nullable|integer|min:1|max:120',
            'calories'         => 'nullable|integer|min:0',
            'is_available'     => 'boolean',
            'is_featured'      => 'boolean',
            'sort_order'       => 'nullable|integer|min:0',

            // Variants
            'variants'          => 'nullable|array',
            'variants.*.name'   => 'required|string|max:50',
            'variants.*.price'  => 'required|numeric|min:0',

            // Addons
            'addons'            => 'nullable|array',
            'addons.*.name'     => 'required|string|max:100',
            'addons.*.price'    => 'required|numeric|min:0',
        ];
    }
}
