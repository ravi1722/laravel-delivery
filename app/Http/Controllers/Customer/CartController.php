<?php

namespace App\Http\Controllers\Customer;

use App\Contracts\CartServiceInterface;
use App\Contracts\RestaurantServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private CartServiceInterface $cartService,
        private RestaurantServiceInterface $restaurantService
    ) {}
    public function index()
    {
        $cart  = $this->cartService->getCart();
        $total = $this->cartService->getCartTotal();

        $restaurant = null;
        if ($cart['restaurant_id']) {
            $restaurant = $this->restaurantService->getRestaurantById($cart['restaurant_id']);
        }
        return view('customer.cart', compact('cart', 'total', 'restaurant'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity'     => 'required|integer|min:1|max:10',
            // 'variant_id'   => 'nullable|exists:item_variants,id',
            // 'addons'       => 'nullable|array',
            // 'addons.*'     => 'exists:item_addons,id',
        ]);

        try {
            $this->cartService->addItem(
                $request->menu_item_id,
                $request->quantity,
                $request->variant_id,
                $request->addons ?? []
            );

            return response()->json([
                'success'    => true,
                'message'    => 'Item added to cart!',
                'cart_count' => $this->cartService->getCartCount(),
                'cart_total' => $this->cartService->getCartTotal(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, string $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        $this->cartService->updateItem($cartItemId, $request->quantity);

        return response()->json([
            'success'    => true,
            'cart_count' => $this->cartService->getCartCount(),
            'cart_total' => $this->cartService->getCartTotal(),
        ]);
    }

    public function remove(string $cartItemId)
    {
        $this->cartService->removeItem($cartItemId);

        return response()->json([
            'success'    => true,
            'cart_count' => $this->cartService->getCartCount(),
            'cart_total' => $this->cartService->getCartTotal(),
        ]);
    }

    public function clear()
    {
        $this->cartService->clearCart();
        // return redirect()->route('cart.index');
    }
}
