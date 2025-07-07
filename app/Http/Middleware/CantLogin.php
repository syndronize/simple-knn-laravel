<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CantLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('email')) {
            return $next($request);
        } else {
            if (session()->get('role') == 'customers') {
                return redirect("/customers")->with("message", "Welcome Back !");
            } else {
                return redirect("/dashboard")->with("message", "Welcome Back !");
            }
        }
    }
}
