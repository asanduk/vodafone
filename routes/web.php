<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\HomeController;

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
