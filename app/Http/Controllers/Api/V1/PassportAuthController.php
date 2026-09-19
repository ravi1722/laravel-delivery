<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PassportAuthController extends BaseApiController
{
    public function register(Request $request)
    {
        dd($request->all());
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|regex:/^[6-9]\d{9}$/',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:customer,restaurant_owner,delivery_agent',
        ]);
        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'phone'    => $validated['phone'],
                'password' => $validated['password'],
                'role'     => $validated['role'],
            ]);

            Wallet::create(['user_id' => $user->id]);
            $user->assignRole($validated['role']);

            return $user;
        });

        // Create personal access token with scopes based on role
        $scopes = $this->getScopesForRole($user->role);
        $token  = $user->createToken('QuickBite-' . $user->role, $scopes);

        return $this->success([
            'user'          => new UserResource($user),
            'token_type'    => 'Bearer',
            'access_token'  => $token->accessToken,
            'expires_at'    => $token->token->expires_at,
        ], 'Registration successful!', 201);
    }

    private function getScopesForRole(string $role): array
    {
        return match ($role) {
            'admin'            => ['admin:all'],
            'restaurant_owner' => ['manage:restaurant', 'view:orders', 'read:restaurants'],
            'delivery_agent'   => ['view:orders', 'read:restaurants'],
            default            => ['read:restaurants', 'place:orders', 'view:orders', 'manage:profile']
        };
    }
}
