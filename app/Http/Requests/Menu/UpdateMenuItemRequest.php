<?php

namespace App\Http\Requests\Menu;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMenuItemRequest extends FormRequest
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
            'name'             => 'sometimes|required|string|max:255',
            'category_id'      => 'sometimes|required|exists:menu_categories,id',
            'description'      => 'sometimes|nullable|string|max:500',
            'price'            => 'sometimes|required|numeric|min:0',
            'discount_price'   => 'sometimes|nullable|numeric|lt:price',
            'food_type'        => 'sometimes|required|in:veg,non_veg,egg',
            'image'            => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'preparation_time' => 'sometimes|nullable|integer|min:1|max:120',
            'calories'         => 'sometimes|nullable|integer|min:0',
            'is_available'     => 'sometimes|boolean',
            'is_featured'      => 'sometimes|boolean',
            'sort_order'       => 'sometimes|nullable|integer|min:0'
        ];
    }
}
