<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'admin'])->group(
    function () {
        Route::get('/user', [\App\Http\Controllers\Api\AuthController::class, 'getUser']);
        Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

        Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);
    });


Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])
    ->name('login')
    ->middleware('guest');
