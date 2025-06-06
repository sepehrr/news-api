<?php

use App\Http\Controllers\V1\ArticleController;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\PersonalizedFeedController;
use App\Http\Controllers\V1\PreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    // Auth routes
    Route::middleware('throttle:60,1')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('/auth/register', [AuthController::class, 'registration'])->name('auth.register');
        Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');
        Route::post('/auth/set-password', [AuthController::class, 'setPassword'])->name('auth.set-password');
    });

    Route::middleware(['auth:sanctum', 'throttle:60,1'])->post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Article routes
    Route::middleware('throttle:120,1')->group(function () {
        Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
        Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    });

    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        // Preference routes
        Route::get('/preferences', [PreferenceController::class, 'index'])->name('preferences.index');
        Route::post('/preferences', [PreferenceController::class, 'update'])->name('preferences.update');

        // Personalized feed routes
        Route::get('/personalized-feed', [PersonalizedFeedController::class, 'index'])->name('personalized-feed.index');
    });
});
