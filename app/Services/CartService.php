<?php

namespace App\Services;

use App\Contracts\CartServiceInterface;
use App\Contracts\MenuServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartService implements CartServiceInterface
{
    private string $cartKey;
    /**
     * Create a new class instance.
     */
    public function __construct(private MenuServiceInterface $menuService)
    {
        $this->cartKey = (Auth::check()) ?  "cart_" . Auth::user()->id : 'cart_guest_' . session()->getId();
    }

    public function getCart(): array
    {
        return session($this->cartKey, [
            'restaurant_id' => null,
            'items'         => [],
        ]);
    }

    public function getCartCount(): int
    {
        return 1;
    }

    public function getCartTotal(): float
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart['items'], 'total_price'));
    }

    public function addItem(int $menuItemId, int $quantity, ?int $variantId, array $addons): array
    {
        $menuItemById = $this->menuService->getMenuItemById($menuItemId);
        $menuItem = $menuItemById->load(['variants', 'addons']);
        $cart = $this->getCart();

        // if ($cart['restaurant_id'] && $cart['restaurant_id'] !== $menuItem->restaurant_id) {
        //     throw new \Exception(
        //         'Your cart contains items from another restaurant. Please clear your cart first.'
        //     );
        // }
        $cart['restaurant_id'] = $menuItem->restaurant_id;

        // Calculate price
        $price = $menuItem->price;
        if ($variantId) {
            $variant = $this->menuService->getItemVariantById($variantId);
            $price   = $variant->price;
        }

        // Add addon prices
        $addonTotal = 0;
        $addonDetails = [];
        if (!empty($addons)) {
            $addonModels = $menuItem->addons()->whereIn('id', $addons)->get();
            foreach ($addonModels as $addon) {
                $addonTotal += $addon->price;
                $addonDetails[] = [
                    'id'    => $addon->id,
                    'name'  => $addon->name,
                    'price' => $addon->price,
                ];
            }
        }
        $unitPrice = $price + $addonTotal;

        // Check if item already exists in cart (same item + variant + addons)
        $existingKey = null;
        foreach ($cart['items'] as $key => $item) {
            if (
                $item['menu_item_id'] === $menuItemId &&
                $item['variant_id'] === $variantId &&
                $item['addons'] === $addonDetails
            ) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey !== null) {
            $cart['items'][$existingKey]['quantity'] += $quantity;
            $cart['items'][$existingKey]['total_price'] = $cart['items'][$existingKey]['quantity'] * $unitPrice;
        } else {
            $cartItemId = (string) Str::uuid();
            $cart['items'][$cartItemId] = [
                'cart_item_id' => $cartItemId,
                'menu_item_id' => $menuItemId,
                'name'         => $menuItem->name,
                'image'        => $menuItem->image,
                'variant_id'   => $variantId,
                'variant_name' => $variantId ? $variant->name ?? null : null,
                'unit_price'   => $unitPrice,
                'quantity'     => $quantity,
                'total_price'  => $unitPrice * $quantity,
                'addons'       => $addonDetails,
                'food_type'    => $menuItem->food_type,
            ];
        }
        session([$this->cartKey => $cart]);
        return $cart;
    }

    public function updateItem(string $cartItemId, int $quantity): array
    {
        $cart = $this->getCart();

        if (isset($cart['items'][$cartItemId])) {
            if ($quantity <= 0) {
                $cart = $this->removeItem($cartItemId);
            } else {
                $cart['items'][$cartItemId]['quantity'] = $quantity;
                $cart['items'][$cartItemId]['total_price'] = $cart['items'][$cartItemId]['unit_price'] * $quantity;
            }

            session([$this->cartKey => $cart]);
        }
        return $cart;
    }

    public function removeItem(string $cartItemId): array
    {
        $cart = $this->getCart();

        unset($cart['items'][$cartItemId]);
        if (empty($cart['items'])) {
            $cart['restaurant_id'] = null;
        }

        session([$this->cartKey => $cart]);
        return $cart;
    }

    public function clearCart(): void
    {
        session([$this->cartKey => [
            'restaurant_id' => null,
            'items'         => [],
        ]]);
    }
}
