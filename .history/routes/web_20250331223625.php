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

    // Admin rotaları
    Route::prefix('admin')->middleware(['admin'])->name('admin.')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    });
});
