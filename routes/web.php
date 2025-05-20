<?php

use App\Http\Controllers\User\UserAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('home');
});
Route::get('/login', [UserAuthController::class, 'login'])->name('login');
Route::post('login', [UserAuthController::class, 'login']);

Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserAuthController::class, 'register']);

Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
