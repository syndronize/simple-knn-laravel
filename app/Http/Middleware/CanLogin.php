<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not logged in, redirect to login
        if (!session()->has('email')) {
            return redirect("/")->with("message", "Login First !");
        }

        // If user is logged in and tries to access login page again, redirect based on role
        if ($request->is('/')) {
            if (session('role') == 'customers') {
                return redirect("/customers")->with("message", "Welcome Back User !");
            } else {
                return redirect("/dashboard")->with("message", "Welcome Back User !");
            }
        }

        // Otherwise, continue to requested page
        return $next($request);
    }
}
