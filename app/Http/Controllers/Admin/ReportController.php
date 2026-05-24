<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportStatus;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['user', 'facility'])->latest()->paginate(15);
        
        $stats = [
            'total' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'in_progress' => Report::where('status', 'in_progress')->count(),
            'completed' => Report::where('status', 'completed')->count(),
        ];
        
        return view('admin.reports.index', compact('reports', 'stats'));
    }

    public function edit($id)
    {
        $report = Report::with(['user', 'facility', 'statusHistory.user'])->findOrFail($id);
        
        $statuses = [
            'pending' => 'Menunggu', 'verified' => 'Diverifikasi',
            'in_progress' => 'Diproses', 'completed' => 'Selesai', 'rejected' => 'Ditolak'
        ];
        
        return view('admin.reports.edit', compact('report', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,verified,in_progress,completed,rejected',
            'admin_note' => 'nullable|string',
        ]);
        
        $oldStatus = $report->status;
        $report->update(['status' => $request->status, 'admin_note' => $request->admin_note]);
        
        if ($request->status == 'completed' && !$report->resolved_at) {
            $report->update(['resolved_at' => now()]);
        }
        
        ReportStatus::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status' => $request->status,
            'description' => $request->admin_note ?? 'Status diubah oleh admin',
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'admin_update_report_status',
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $request->status],
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.reports.index')->with('success', 'Status laporan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'admin_delete_report',
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'old_values' => ['title' => $report->title],
            'ip_address' => request()->ip(),
        ]);
        
        $report->delete();
        return redirect()->route('admin.reports.index')->with('success', 'Laporan berhasil dihapus');
    }

    public function deleteRating($id)
    {
        $report = Report::findOrFail($id);
        
        $report->update(['rating' => null, 'rating_comment' => null]);
        
        return redirect()->route('admin.reports.edit', $report->id)->with('success', 'Rating berhasil dihapus');
    }
}