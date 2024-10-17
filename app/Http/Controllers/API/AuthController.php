<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     **/
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]
        );

        if (! Auth::attempt($request->only('email', 'password'))) {

            return \response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
