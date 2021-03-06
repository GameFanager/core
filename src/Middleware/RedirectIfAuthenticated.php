<?php

namespace LiveCMS\Middleware;

use Closure;
use LiveCMS\Models\GenericSetting as Setting;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();
            $site = $user->site ?: site();
            $root = $site->getRootUrl();
            $userSlug = getSlug('userhome');

            $url = $root.'/'.$userSlug;
            return redirect()->away($url);
        }

        return $next($request);
    }
}
