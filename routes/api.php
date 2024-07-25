<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {

    Route::prefix('/auth')->group(function () {
        Route::post('/registration', [AuthController::class, 'register'])->withoutMiddleware('auth:api');
        Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:api');
        Route::get('/logout', [AuthController::class, 'logout']);
    });

    Route::prefix('/tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::get('/{taskId}', [TaskController::class, 'show']);
        Route::post('/', [TaskController::class, 'store']);
        Route::put('/{taskId}', [TaskController::class, 'update']);
        Route::patch('/{taskId}/mark-as-completed', [TaskController::class, 'markAsCompleted']);
        Route::delete('/{taskId}', [TaskController::class, 'destroy']);
    });
});