<?php

return [
    'status_colors' => [
        'placed'    => 'primary',
        'confirmed' => 'info',
        'preparing' => 'warning',
        'ready'     => 'secondary',
        'picked_up' => 'dark',
        'delivered' => 'success',
        'cancelled' => 'danger',
    ],

    'colors' => [
        'pending' => 'warning',
        'active' => 'success',
        'inactive' => 'secondary',
        'suspended' => 'danger',
    ],

    'order_status' => ['placed', 'confirmed', 'preparing', 'ready', 'picked_up', 'delivered', 'cancelled'],

    'nextStatuses' => [
        'placed'    => ['confirmed' => 'Confirm'],
        'confirmed' => ['preparing' => 'Preparing'],
        'preparing' => ['ready' => 'Ready'],
        'ready'     => ['picked_up' => 'Picked Up'],
        'picked_up' => ['delivered' => 'Delivered'],
    ],

    'notify_icons' => [
        'confirmed' => 'bi-check-circle',
        'preparing' => 'bi-fire',
        'ready'     => 'bi-box-seam',
        'picked_up' => 'bi-truck',
        'delivered' => 'bi-house-check',
        'cancelled' => 'bi-x-circle',
    ]

];
