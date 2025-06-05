<?php

use App\Http\Controllers\V1\ArticleController;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\PreferenceController;
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
    Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);

    // Article routes
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{article}', [ArticleController::class, 'show']);

    // Preference routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/preferences', [PreferenceController::class, 'index']);
        Route::post('/preferences', [PreferenceController::class, 'update']);
    });

});
