<div class="nav-section-title">Main</div>
<a href="{{ route('restaurant.dashboard') }}" class="nav-link">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<div class="nav-section-title">My Restaurant</div>
<a href="#" class="nav-link">
    <i class="bi bi-shop"></i> Restaurant Profile
</a>
<a href="{{ route('restaurant.menu-categories.index') }}" class="nav-link">
    <i class="bi bi-list-ul"></i> Menu Categories
</a>
<a href="{{ route('restaurant.menu-items.index') }}" class="nav-link">
    <i class="bi bi-egg-fried"></i> Menu Items
</a>

<div class="nav-section-title">Orders</div>
<a href="#" class="nav-link">
    <i class="bi bi-bag"></i> Incoming Orders
</a>
<a href="#" class="nav-link">
    <i class="bi bi-check-circle"></i> Order History
</a>

<div class="nav-section-title">Reports</div>
<a href="#" class="nav-link">
    <i class="bi bi-bar-chart"></i> Earnings
</a>
<a href="#" class="nav-link">
    <i class="bi bi-star"></i> Reviews
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
