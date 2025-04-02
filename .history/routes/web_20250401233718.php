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
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
});
