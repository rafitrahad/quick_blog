<?php

use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [userController::class, 'home'])->name('home');

Route::get('/loginView', [userController::class, 'loginView'])->name('loginView');

Route::post('/register', [userController::class, 'register'])->name('register');

Route::get('/signup', [userController::class, 'signup'])->name('signup');

Route::post('/login', [userController::class, 'login'])->name('login');

Route::post('/logout', [userController::class, 'logout'])->name('logout');
