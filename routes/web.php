<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/autor/{id}', [AuthorController::class, 'show'])->name('author.show');
Route::get('/categoria/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/{slug}', [PostController::class, 'show'])->name('post.show');
