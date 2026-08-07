<?php

namespace App\View\Composers;

use App\Contracts\CartServiceInterface;
use Illuminate\View\View;

class SidebarComposer
{
    public function __construct(
        protected CartServiceInterface $cartService
    ) {}

    public function compose(View $view)
    {
        $view->with('cart_count', $this->cartService->getCartCount());  //declaring service value for view file
    }
}
