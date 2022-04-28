<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Auth;

class LoginResponse implements LoginResponseContract
{
    /**
     * @param  $request
     * @return mixed
     */
    public function toResponse($request)
    {
        if (auth()->user()->hasRole('driver')) {
            Auth::logout();
            return    redirect('/login')->with('error', 'access denied');
        } else {
            return    redirect()->route('dashboard');
        }
    }
}
