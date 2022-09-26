<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/auth/login', [LoginController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', [UserController::class, 'profile']);
    Route::post('/auth/logout', [LogoutController::class, 'logout']);
    Route::get('/auth/token/generate', [SubscriptionController::class, 'clientToken']);
    Route::get('/plans', [PlanController::class, 'index']);
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::post('/subscriptions', [SubscriptionController::class, 'create']);
    Route::delete('/subscriptions', [SubscriptionController::class, 'destroy']);
});
