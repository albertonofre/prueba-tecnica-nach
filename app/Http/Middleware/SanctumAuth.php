<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SanctumAuth
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user('sanctum');
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
            ], 401);
        }

        return $next($request);
    }
}