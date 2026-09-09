<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/users', [UserController::class, 'store']);
Route::get('/users', [UserController::class, 'index']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth.sanctum.custom')->group(function () {
    Route::get('/user', [UserController::class, 'me']);
    Route::post('/logout', [UserController::class, 'logout']);

    Route::prefix('users/{userId}')->group(function () {
        Route::get('/tasks', [TaskController::class, 'index']);
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::put('/tasks/{task}', [TaskController::class, 'update']);
        Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete']);
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    });
});