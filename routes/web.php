<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::get('register', [AuthController::class, 'getRegister'])->name('register');
    Route::post('register', [AuthController::class, 'postRegister'])->name('post.register');
    Route::get('login', [AuthController::class, 'getLogin'])->name('login');
    Route::post('login', [AuthController::class, 'postLogin'])->name('post.login');
    Route::get('logout', [AuthController::class, 'gettLogout'])->name('logout');
});

Route::midleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});