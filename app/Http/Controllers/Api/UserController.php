<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function profile() : Response
    {
        $user = auth()->user();
        return response([
            'status' => 'Success.',
            'data' => $user,
        ], 200);
    }
}
