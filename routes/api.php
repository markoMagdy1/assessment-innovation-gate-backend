<?php

use App\Http\Controllers\API\AuthController;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('signup', 'signup');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:api');
});

