<?php

namespace App\View\Composers;

use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RestaurantOwnerSidebarComposer
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected OrderRepository $orderRepository)
    {
        //
    }

    public function compose(View $view)
    {
        $order = Auth::user()->restaurant ? $this->orderRepository->getOrdersByRestaurant(Auth::user()->restaurant?->id) : 0;
        $orderPendingCount = $order->whereIn('status', ['placed', 'confirmed'])->count();
        $view->with("orderPendingCount", $orderPendingCount);
    }
}
