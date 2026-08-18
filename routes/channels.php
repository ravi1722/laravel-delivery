<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

// Customer order channel — user can only listen to their own orders
Broadcast::channel('orders.{userId}', function($user, $userId) {
    dd($user, $userId);
});
