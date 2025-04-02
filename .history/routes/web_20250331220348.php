<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContractController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('job-applications/export', [JobApplicationController::class, 'export'])->name('job-applications.export');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard görünümü için HomeController kullanımı
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

// İş başvuruları için resource controller tanımlamaları
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('job-applications', JobApplicationController::class);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ContractController::class, 'dashboard'])->name('dashboard');
    Route::get('/categories/{category}/subcategories', [ContractController::class, 'getSubcategories'])->name('categories.subcategories');
    Route::resource('contracts', ContractController::class);
});
