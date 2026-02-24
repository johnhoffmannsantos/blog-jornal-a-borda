<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sobre-nos', [AboutController::class, 'index'])->name('about');
Route::get('/nossa-equipe', [TeamController::class, 'index'])->name('team');
Route::get('/autor/{id}', [AuthorController::class, 'show'])->name('author.show');
Route::get('/categoria/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Quick Login (Development Only)
if (app()->environment('local')) {
    Route::get('/quick-login/{role}', [LoginController::class, 'quickLogin'])->name('quick-login');
}

// Admin Routes
Route::middleware('auth')->prefix('painel')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

// Post Route (must be last)
Route::get('/{slug}', [PostController::class, 'show'])->name('post.show');
