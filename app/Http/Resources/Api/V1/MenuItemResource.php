<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'slug'             => $this->slug,
            'description'      => $this->description,
            'image_url'        => $this->image
                ? asset('storage/' . $this->image)
                : null,
            'pricing' => [
                'price'          => (float) $this->price,
                'discount_price' => $this->discount_price
                    ? (float) $this->discount_price
                    : null,
                'final_price'    => (float) $this->final_price,
                'has_discount'   => !is_null($this->discount_price),
            ],
            'food_type'        => $this->food_type,
            'is_available'     => $this->is_available,
            'is_featured'      => $this->is_featured,
            'preparation_time' => $this->preparation_time,
            'calories'         => $this->calories,
            'variants'         => ItemVariantResource::collection(
                $this->whenLoaded('variants')
            ),
            'addons'           => ItemAddonResource::collection(
                $this->whenLoaded('addons')
            ),
        ];
    }
}
