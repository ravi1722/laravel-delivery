<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Customer order channel — user can only listen to their own orders
Broadcast::channel('orders.{userId}', function($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Restaurant channel — only the restaurant owner can listen
Broadcast::channel('restaurant.{restaurantId}', function ($user, $restaurantId) {
    return $user->restaurant?->id === (int) $restaurantId;
});

// Delivery agent channel — only that specific agent
Broadcast::channel('agent.{agentId}', function ($user, $agentId) {
    return $user->deliveryAgent?->id === (int) $agentId;
});

// Admin channel — only admins
Broadcast::channel('admin', function ($user) {
    return $user->isAdmin();
});