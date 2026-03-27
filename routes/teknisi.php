<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teknisi\DashboardController;
use App\Http\Controllers\Teknisi\TaskController;
use App\Http\Controllers\Teknisi\ReportController;

Route::prefix('teknisi')->name('teknisi.')->middleware(['auth', 'role:teknisi'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
    
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
});