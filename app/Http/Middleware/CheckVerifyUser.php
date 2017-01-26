<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Routing\RoutingServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing;

class CheckVerifyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $unverify_allow_access = ['unverified', 'home', 'login', 'logout'];

        // Si no está verificado y la pantalla no es la de welcome
        if (Auth::check()) {
            if (!Auth::user()->isVerify() && !in_array($request->route()->getName(), $unverify_allow_access))  {
                return redirect()->route('unverified');
            }
        }

        return $next($request);
    }
}
