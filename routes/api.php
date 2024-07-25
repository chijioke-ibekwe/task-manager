<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {

    Route::prefix('/auth')->group(function () {
        Route::post('/registration', [AuthController::class, 'register'])->withoutMiddleware('auth:api');
        Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:api');
        Route::get('/logout', [AuthController::class, 'logout']);
    });
});