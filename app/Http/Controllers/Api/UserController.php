<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

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
}
