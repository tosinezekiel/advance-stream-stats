<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request) : Response
    {
        if (!$token = Auth::guard('api')->attempt($request->only(['email', 'password']))) {
            return response([
                'status' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        return response([
                'status' => true,
                'user' => new UserResource(auth()->user()),
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
        ]);
    }
}
