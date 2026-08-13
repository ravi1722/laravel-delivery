<div class="nav-section-title">Main</div>
<a href="{{ route('restaurant.dashboard') }}" class="nav-link">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<div class="nav-section-title">Orders</div>
<a href="{{ route('restaurant.orders.index') }}" class="nav-link">
    <i class="bi bi-bag"></i> Manage Orders
    @if ($orderPendingCount > 0)
        <span class="badge ms-auto" style="background:#FF6B35">{{ $orderPendingCount }}</span>
    @endif
</a>
<div class="nav-section-title">Menu</div>
<a href="{{ route('restaurant.menu-categories.index') }}" class="nav-link">
    <i class="bi bi-list-ul"></i> Categories
</a>
<a href="{{ route('restaurant.menu-items.index') }}" class="nav-link">
    <i class="bi bi-egg-fried"></i> Menu Items
</a>

<div class="nav-section-title">Restaurant</div>
<a href="{{ route('restaurant.profile.edit') }}" class="nav-link">
    <i class="bi bi-shop"></i> My Profile
</a>

<div class="nav-section-title">Account</div>
<a href="{{ route('notifications.index') }}" class="nav-link">
    <i class="bi bi-bell"></i> Notifications
</a>
