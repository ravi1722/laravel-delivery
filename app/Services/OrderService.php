<?php

namespace App\Services;

use App\Contracts\CartServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Contracts\RestaurantServiceInterface;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;

class OrderService implements OrderServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private OrderRepository $orderRepository,
        private CartServiceInterface $cartService,
        private RestaurantServiceInterface $restaurantService
    ) {}

    public function getOrdersByUser(int $userId): mixed
    {
        return $this->orderRepository->getOrdersByUser($userId);
    }

    public function getCoupon(string $code): mixed
    {
        return $this->orderRepository->getCoupon($code);
    }

    private function applyCoupon(string $code, float $subtotal): array
    {
        $coupon = $this->orderRepository->getCoupon($code);
        if ($subtotal < $coupon->minimum_order) {
            throw new \Exception(
                "Minimum order amount of ₹{$coupon->minimum_order} required for this coupon."
            );
        }
        // Check per-user usage
        $userUsage = $coupon->usages()->where('user_id', Auth::user()->id)->count();
        if ($userUsage >= $coupon->per_user_limit) {
            throw new \Exception('You have already used this coupon.');
        }

        // Calculate discount
        $discount = match ($coupon->type) {
            'percentage'   => min(($subtotal * $coupon->value) / 100, $coupon->maximum_discount ?? PHP_INT_MAX),
            'fixed'        => $coupon->value,
            'free_delivery' => 0, // handled separately
            default        => 0,
        };
        return [
            'coupon_id' => $coupon->id,
            'discount'  => $discount,
        ];
    }

    public function placeOrder(array $data): mixed
    {
        $cart = $this->cartService->getCart();

        if (empty($cart['items'])) {
            throw new \Exception('Your cart is empty.');
        }

        $restaurant = $this->restaurantService->getRestaurantById($cart['restaurant_id']);
        // Calculate totals
        $subtotal       = $this->cartService->getCartTotal();
        $deliveryFee    = $restaurant->delivery_fee;
        $discountAmount = 0;
        $couponId       = null;
        $taxRate        = 0.05; // 5% GST
        $taxAmount      = $subtotal * $taxRate;
        $totalAmount    = $subtotal + $deliveryFee + $taxAmount;

        // Apply coupon if provided
        if (!empty($data['coupon_code'])) {
            $couponResult   = $this->applyCoupon($data['coupon_code'], $subtotal);
            $discountAmount = $couponResult['discount'];
            $couponId       = $couponResult['coupon_id'];
            $totalAmount    -= $discountAmount;
        }

        $order = $this->orderRepository->createOrder(
            $data,
            $cart,
            $restaurant,
            $subtotal,
            $deliveryFee,
            $discountAmount,
            $taxAmount,
            $totalAmount,
            $couponId
        );

        // Clear cart after successful order
        $this->cartService->clearCart();
        // Fire event — listeners handle email, notifications, restaurant alert
        // OrderPlaced::dispatch($order);

        return $order;
    }
}
