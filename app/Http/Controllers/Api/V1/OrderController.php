<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\CartServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends BaseApiController
{
    public function __construct(
        private OrderServiceInterface $orderService,
        private CartServiceInterface $cartService
    ) {}

    // GET /api/v1/orders
    public function index(Request $request)
    {
        $orders = $this->orderService->getOrdersByUser(Auth::user()->id);

        return $this->paginated(
            OrderResource::collection($orders)->resource,
            'Orders retrieved successfully.'
        );
    }

    // GET /api/v1/orders/{order}
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::user()->id) {
            return $this->error('Unauthorized.', 403);
        }

        $order->load([
            'items',
            'restaurant:id,name,logo,phone',
            'address',
            'statusHistories',
        ]);

        return $this->success(
            new OrderResource($order),
            'Order retrieved successfully.'
        );
    }

    // POST /api/v1/orders
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
            $order->load(['items', 'restaurant:id,name,logo', 'address']);

            return $this->success(
                new OrderResource($order),
                'Order placed successfully!',
                201
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    // POST /api/v1/orders/{order}/cancel
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::user()->id) {
            return $this->error('Unauthorized.', 403);
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        try {
            $this->orderService->cancelOrder($order->id, $request->reason);

            return $this->success(null, 'Order cancelled successfully.');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    // GET /api/v1/orders/{order}/track
    public function track(Order $order)
    {
        if ($order->user_id !== Auth::user()->id) {
            return $this->error('Unauthorized.', 403);
        }

        $order->load(['statusHistories' => fn($q) => $q->latest()]);

        return $this->success([
            'order_id'      => $order->id,
            'order_number'  => $order->order_number,
            'status'        => $order->status,
            'status_label'  => ucfirst(str_replace('_', ' ', $order->status)),
            'timeline'      => $order->statusHistories->map(fn($h) => [
                'status'    => $h->status,
                'note'      => $h->note,
                'timestamp' => $h->created_at->toIso8601String(),
            ]),
            'estimated_delivery_at' => $order->estimated_delivery_at?->toIso8601String(),
        ], 'Order tracking retrieved.');
    }
}
