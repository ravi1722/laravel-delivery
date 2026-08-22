<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseApiController
{
    public function register(Request $request)
    {
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

        $token = $user->createToken('api-token', ['*'])->plainTextToken;
        return $this->success([
            'user'  => new UserResource($user),
            'token' => $token,
        ], 'Registration successful!', 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }
        if (!$user->is_active) {
            return $this->error('Your account has been deactivated.', 403);
        }
        // Revoke old tokens
        $user->tokens()->delete();
        $token = $user->createToken('api-token', ['*'])->plainTextToken;

        $user->update(['last_login_at' => now()]);
        return $this->success([
            'user'  => new UserResource($user),
            'token' => $token,
        ], 'Login successful!');
    }

    // POST /api/v1/auth/logout
    public function logout(Request $request)
    {
        dd('check');
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Logged out successfully.');
    }

    // GET /api/v1/auth/me
    public function me(Request $request)
    {
        return $this->success(
            new UserResource($request->user()),
            'User profile retrieved.'
        );
    }

    // PUT /api/v1/auth/profile
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|regex:/^[6-9]\d{9}$/',
        ]);
        $request->user()->update($validated);

        return $this->success(
            new UserResource($request->user()->fresh()),
            'Profile updated successfully.'
        );
    }
}
