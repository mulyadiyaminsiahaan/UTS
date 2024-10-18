<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PinnedController;


Route::prefix('auth')->group(function () {
    Route::get("/register", [AuthController::class, "getRegister"])->name("register");
    Route::post("/register", [AuthController::class, "postRegister"])->name("post.register");
    Route::get("/login", [AuthController::class, "getLogin"])->name("login");
    Route::post("/login", [AuthController::class, "postLogin"])->name("post.login");
    Route::get("/logout", [AuthController::class, "getlogout"])->name("logout");
});

Route::middleware('auth')->group(function () {
    Route::get("/", [HomeController::class, "index"])->name("home");

    Route::prefix('pinned')->group(function () {
        Route::post("/add", [PinnedController::class, "postAdd"])->name("post.pinned.add");
        //tambahkan post edit dan delete
        Route::post("/edit", [PinnedController::class, "postEdit"])->name("post.pinned.edit");
        Route::post("/delete", [PinnedController::class, "postDelete"])->name("post.pinned.delete");
    });
});