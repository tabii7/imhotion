<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogoutOnError
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $status = $response->getStatusCode();

        if (in_array($status, [403, 404], true) && Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $login = str_starts_with(trim($request->path(), '/'), 'admin')
                ? '/admin/login'
                : '/login';

            return redirect($login)->with('status', 'You were logged out.');
        }

        return $response;
    }
}
