<?php

namespace App\Http\Controllers\Customer;

use App\Contracts\AddressServiceInterface;
use App\Contracts\CartServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Contracts\RestaurantServiceInterface;
use App\Events\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private OrderServiceInterface $orderService,
        private CartServiceInterface $cartService,
        private AddressServiceInterface $addressService,
        private RestaurantServiceInterface $restaurantService
    ) {}
    public function index()
    {
        $get_orders = $this->orderService->getOrdersByUser(Auth::user()->id);
        $orders = $get_orders->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id'           => 'required|exists:addresses,id',
            'payment_method'       => 'required|in:cod,online,wallet',
            'special_instructions' => 'nullable|string|max:500',
            'coupon_code'          => 'nullable|string',
        ]);

        try {
            $order = $this->orderService->placeOrder($request->all());
            return redirect()->route('customer.orders.show', $order)
                ->with('success', "Order #{$order->order_number} placed successfully!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        // Ensure customer owns this order
        if ($order->user_id !== Auth::user()->id) {
            abort(403);
        }

        $order->load([
            'orderItems.menuItem',
            'restaurant:id,name,logo,phone,address',
            'address',
            'statusHistories' => fn($q) => $q->orderBy('created_at'),
        ]);
        // dd($order->toArray());

        $allStatuses = ['placed', 'confirmed', 'preparing', 'ready', 'picked_up', 'delivered'];
        $statusLabels = [
            'placed'    => ['label' => 'Order Placed', 'icon' => 'bi-bag-check'],
            'confirmed' => ['label' => 'Confirmed', 'icon' => 'bi-check-circle'],
            'preparing' => ['label' => 'Preparing', 'icon' => 'bi-fire'],
            'ready'     => ['label' => 'Ready', 'icon' => 'bi-box-seam'],
            'picked_up' => ['label' => 'Picked Up', 'icon' => 'bi-truck'],
            'delivered' => ['label' => 'Delivered', 'icon' => 'bi-house-check'],
        ];

        return view('customer.orders.show', compact('order', 'allStatuses', 'statusLabels'));
    }

    public function checkout()
    {
        $cart = $this->cartService->getCart();

        if (empty($cart['items'])) {
            return redirect()->route('customer.home');
        }

        $addresses = $this->addressService->getUserAddresses(Auth::user()->id);
        $restaurant = $this->restaurantService->getRestaurantById($cart['restaurant_id']);
        $subtotal = $this->cartService->getCartTotal();
        $taxAmount  = $subtotal * 0.05;
        $total      = $subtotal + $restaurant->delivery_fee + $taxAmount;
        return view('customer.checkout', compact('cart', 'addresses', 'restaurant', 'subtotal', 'taxAmount', 'total'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        try {
            $coupon = $this->orderService->getCoupon($request->coupon_code);
            $subtotal = $this->cartService->getCartTotal();

            if ($subtotal < $coupon->minimum_order) {
                throw new \Exception(
                    "Coupon not validated. Minimum order amount ₹{$coupon->minimum_order} required."
                );
            }

            $discount = match ($coupon->type) {
                'percentage' => min(($subtotal * $coupon->value / 100), $coupon->maximum_discount ?? PHP_INT_MAX), //PHP_INT_MAX means maximum or big integer value
                'fixed'        => $coupon->value,
                'free_delivery' => 0,
                default        => 0,
            };

            return response()->json([
                'success'       => true,
                'coupon_code'   => $coupon->code,
                'discount'      => $discount,
                'message'       => "Coupon applied! You save ₹{$discount}",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function cancel(Request $request, Order $order)
    {

        if ($order->user_id != Auth::user()->id) {
            abort(403);
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        try {
            $this->orderService->cancelOrder($order->id, $request->reason);

            return back()->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
