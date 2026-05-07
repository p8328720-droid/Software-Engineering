<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\EscalationController;

// Halaman utama redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Dashboard (protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route for submitting comments on reports
    Route::post('/reports/{report}/comments', [ReportController::class, 'storeComment'])->name('reports.comments.store');
});

// Escalation routes (protected by auth)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/escalation', [EscalationController::class, 'index'])->name('admin.escalation.index');
    Route::post('/escalation/{report}/escalate', [EscalationController::class, 'escalate'])->name('admin.escalation.escalate');
    Route::post('/escalation/{report}/ignore', [EscalationController::class, 'ignore'])->name('admin.escalation.ignore');
});

// Include route files
require __DIR__.'/admin.php';
require __DIR__.'/pelapor.php';
require __DIR__.'/teknisi.php';