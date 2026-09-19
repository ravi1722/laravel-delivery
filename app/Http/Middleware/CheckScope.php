<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckScope
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$scopes): Response
    {
        dd($scopes);
        if (!$request->user() || !$request->user()->token()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        foreach ($scopes as $scope) {
            if (!$request->user()->tokenCan($scope)) {
                return response()->json([
                    'message' => "Token missing required scope: {$scope}",
                ], 403);
            }
        }
        return $next($request);
    }
}
