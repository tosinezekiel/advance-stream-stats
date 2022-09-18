<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function login(LoginRequest $request) : Response
    {
        if (!$token = Auth::guard('api')->attempt($request->only(['email', 'password']))) {
            return response([
                'status' => 'Error.',
                'message' => 'Unauthorized.',
            ], 401);
        }

        return response([
                'status' => 'Success.',
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
        ]);
    }
}
