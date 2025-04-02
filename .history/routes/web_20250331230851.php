<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Admin\CategoryController;

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
    Route::resource('contracts', ContractController::class);
});

// Admin rotalarını ayrı bir grup olarak tanımlayalım
Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth:sanctum', 'verified', \App\Http\Middleware\AdminMiddleware::class], // Middleware'i doğrudan sınıf olarak belirtelim
    'as' => 'admin.',
    'namespace' => 'App\Http\Controllers\Admin'
], function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::put('/admin/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])
        ->name('admin.categories.update');
});
