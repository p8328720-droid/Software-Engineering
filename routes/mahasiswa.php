<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\ReportController;
use App\Http\Controllers\Mahasiswa\TrackingController;

Route::prefix('mahasiswa')->name('mahasiswa.')->middleware(['auth', 'role:mahasiswa'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
    Route::get('/tracking/{code}', [TrackingController::class, 'show'])->name('tracking.show');
});