<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['username' => $request->email, 'password' => $request->password]) ||
            Auth::attempt(['email' => $request->email, 'password' => $request->password]) ||
            Auth::attempt(['id_number' => $request->email, 'password' => $request->password])
        ) {
            $user = User::where('email', $request['email'])
                ->orWhere('id_number',$request['email'])
                ->orWhere('username',$request['email'])
                ->first();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        return response()->json([
            'message' => 'Invalid login details'
        ], 401);
    }

    public function logout(Request $request){
        auth()->user()->currentAccessToken()->delete();
        
    }
}
