<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportStatus;
use App\Models\AuditLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function update(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,in_progress,completed,rejected',
            'description' => 'nullable|string',
        ]);

        $oldStatus = $report->status;
        $user = Auth::user();
        
        $report->update(['status' => $request->status]);

        if ($request->status == 'completed' && !$report->resolved_at) {
            $report->update(['resolved_at' => now()]);
        }
        
        if ($request->status == 'rejected') {
            $report->update(['resolved_at' => now()]);
        }

        ReportStatus::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status' => $request->status,
            'description' => $request->description ?? 'Status diperbarui oleh ' . $user->name,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $user->role . '_update_status',
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $request->status],
            'ip_address' => $request->ip(),
        ]);

        NotificationService::reportStatusUpdated($report, $oldStatus, $request->status);

        return back()->with('success', 'Status laporan berhasil diperbarui');
    }
}