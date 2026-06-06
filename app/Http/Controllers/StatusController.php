<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Report;
use App\Models\ReportStatus;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    /**
     * Peta transisi status yang diizinkan.
     * Status 'completed' dan 'rejected' adalah status final — tidak bisa berubah lagi.
     */
    private array $allowedTransitions = [
        'pending' => ['verified', 'in_progress', 'rejected'],
        'verified' => ['in_progress', 'rejected'],
        'in_progress' => ['completed', 'rejected'],
        'completed' => [],
        'rejected' => [],
    ];

    public function update(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,in_progress,completed,rejected',
            'description' => 'nullable|string',
        ]);

        $oldStatus = $report->status;
        $newStatus = $request->status;
        $user = Auth::user();

        // Validasi transisi status (state machine)
        $allowed = $this->allowedTransitions[$oldStatus] ?? [];

        if (! in_array($newStatus, $allowed)) {
            $message = empty($allowed)
                ? "Status '{$oldStatus}' adalah status final dan tidak dapat diubah."
                : "Transisi dari '{$oldStatus}' ke '{$newStatus}' tidak diizinkan.";

            return back()->with('error', $message);
        }

        // Update status laporan
        $report->update(['status' => $newStatus]);

        // Set resolved_at untuk status final
        if (in_array($newStatus, ['completed', 'rejected']) && ! $report->resolved_at) {
            $report->update(['resolved_at' => now()]);
        }

        // Catat riwayat status
        ReportStatus::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status' => $newStatus,
            'description' => $request->description ?? 'Status diperbarui oleh '.$user->name,
        ]);

        // Catat audit log
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $user->role.'_update_status',
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $newStatus],
            'ip_address' => $request->ip(),
        ]);

        // Kirim notifikasi ke mahasiswa pemilik laporan
        NotificationService::reportStatusUpdated($report, $oldStatus, $newStatus);

        return back()->with('success', 'Status laporan berhasil diperbarui');
    }
}
