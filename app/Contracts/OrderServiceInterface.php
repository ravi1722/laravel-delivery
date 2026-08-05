<?php

namespace App\Contracts;

interface OrderServiceInterface
{
    public function placeOrder(array $data): mixed;
    public function cancelOrder(int $orderId, string $reason): mixed;
    // public function updateOrderStatus(int $orderId, string $status): mixed;
    public function getOrdersByUser(int $userId): mixed;
    // public function getOrdersByRestaurant(int $restaurantId, array $filters): mixed;
    public function getCoupon(string $code) : mixed;
}
