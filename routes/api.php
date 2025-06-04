<?php

use App\Http\Controllers\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'registration']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/auth/set-password', [AuthController::class, 'setPassword']);
    // sanctum auth:
    Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);
    // logout:
    // Route::post('/auth/logout', [AuthController::class, 'logout']);
});
