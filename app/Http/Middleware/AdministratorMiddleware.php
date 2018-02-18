<?php

namespace CoreTecs\Http\Middleware;

use Closure;

class AdministratorMiddleware
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
        // Get the current route actions.
        $actions = $request->route()->getAction();

        // Check if we have any permissions to check the user has.
        if (!isset($actions['permission']))
        {
            // No permissions to check, allow access.
            return $next($request);
        } else {
            if($actions['permission'] <= \Auth::user()->permission) {
                return $next($request);
            }

            return redirect('/home');
        }

        return $next($request);
    }
}
