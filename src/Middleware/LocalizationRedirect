<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Localization;

class LocalizationRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Localization::hideDefaultLocaleInURL() && Localization::localeFromUrl() === Localization::defaultLocale()) {
            $route = request()->decodedPath();
            $route = str_replace(Localization::localeFromUrl(), '', $route);
            session()->reflash();

            return redirect(app('url')->to($route), Localization::redirectCode());
        } elseif (!Localization::hideDefaultLocaleInURL() && Localization::localeFromUrl() === null) {
            $route = request()->decodedPath();
            $route = Localization::defaultLocale().'/'.$route;
            session()->reflash();

            return redirect(app('url')->to($route), Localization::redirectCode());
        }

        return $next($request);
    }
}
