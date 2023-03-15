<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        switch ($guard) {
            case 'customer':
              if (Auth::guard($guard)->check()) {
                  return Redirect::intended('defaultpageafterlogin');
              }

              // no break
            default:
              if (Auth::guard($guard)->check()) {
                  return Redirect::intended('defaultpageafterlogin');
              }
              break;
          }
        return $next($request);
    }
}
