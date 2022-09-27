<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile() : Response
    {
        $user = auth()->user();
        return response([
            'status' => true,
            'user' => new UserResource($user),
        ], 200);
    }

    public function refreshToken(Request $request) : Response
    {
        \Log::info($request->refreshToken);
        if (!$token = auth('api')->refresh()) {
            return response([
                'status' => 'Error.',
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
