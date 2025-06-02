<?php

use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return view('home');
});

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/register', function () {
    return view('auth.register');
})->name('user.register.form');

Route::post('/register', [UserAuthController::class, 'register'])->name('user.register');

Route::get('/login', function () {
    return view('auth.login');
})->name('user.login.form');

Route::post('/login', [UserAuthController::class, 'login'])->name('user.login');

Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth')->name('user.logout');

// Cart Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update-quantity/{product}', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    
    // Checkout Routes
    Route::get('/checkout', function() {
        return view('checkout.index');
    })->name('checkout.index');
});
