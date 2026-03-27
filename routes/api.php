<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\TrackingApiController;
use App\Http\Controllers\Api\StatisticsApiController;
use App\Http\Controllers\Api\FacilityApiController;
use App\Http\Controllers\Api\NotificationApiController;

// ==================== API V1 ROUTES ====================
Route::prefix('v1')->group(function () {
    
    // Public API (no authentication required)
    Route::post('/auth/login', [App\Http\Controllers\Api\AuthApiController::class, 'login']);
    
    // Protected API (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Auth
        Route::post('/auth/logout', [App\Http\Controllers\Api\AuthApiController::class, 'logout']);
        Route::get('/auth/me', [App\Http\Controllers\Api\AuthApiController::class, 'me']);
        
        // Reports API
        Route::apiResource('reports', ReportApiController::class);
        Route::get('/reports/{report}/tracking', [TrackingApiController::class, 'tracking']);
        Route::get('/reports/{report}/timeline', [TrackingApiController::class, 'timeline']);
        Route::post('/reports/{report}/comments', [ReportApiController::class, 'addComment']);
        Route::put('/reports/{report}/status', [ReportApiController::class, 'updateStatus']);
        
        // Statistics API
        Route::get('/statistics/dashboard', [StatisticsApiController::class, 'dashboard']);
        Route::get('/statistics/reports', [StatisticsApiController::class, 'reports']);
        Route::get('/statistics/sla-compliance', [StatisticsApiController::class, 'slaCompliance']);
        Route::get('/statistics/technician-performance', [StatisticsApiController::class, 'technicianPerformance']);
        
        // Facilities API
        Route::apiResource('facilities', FacilityApiController::class);
        Route::get('/facilities/{facility}/reports', [FacilityApiController::class, 'reports']);
        Route::get('/facilities/{facility}/risk-indicator', [FacilityApiController::class, 'riskIndicator']);
        
        // Notifications API
        Route::get('/notifications', [NotificationApiController::class, 'index']);
        Route::put('/notifications/{notification}/read', [NotificationApiController::class, 'markAsRead']);
        Route::put('/notifications/read-all', [NotificationApiController::class, 'markAllAsRead']);
        Route::delete('/notifications/{notification}', [NotificationApiController::class, 'destroy']);
        
        // User API
        Route::get('/users', [App\Http\Controllers\Api\UserApiController::class, 'index']);
        Route::get('/users/{user}', [App\Http\Controllers\Api\UserApiController::class, 'show']);
        Route::put('/users/{user}', [App\Http\Controllers\Api\UserApiController::class, 'update']);
        
        // Technician specific API
        Route::middleware(['role:teknisi'])->prefix('technician')->group(function () {
            Route::get('/tasks', [App\Http\Controllers\Api\TechnicianApiController::class, 'tasks']);
            Route::put('/tasks/{report}/start', [App\Http\Controllers\Api\TechnicianApiController::class, 'startTask']);
            Route::put('/tasks/{report}/complete', [App\Http\Controllers\Api\TechnicianApiController::class, 'completeTask']);
        });
        
        // Supervisor specific API
        Route::middleware(['role:supervisor'])->prefix('supervisor')->group(function () {
            Route::get('/monitoring', [App\Http\Controllers\Api\SupervisorApiController::class, 'monitoring']);
            Route::get('/escalations', [App\Http\Controllers\Api\SupervisorApiController::class, 'escalations']);
            Route::post('/escalations/{report}/assign', [App\Http\Controllers\Api\SupervisorApiController::class, 'assignEscalation']);
        });
        
        // Admin specific API
        Route::middleware(['role:admin'])->prefix('admin')->group(function () {
            Route::apiResource('users', App\Http\Controllers\Api\AdminUserApiController::class);
            Route::apiResource('facilities', App\Http\Controllers\Api\AdminFacilityApiController::class);
            Route::apiResource('sla', App\Http\Controllers\Api\AdminSLAApiController::class);
            Route::get('/audit-logs', [App\Http\Controllers\Api\AdminAuditApiController::class, 'index']);
            Route::get('/system-health', [App\Http\Controllers\Api\AdminSystemApiController::class, 'health']);
        });
    });
});

// ==================== WEBHOOK ROUTES ====================
Route::prefix('webhook')->group(function () {
    // External service webhooks
    Route::post('/sla-monitor', [App\Http\Controllers\Webhook\SLAMonitorController::class, 'handle']);
    Route::post('/report-notification', [App\Http\Controllers\Webhook\NotificationController::class, 'handle']);
});