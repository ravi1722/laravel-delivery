<div class="nav-section-title">Browse</div>
<a href="{{ route('customer.home') }}" class="nav-link">
    <i class="bi bi-house"></i> Home
</a>
<div class="nav-section-title">Orders</div>
<a href="{{ route('customer.orders.index') }}" class="nav-link">
    <i class="bi bi-bag"></i> My Orders
</a>

<a href="{{ route('customer.cart.index') }}" class="nav-link">
    <i class="bi bi-cart"></i> My Cart
    @if ($cart_count > 0)
        <span class="badge ms-auto" style="background:#FF6B35">{{ $cart_count }}</span>
    @endif
</a>
<div class="nav-section-title">Account</div>
<a href="{{ route('customer.addresses.index') }}" class="nav-link">
    <i class="bi bi-geo-alt"></i> My Addresses
</a>
<a href="#" class="nav-link">
    <i class="bi bi-wallet2"></i> My Wallet
</a>
<a href="#" class="nav-link">
    <i class="bi bi-person"></i> Profile
</a>
