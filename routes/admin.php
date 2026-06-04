<?php

use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\EscalationController;
// 1. Ganti import Controller
use App\Http\Controllers\Admin\RoomController; 
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\SLAController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // 2. Ganti Route Facilities menjadi Rooms
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    // SLA & Audit
    Route::get('/sla', [SLAController::class, 'index'])->name('sla.index');
    Route::get('/sla/{id}/edit', [SLAController::class, 'edit'])->name('sla.edit');
    Route::put('/sla/{id}', [SLAController::class, 'update'])->name('sla.update');

    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');

    // Monitoring & Escalation
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/{id}', [MonitoringController::class, 'show'])->name('monitoring.show');
    Route::get('/escalation', [EscalationController::class, 'index'])->name('escalation.index');

    // Reports Management
    Route::patch('/reports/{report}/assign', [ReportController::class, 'assignTechnician'])->name('reports.assign');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
});