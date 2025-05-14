<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\Vendor\VendorAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'admin'])->group(
    function () {
        Route::get('/user', [AdminAuthController::class, 'getUser']);
        Route::post('/logout', [AdminAuthController::class, 'logout']);

        Route::apiResource('products', ProductController::class);
    });

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login')
        ->middleware('guest');;
});
Route::prefix('vendor')->group(function () {
    Route::post('/register', [VendorAuthController::class, 'register']);
    Route::post('/login', [VendorAuthController::class, 'login']);
});

Route::prefix('user')->group(function () {
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/logout', [UserAuthController::class, 'logout']);;
});
