<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckChargeTries
{
    public function handle(Request $request, Closure $next): mixed
    {
        $time = Carbon::now()->diffInMinutes(Auth::user()->last_fail_charge);
        if ($time >= 60) {
            Auth::user()->update(["charge_fail_attempts" => 0]);
        }
        if (Auth::user()->charge_fail_attempts >= 6) {
            return response()->json(['message' => "you exceeded maximum number of attempts to charge"]);
        }
        return $next($request);
    }
}
