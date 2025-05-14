<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Vendor\VendorAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'admin'])->group(
    function () {
        Route::get('/user', [AdminAuthController::class, 'getUser']);
        Route::post('/logout', [AdminAuthController::class, 'logout']);

        Route::apiResource('products', ProductController::class);
    });

Route::post('/login', [AdminAuthController::class, 'login'])
    ->name('login')
    ->middleware('guest');
Route::post('/vendor/register', [VendorAuthController::class, 'register']);

