<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportStatus;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $activeTasks = Report::with('facility')
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest()
            ->get();
        
        return view('teknisi.tasks.index', compact('activeTasks'));
    }

    // Tambahkan dua import ini (baris 5-8):
use App\Models\AuditLog;
use App\Services\NotificationService;



// SESUDAH:
public function show(Report $report)
{
    if ($report->status == 'pending') {
        $report->update(['status' => 'in_progress']);
        
        ReportStatus::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'status' => 'in_progress',
            'description' => 'Teknisi mulai memproses laporan'
        ]);

        // ← TAMBAHKAN: catat di audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'teknisi_open_task',
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'old_values' => ['status' => 'pending'],
            'new_values' => ['status' => 'in_progress'],
            'ip_address' => request()->ip(),
        ]);

        // ← TAMBAHKAN: notifikasi ke mahasiswa
        NotificationService::reportStatusUpdated($report, 'pending', 'in_progress');
    }

    $report->load('comments.user', 'statusHistory.user', 'facility', 'user');
    return view('teknisi.tasks.show', compact('report'));
        
    }
}