<?php

namespace App\Services;

use App\Contracts\CartServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartService implements CartServiceInterface
{
    private string $cartKey;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->cartKey = (Auth::check()) ?  "cart_". Auth::user()->id : 'cart_guest_' . session()->getId();
    }

    public function getCart(): array {
        return session($this->cartKey , []);
    }
}
