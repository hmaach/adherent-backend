<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PostAgent
{
    /**
     * Handle an incoming request.
     * Allows both admins and post_agents through.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'post_agent' || $role === 'admin') {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
