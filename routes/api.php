<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;

// ========================= Authentication APIS =========================
Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('signup', 'signup');
        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware('auth:api');
    });

// ========================= Task Management APIS =========================
Route::middleware('auth:api')
    ->prefix('tasks')
    ->controller(TaskController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('{id}', 'show');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');
        Route::post('{id}/toggle', 'toggleComplete');
        Route::post('/{id}/reassign ', 'assign');
    });
