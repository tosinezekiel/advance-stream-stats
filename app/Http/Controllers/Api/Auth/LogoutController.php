<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout() : Response
    {
        Auth::logout();
        return response([
            'status' => 'Success.',
            'message' => 'Successfully logged out.',
        ], 200);
    }
}
