<?php

namespace App\Http\Middleware;

use Closure;

class SetLocaleApi
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
        \App::setLocale($request->header('accept-language') ?? 'en');
        return $next($request);
    }
}
