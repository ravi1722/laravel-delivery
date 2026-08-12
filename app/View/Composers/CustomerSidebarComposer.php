<?php

namespace App\View\Composers;

use App\Contracts\CartServiceInterface;
use Illuminate\View\View;

class CustomerSidebarComposer
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected CartServiceInterface $cartService)
    {
        //
    }

    public function compose(View $view)
    {
        $view->with('cart_count', $this->cartService->getCartCount());  //declaring service value for view file
    }
}
