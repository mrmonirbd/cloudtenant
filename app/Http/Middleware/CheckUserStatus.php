<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user status is active
            if ($user->status !== 'active') {
                Auth::logout();
                
                // For AJAX requests
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Your account is not active. Please contact administrator.'
                    ], 403);
                }
                
                // For web requests
                return redirect()->route('login')
                    ->with('error', 'Your account is not active. Please contact administrator.');
            }
        }

        return $next($request);
    }
}