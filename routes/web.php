<?php

use App\Http\Controllers\User\UserAuthController;
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
