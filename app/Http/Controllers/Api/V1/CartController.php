<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\CartServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class CartController extends BaseApiController
{
    public function __construct(
        private CartServiceInterface $cartService
    ) {}

    // GET /api/v1/cart
    public function index()
    {
        $cart       = $this->cartService->getCart();
        $total      = $this->cartService->getCartTotal();
        $restaurant = null;

        if ($cart['restaurant_id']) {
            $restaurant = Restaurant::select('id', 'name', 'delivery_fee', 'minimum_order')
                ->find($cart['restaurant_id']);
        }

        return $this->success([
            'restaurant'  => $restaurant,
            'items'       => array_values($cart['items']),
            'summary' => [
                'items_count'  => $this->cartService->getCartCount(),
                'subtotal'     => $total,
                'delivery_fee' => $restaurant?->delivery_fee ?? 0,
                'tax'          => round($total * 0.05, 2),
                'total'        => round($total + ($restaurant?->delivery_fee ?? 0) + ($total * 0.05), 2),
            ],
        ], 'Cart retrieved successfully.');
    }

    // POST /api/v1/cart/add
    public function add(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity'     => 'required|integer|min:1|max:10',
            'variant_id'   => 'nullable|exists:item_variants,id',
            'addons'       => 'nullable|array',
            'addons.*'     => 'exists:item_addons,id',
        ]);

        try {
            $this->cartService->addItem(
                $request->menu_item_id,
                $request->quantity,
                $request->variant_id,
                $request->addons ?? []
            );

            return $this->success([
                'cart_count' => $this->cartService->getCartCount(),
                'cart_total' => $this->cartService->getCartTotal(),
            ], 'Item added to cart!');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    // PUT /api/v1/cart/{cartItemId}
    public function update(Request $request, string $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        $this->cartService->updateItem($cartItemId, $request->quantity);

        return $this->success([
            'cart_count' => $this->cartService->getCartCount(),
            'cart_total' => $this->cartService->getCartTotal(),
        ], 'Cart updated successfully.');
    }

    // DELETE /api/v1/cart/{cartItemId}
    public function remove(string $cartItemId)
    {
        $this->cartService->removeItem($cartItemId);

        return $this->success([
            'cart_count' => $this->cartService->getCartCount(),
            'cart_total' => $this->cartService->getCartTotal(),
        ], 'Item removed from cart.');
    }

    // DELETE /api/v1/cart
    public function clear()
    {
        $this->cartService->clearCart();
        return $this->success(null, 'Cart cleared.');
    }
}
