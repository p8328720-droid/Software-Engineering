<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportStatus;
use App\Models\AuditLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
 
class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['user', 'facility'])->latest()->paginate(15);
 
        $stats = [
            'total'       => Report::count(),
            'pending'     => Report::where('status', 'pending')->count(),
            'in_progress' => Report::where('status', 'in_progress')->count(),
            'completed'   => Report::where('status', 'completed')->count(),
        ];
 
        return view('admin.reports.index', compact('reports', 'stats'));
    }
 
    public function edit($id)
    {
        $report = Report::with(['user', 'facility', 'statusHistory.user', 'comments.user'])
            ->findOrFail($id);
 
        $statuses = [
            'pending'     => 'Menunggu',
            'verified'    => 'Diverifikasi',
            'in_progress' => 'Diproses',
            'completed'   => 'Selesai',
            'rejected'    => 'Ditolak',
        ];
 
        return view('admin.reports.edit', compact('report', 'statuses'));
    }
 
    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);
 
        $request->validate([
            // ✅ FIX: admin_note dihapus — kolom sudah tidak ada di DB (migration remove_admin_note)
            'status' => 'required|in:pending,verified,in_progress,completed,rejected',
        ]);
 
        $oldStatus = $report->status;
 
        // ✅ FIX: hanya update 'status', tidak menyertakan admin_note
        $report->update(['status' => $request->status]);
 
        if ($request->status === 'completed' && ! $report->resolved_at) {
            $report->update(['resolved_at' => now()]);
        }
 
        ReportStatus::create([
            'report_id'   => $report->id,
            'user_id'     => Auth::id(),
            'status'      => $request->status,
            'description' => 'Status diubah oleh admin',
        ]);
 
        AuditLog::create([
            'user_id'        => Auth::id(),
            'action'         => 'admin_update_report_status',
            'auditable_type' => Report::class,
            'auditable_id'   => $report->id,
            'old_values'     => ['status' => $oldStatus],
            'new_values'     => ['status' => $request->status],
            'ip_address'     => $request->ip(),
        ]);
 
        NotificationService::reportStatusUpdated($report, $oldStatus, $request->status);
 
        return redirect()->route('admin.reports.index')
            ->with('success', 'Status laporan berhasil diperbarui');
    }
 
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
 
        AuditLog::create([
            'user_id'        => Auth::id(),
            'action'         => 'admin_delete_report',
            'auditable_type' => Report::class,
            'auditable_id'   => $report->id,
            'old_values'     => ['title' => $report->title],
            'ip_address'     => request()->ip(),
        ]);
 
        $report->delete();
 
        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus');
    }
 
    public function deleteRating($id)
    {
        $report = Report::findOrFail($id);
        $report->update(['rating' => null, 'rating_comment' => null]);
 
        return redirect()->route('admin.reports.edit', $report->id)
            ->with('success', 'Rating berhasil dihapus');
    }
 
    public function addComment(Request $request, Report $report)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);
 
        $comment = Comment::create([
            'report_id' => $report->id,
            'user_id'   => Auth::id(),
            'comment'   => $request->comment,
            'user_type' => 'admin',
        ]);
 
        $comment->load('user');
 
        $user    = Auth::user();
        $title   = 'Komentar Baru pada Laporan #' . str_pad($report->id, 5, '0', STR_PAD_LEFT);
        $message = $user->name . ' (Admin) menambahkan komentar: "' . substr($comment->comment, 0, 100) . '"';
 
        NotificationService::send($report->user_id, $title, $message, 'info', $report->id);
 
        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan',
            'comment' => [
                'id'         => $comment->id,
                'comment'    => $comment->comment,
                'user_name'  => $comment->user->name,
                'user_role'  => $comment->user->role,
                'created_at' => $comment->created_at->format('d M Y, H:i'),
                'is_mine'    => true,
            ],
        ]);
    }
}