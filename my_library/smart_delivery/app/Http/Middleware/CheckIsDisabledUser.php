<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIsDisabledUser
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::user()->is_disabled) {
                return response()->json(['message' => "your account is disabled "], 401);
        }
        return $next($request);
    }
}
