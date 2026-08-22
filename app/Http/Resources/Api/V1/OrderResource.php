<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'order_number' => $this->order_number,
            'status'       => $this->status,
            'status_label' => ucfirst(str_replace('_', ' ', $this->status)),

            'restaurant' => $this->whenLoaded('restaurant', fn() => [
                'id'       => $this->restaurant->id,
                'name'     => $this->restaurant->name,
                'logo_url' => $this->restaurant->logo_url,
                'phone'    => $this->restaurant->phone,
            ]),

            'items' => OrderItemResource::collection(
                $this->whenLoaded('items')
            ),

            'address' => $this->whenLoaded('address', fn() => [
                'label'         => $this->address->label,
                'address_line1' => $this->address->address_line1,
                'address_line2' => $this->address->address_line2,
                'city'          => $this->address->city,
                'state'         => $this->address->state,
                'pincode'       => $this->address->pincode,
            ]),

            'payment' => [
                'method'  => $this->payment_method,
                'status'  => $this->payment_status,
            ],

            'pricing' => [
                'subtotal'        => (float) $this->subtotal,
                'delivery_fee'    => (float) $this->delivery_fee,
                'discount_amount' => (float) $this->discount_amount,
                'tax_amount'      => (float) $this->tax_amount,
                'total_amount'    => (float) $this->total_amount,
            ],

            'special_instructions'  => $this->special_instructions,
            'estimated_delivery_at' => $this->estimated_delivery_at?->toIso8601String(),
            'delivered_at'          => $this->delivered_at?->toIso8601String(),
            'can_cancel'            => $this->canBeCancelled(),
            'placed_at'             => $this->created_at->toIso8601String(),
        ];
    }
}
