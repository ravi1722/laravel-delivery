<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');;
    }

    public function register(RegisterRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->password,
                'role' => $request->role
            ]);

            $user->assignRole($request->role);
            Wallet::create(['user_id' => $user->id, 'balance' => 0]);

            Auth::login($user);
        });

        return redirect()->route('dashboard')->with('success', 'Welcome to Laravel-Delivery! Please verify your email.');
    }
}
