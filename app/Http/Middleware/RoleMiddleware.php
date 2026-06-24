<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (! $request->user() || $request->user()->peran !== $role) {
            \Illuminate\Support\Facades\Log::warning('Role access denied', [
                'user_id' => $request->user()?->id,
                'user_role' => $request->user()?->peran,
                'required_role' => $role
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk mengakses fitur ini.',
            ], 403);
        }

        return $next($request);
    }
}
