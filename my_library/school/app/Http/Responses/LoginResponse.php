<?php


namespace App\Http\Responses;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if (auth()->user()) {
            return response()->json(['success'=>'logged in',
                'token' => auth()->user()->createToken('user-token')->plainTextToken]);
        }
    }
}
