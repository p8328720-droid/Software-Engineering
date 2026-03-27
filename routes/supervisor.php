<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Supervisor\DashboardController;
use App\Http\Controllers\Supervisor\MonitoringController;
use App\Http\Controllers\Supervisor\EscalationController;

Route::prefix('supervisor')->name('supervisor.')->middleware(['auth', 'role:supervisor'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/{id}', [MonitoringController::class, 'show'])->name('monitoring.show');
    
    Route::get('/escalation', [EscalationController::class, 'index'])->name('escalation.index');
});