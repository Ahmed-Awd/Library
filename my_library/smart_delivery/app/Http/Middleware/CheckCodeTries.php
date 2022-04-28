<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCodeTries
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::user()->code) {
            if (Auth::user()->code->number_of_tries >= 3) {
                return response()->json(['message' => "you exceeded maximum number of attempts to write code"], 400);
            }
        } else {
            return response()->json(['message' => "you don't have code"], 400);
        }

        return $next($request);
    }
}
