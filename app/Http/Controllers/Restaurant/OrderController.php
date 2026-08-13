<?php

namespace App\Http\Controllers\Restaurant;

use App\Contracts\OrderServiceInterface;
use App\Events\OrderPlaced;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(private OrderServiceInterface $orderService) {}

    public function index(Request $request)
    {
        $order = $this->orderService->getOrderByid(8);
        OrderPlaced::dispatch($order);
        dd(123);
        $restaurant = Auth::user()->restaurant;

        $orders = $this->orderService->getOrdersByRestaurant($restaurant->id, $request->all());
        return view('restaurant.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->ensureOwnsOrder($order);

        $order->load(['user', 'orderItems.menuItem', 'address', 'statusHistories']);

        return view('restaurant.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->ensureOwnsOrder($order);

        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,picked_up,delivered',
        ]);

        try {
            $this->orderService->updateOrderStatus($order->id, $request->status);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order status updated!',
                    'status'  => $request->status,
                ]);
            }

            return back()->with('success', 'Order status updated!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    private function ensureOwnsOrder(Order $order): void
    {
        if (Auth::user()->restaurant->id !== $order->restaurant_id) {
            abort(403);
        }
    }
}
