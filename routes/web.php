<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;


// 1. Home / Feed
Route::get('/', [userController::class, 'index'])->name('index');

// 2. Authentication: Sign Up
Route::get('/signupView', [userController::class, 'signupView'])->name('signupView');
Route::post('signup', [userController::class, 'signup'])->name('signup');

// 3. Authentication: Log In
Route::get('/loginView', [userController::class, 'loginView'])->name('loginView');
Route::post('/login', [userController::class, 'login'])->name('login');

//logout
Route::post('/logout', [userController::class, 'logout'])->name('logout');

// 4. Password Reset
Route::get('/forgot-passwordView', [userController::class, 'forgotPasswordView'])->name('forgotPasswordView');
Route::post('/forgot-password', [userController::class, 'forgotPassword'])->name('forgotPassword');

// 5. Admin Dashboard
Route::get('/admin/dashboard', [userController::class, 'adminDashboard'])->name('admin.dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post', [PostController::class, 'store'])->name('post.store');
    Route::post('/logout', [userController::class, 'logout'])->name('logout');
});

Route::get('/', [PostController::class, 'index'])->name('index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('post.show');

Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');
