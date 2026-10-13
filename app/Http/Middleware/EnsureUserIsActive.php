<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ($user->is_locked || ($request->session()->has('auth_session_version') && (int) $request->session()->get('auth_session_version') !== $user->session_version))) {
            $isAdmin = $user->is_admin;
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'code' => 'account_unavailable',
                    'message' => __('This account is locked or its session has expired.'),
                ], 401);
            }

            return redirect()->route($isAdmin ? 'admin.login' : 'login')
                ->withErrors(['email' => __('This account is locked or its session has expired.')]);
        }

        return $next($request);
    }
}
