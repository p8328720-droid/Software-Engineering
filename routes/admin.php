<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\SLAController;
use App\Http\Controllers\Admin\AuditController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    
    Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('/facilities/create', [FacilityController::class, 'create'])->name('facilities.create');
    Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
    Route::get('/facilities/{id}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::put('/facilities/{id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/facilities/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    
    Route::get('/sla', [SLAController::class, 'index'])->name('sla.index');
    Route::get('/sla/{id}/edit', [SLAController::class, 'edit'])->name('sla.edit');
    Route::put('/sla/{id}', [SLAController::class, 'update'])->name('sla.update');
    
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
});