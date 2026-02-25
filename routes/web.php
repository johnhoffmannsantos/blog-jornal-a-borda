<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController as PublicTagController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\JournalEditionController as AdminJournalEditionController;
use App\Http\Controllers\JournalEditionController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sobre-nos', [AboutController::class, 'index'])->name('about');
Route::get('/nossa-equipe', [TeamController::class, 'index'])->name('team');
Route::get('/autor/{id}', [AuthorController::class, 'show'])->name('author.show');
Route::get('/categoria/{slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/tag/{slug}', [PublicTagController::class, 'show'])->name('tag.show');
Route::get('/jornal-digital', [JournalEditionController::class, 'index'])->name('journal-editions.index');
Route::get('/jornal-digital/{slug}', [JournalEditionController::class, 'show'])->name('journal-editions.show');
Route::get('/jornal-digital/{slug}/download', [JournalEditionController::class, 'download'])->name('journal-editions.download');

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
    
    // Profile
    Route::get('/perfil', [ProfileController::class, 'index'])->name('profile');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    
    // Posts
    Route::resource('posts', AdminPostController::class);
    
    // Categories
    Route::resource('categories', AdminCategoryController::class);
    Route::delete('/categories/{category}/force', [AdminCategoryController::class, 'forceDestroy'])->name('categories.forceDestroy');
    
    // Tags
    Route::resource('tags', TagController::class);
    Route::delete('/tags/{tag}/force', [TagController::class, 'forceDestroy'])->name('tags.forceDestroy');
    
    // Users (Admin only)
    Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    
    // Image Upload (TinyMCE)
    Route::post('/upload-image', [ImageUploadController::class, 'upload'])->name('upload.image');
    
    // Partners (Admin only)
    Route::resource('partners', PartnerController::class);
    
    // Journal Editions (Admin only)
    Route::resource('journal-editions', AdminJournalEditionController::class);
    
    // Comments
    Route::get('/comentarios', [CommentController::class, 'index'])->name('comments.index');
    Route::put('/comentarios/{comment}/status', [CommentController::class, 'updateStatus'])->name('comments.updateStatus');
    Route::delete('/comentarios/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Settings (Admin only)
    Route::get('/configuracoes', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/configuracoes', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/configuracoes/test-email', [SettingsController::class, 'testEmail'])->name('settings.testEmail');
});

// Post Comment Route (must be before catch-all post route)
Route::post('/{slug}/comentario', [PostController::class, 'storeComment'])->name('post.comment.store');

// Post Route (must be last)
Route::get('/{slug}', [PostController::class, 'show'])->name('post.show');
