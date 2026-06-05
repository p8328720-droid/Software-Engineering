<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Teknisi\TaskController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

// ========== LANDING PAGE ==========
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ========== GLOBAL LOGIN REDIRECT ==========
// Route ini untuk menangani redirect dari middleware auth
Route::get('/login', function () {
    return redirect()->route('mahasiswa.login');
})->name('login');

// ========== MAHASISWA ==========
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showMahasiswaLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'mahasiswaLogin']);
    
    // Register
    Route::get('/register', [AuthController::class, 'showMahasiswaRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'mahasiswaRegister']);
    
    // Protected routes
    Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'mahasiswaDashboard'])->name('dashboard');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::post('/reports/{report}/rating', [ReportController::class, 'rating'])->name('reports.rating');
        Route::post('/reports/{report}/comment', [ReportController::class, 'addComment'])->name('reports.comment');
        Route::get('/tracking', [ReportController::class, 'tracking'])->name('tracking');
        Route::get('/tracking/search', [ReportController::class, 'searchTracking'])->name('tracking.search');
    });
});


// ========== TEKNISI ==========
Route::prefix('teknisi')->name('teknisi.')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showTeknisiLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'teknisiLogin']);
    
    // Register
    Route::get('/register', [AuthController::class, 'showTeknisiRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'teknisiRegister']);
    
    // Protected routes
    Route::middleware(['auth', 'role:teknisi'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'teknisiDashboard'])->name('dashboard');
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{report}', [TaskController::class, 'show'])->name('tasks.show');
        Route::post('/reports/{report}/status', [StatusController::class, 'update'])->name('status.update');
        Route::post('/reports/{report}/comment', [ReportController::class, 'addComment'])->name('reports.comment');
    });
});


// ========== ADMIN ==========
Route::prefix('admin')->name('admin.')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin']);
    
    // Register
    Route::get('/register', [AuthController::class, 'showAdminRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'adminRegister']);
    
    // Protected routes
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Facility Management
        Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities');
        Route::get('/facilities/create', [FacilityController::class, 'create'])->name('facilities.create');
        Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
        Route::get('/facilities/{facility}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
        Route::put('/facilities/{facility}', [FacilityController::class, 'update'])->name('facilities.update');
        Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
        
        // Report Management
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{id}/edit', [AdminReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{id}', [AdminReportController::class, 'update'])->name('reports.update');
        Route::delete('/reports/{id}', [AdminReportController::class, 'destroy'])->name('reports.destroy');
Route::delete('/reports/{id}/rating', [AdminReportController::class, 'deleteRating'])->name('reports.delete-rating');
Route::post('/reports/{report}/comment', [AdminReportController::class, 'addComment'])->name('reports.comment'); // ← TAMBAHKAN
        // Audit Trail
       Route::get('/audit', [AuditController::class, 'index'])->name('audit');
    Route::get('/audit/{id}/detail', [AuditController::class, 'detail'])->name('audit.detail');
    });
});


// ========== NOTIFICATIONS ==========
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
    Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');
    Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
});


// ========== LOGOUT ==========
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== Rating ==========
// Route::post('/mahasiswa/reports/{report}/rating', [ReportController::class, 'rating'])->name('mahasiswa.reports.rating');
// Route::post('/mahasiswa/reports/{report}/comment', [ReportController::class, 'addComment'])->name('mahasiswa.reports.comment');
// Route::post('/teknisi/reports/{report}/comment', [ReportController::class, 'addComment'])->name('teknisi.reports.comment');
// Route::post('/admin/reports/{report}/comment', [ReportController::class, 'addComment'])->name('admin.reports.comment');