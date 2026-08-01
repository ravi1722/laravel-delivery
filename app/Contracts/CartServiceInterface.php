<?php

namespace App\Contracts;

interface CartServiceInterface
{
    public function getCart(): array;
    // public function addItem(int $menuItemId, int $quantity, ?int $variantId, array $addons): array;
    // public function updateItem(string $cartItemId, int $quantity): array;
    // public function removeItem(string $cartItemId): array;
    // public function clearCart(): void;
    public function getCartCount(): int;
    // public function getCartTotal(): float;
    // public function getRestaurantId(): ?int;
}
