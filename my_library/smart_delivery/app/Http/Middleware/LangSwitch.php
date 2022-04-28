<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LangSwitch
{

    public function handle(Request $request, Closure $next)
    {
        if (Session::has('appLocale')) {
            App::setLocale(Session::get('appLocale'));
        } else {
            App::setLocale("en");
        }
        return $next($request);
    }
}
