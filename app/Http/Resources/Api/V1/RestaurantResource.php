<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'slug'          => $this->slug,
            'description'   => $this->description,
            'cuisine_type'  => $this->cuisine_type,
            'logo_url'      => $this->logo_url,
            'cover_url'     => $this->cover_image  ? asset('storage/' . $this->cover_image) : null,
            'address'       => [
                'full'    => $this->address,
                'city'    => $this->city,
                'state'   => $this->state,
                'pincode' => $this->pincode,
            ],
            'contact' => [
                'phone' => $this->phone,
                'email' => $this->email,
            ],
            'settings' => [
                'minimum_order' => (float) $this->minimum_order,
                'delivery_fee'  => (float) $this->delivery_fee,
                'delivery_time' => $this->delivery_time,
            ],
            'stats' => [
                'rating'        => (float) $this->rating,
                'total_reviews' => $this->total_reviews,
                'orders_count'  => $this->whenCounted('orders'),
            ],
            'status' => [
                'is_open'     => $this->is_open,
                'is_featured' => $this->is_featured,
                'status'      => $this->status,
            ],
            'menu_categories' => MenuCategoryResource::collection(
                $this->whenLoaded('menuCategories')
            ),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
