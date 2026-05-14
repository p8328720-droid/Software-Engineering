<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Report;

class NotificationService
{
    /**
     * Send notification to a single user
     */
    public static function send($userId, $title, $message, $type = 'info', $reportId = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'report_id' => $reportId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => false,
        ]);
    }

    /**
     * Send notification to all users with specific role
     */
    public static function sendToRole($role, $title, $message, $type = 'info', $reportId = null)
    {
        $users = User::where('role', $role)->get();
        $notifications = [];
        
        foreach ($users as $user) {
            $notifications[] = [
                'user_id' => $user->id,
                'report_id' => $reportId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        if (!empty($notifications)) {
            return Notification::insert($notifications);
        }
        
        return true;
    }

    /**
     * Send notification when new report is created (to all teknisi)
     */
    public static function newReportCreated(Report $report)
    {
        $title = 'Laporan Baru #' . str_pad($report->id, 5, '0', STR_PAD_LEFT);
        $message = 'Laporan baru dari ' . $report->user->name . ' tentang "' . $report->title . '"';
        
        return self::sendToRole('teknisi', $title, $message, 'info', $report->id);
    }

    /**
     * Send notification when report status is updated (to mahasiswa who made the report)
     */
    public static function reportStatusUpdated(Report $report, $oldStatus, $newStatus)
    {
        $statusLabels = [
            'pending' => 'Menunggu',
            'verified' => 'Diverifikasi',
            'in_progress' => 'Diproses',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak'
        ];
        
        $title = 'Update Status Laporan #' . str_pad($report->id, 5, '0', STR_PAD_LEFT);
        $message = 'Status laporan Anda berubah dari "' . ($statusLabels[$oldStatus] ?? $oldStatus) . '" menjadi "' . ($statusLabels[$newStatus] ?? $newStatus) . '"';
        
        $type = match($newStatus) {
            'completed' => 'success',
            'rejected' => 'danger',
            'in_progress' => 'warning',
            default => 'info',
        };
        
        return self::send($report->user_id, $title, $message, $type, $report->id);
    }
}