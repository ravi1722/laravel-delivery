<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getOrdersByUser(int $userId): mixed
    {
        return Order::with(['restaurant:id,name,logo', 'orderItems'])->forUser($userId)
            ->latest();
    }

    public function getOrdersByRestaurant(int $restaurantId) : mixed {
        return Order::forRestaurant($restaurantId);
    }

    public function getOrderById(int $orderId)
    {
        return Order::findOrFail($orderId);
    }

    public function getCoupon(string $code)
    {
        return Coupon::active()->where('code', strtoupper($code))->firstOrFail();
    }

    public function createOrder(
        array $data,
        array $cart,
        mixed $restaurant,
        float $subtotal,
        float $deliveryFee,
        float $discountAmount,
        float $taxAmount,
        float $totalAmount,
        mixed $couponId
    ) {
        // Wrap everything in transaction — all or nothing
        $order = DB::transaction(function () use (
            $data,
            $cart,
            $restaurant,
            $subtotal,
            $deliveryFee,
            $discountAmount,
            $taxAmount,
            $totalAmount,
            $couponId
        ) {
            $order = Order::create([
                'order_number'      => $this->generateOrderNumber(),
                'user_id'           => Auth::user()->id,
                'restaurant_id'     => $restaurant->id,
                'address_id'        => $data['address_id'],
                'coupon_id'         => $couponId,
                'status'            => 'placed',
                'payment_method'    => $data['payment_method'],
                'payment_status'    => $data['payment_method'] === 'cod' ? 'pending' : 'pending',
                'subtotal'          => $subtotal,
                'delivery_fee'      => $deliveryFee,
                'discount_amount'   => $discountAmount,
                'tax_amount'        => $taxAmount,
                'total_amount'      => max(0, $totalAmount),
                'special_instructions' => $data['special_instructions'] ?? null,
                'estimated_delivery_at' => now()->addMinutes($restaurant->delivery_time),
            ]);

            // Create order items from cart
            foreach ($cart['items'] as $cartItem) {
                $order->orderItems()->create([
                    'menu_item_id' => $cartItem['menu_item_id'],
                    'variant_id'   => $cartItem['variant_id'],
                    'item_name'    => $cartItem['name'],
                    'variant_name' => $cartItem['variant_name'],
                    'unit_price'   => $cartItem['unit_price'],
                    'quantity'     => $cartItem['quantity'],
                    'total_price'  => $cartItem['total_price'],
                    'addons'       => !empty($cartItem['addons']) ? $cartItem['addons'] : null,
                ]);
            }

            // Initial status history
            $order->statusHistories()->create([
                'status'     => 'placed',
                'note'       => 'Order placed by customer',
                'changed_by' => Auth::user()->id,
            ]);

            // Increment coupon usage
            if ($couponId) {
                Coupon::findOrFail($couponId)?->increment('used_count');
            }

            return $order;
        });

        return $order;
    }

    private function generateOrderNumber(): string
    {
        return 'QB' . strtoupper(Str::random(8));
    }
}
