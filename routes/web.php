<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// 1. Public Feed & Show Post
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

// 2. Authentication (Guests Only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.authenticate');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// 3. Authenticated Actions (All logged-in users)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/hide', [CommentController::class, 'hide'])->name('comments.hide');

    // User Dashboard & Profile Management
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::put('/dashboard/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::put('/dashboard/password', [UserController::class, 'updatePassword'])->name('user.password.update');
    Route::delete('/dashboard/deactivate', [UserController::class, 'deactivate'])->name('user.deactivate');
});

// 4. Admin Only
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::put('/posts/{id}/restore', [AdminController::class, 'restore'])->name('posts.restore');
    Route::delete('/posts/{id}/force-delete', [AdminController::class, 'forceDelete'])->name('posts.forceDelete');
    Route::delete('/posts/empty-trash', [AdminController::class, 'emptyTrash'])->name('posts.emptyTrash');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});
