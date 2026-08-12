<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->isPasswordExpired(30)) {
                $routeName = $request->route()?->getName();

                // Allow access to password reset/change routes and auth routes to avoid redirect loop
                $allowedRouteNames = [
                    'password.edit',
                    'password.update',
                    'qal.password.edit',
                    'qal.password.update',
                    'logout',
                    'login',
                ];

                if (!in_array($routeName, $allowedRouteNames)) {
                    $redirectRoute = match ((string) $user->role_id) {
                        '2' => 'qal.password.edit',
                        default => 'password.edit',
                    };

                    return redirect()->route($redirectRoute)
                        ->with('warning', 'Password Anda telah kadaluarsa (lebih dari 30 hari). Silakan ubah password Anda demi keamanan.');
                }
            }
        }

        return $next($request);
    }
}
