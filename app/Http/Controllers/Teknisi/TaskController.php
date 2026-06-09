<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Report;
use App\Models\ReportStatus;
use App\Services\NotificationService;

class TaskController extends Controller
{
    public function index()
    {
        $activeTasks = Report::with('facility')
            ->whereIn('status', ['pending', 'verified', 'in_progress']) // ✅ tambah 'verified'
            ->latest()
            ->get();

        return view('teknisi.tasks.index', compact('activeTasks'));
    }

    public function show(Report $report)
    {
        if (in_array($report->status, ['verified'])) { // ✅ ganti == 'pending'
            $oldStatus = $report->status;

            $report->update(['status' => 'in_progress']);

            ReportStatus::create([
                'report_id' => $report->id,
                'user_id' => auth()->id(),
                'status' => 'in_progress',
                'description' => 'Teknisi mulai memproses laporan',
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'teknisi_open_task',
                'auditable_type' => Report::class,
                'auditable_id' => $report->id,
                'old_values' => ['status' => $oldStatus], // ✅ pakai $oldStatus
                'new_values' => ['status' => 'in_progress'],
                'ip_address' => request()->ip(),
            ]);

            NotificationService::reportStatusUpdated($report, $oldStatus, 'in_progress'); // ✅ pakai $oldStatus
        }

        $report->load('comments.user', 'statusHistory.user', 'facility', 'user');

        return view('teknisi.tasks.show', compact('report'));
    }
}