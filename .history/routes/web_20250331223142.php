<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    // Normal kullanıcı route'ları
    Route::get('/dashboard', [ContractController::class, 'dashboard'])->name('dashboard');
    Route::get('/categories/{category}/subcategories', [ContractController::class, 'getSubcategories'])->name('categories.subcategories');
    Route::resource('contracts', ContractController::class);

    // Admin route'ları
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    });

    // Diğer route'lar
    Route::get('job-applications/export', [JobApplicationController::class, 'export'])->name('job-applications.export');
    Route::resource('job-applications', JobApplicationController::class);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard görünümü için HomeController kullanımı
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
