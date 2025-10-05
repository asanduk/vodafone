<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;

// Ana sayfa
Route::get('/', function () {
    return view('welcome');
});

// Auth gerektiren rotalar
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    // Normal kullanıcı rotaları
    Route::get('/dashboard', [ContractController::class, 'dashboard'])->name('dashboard');
    Route::get('/contracts/export', [ContractController::class, 'export'])->name('contracts.export');
    Route::get('/categories/{category}/subcategories', [ContractController::class, 'getSubcategories'])->name('categories.subcategories');
    Route::get('/categories/{category}/search-subcategories', [ContractController::class, 'searchSubcategories'])->name('categories.search-subcategories');
    Route::resource('contracts', ContractController::class);
});

// Admin rotaları
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth:sanctum', 'verified', \App\Http\Middleware\AdminMiddleware::class],
    'as' => 'admin.',
], function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/users/archived', [UserController::class, 'archived'])->name('users.archived');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    Route::get('/users/{user}/export', [UserController::class, 'exportUser'])->name('users.export.single');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::delete('/users/{user}/force', [UserController::class, 'forceDelete'])->name('users.force-delete');
    Route::post('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
});
