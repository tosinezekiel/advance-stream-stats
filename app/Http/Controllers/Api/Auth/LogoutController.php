<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout() : Response
    {
        Auth::logout();
        return response([
            'status' => true,
            'message' => 'Successfully logged out.',
        ], 200);
    }
}
