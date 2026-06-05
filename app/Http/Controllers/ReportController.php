<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Facility;
use App\Models\ReportStatus;
use App\Models\AuditLog;
use App\Models\Comment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('facility')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('mahasiswa.reports.index', compact('reports'));
    }

    public function create()
    {
        $facilities = Facility::where('is_active', true)->get();
        return view('mahasiswa.reports.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'facility_id' => 'required|exists:facilities,id',
            'location_detail' => 'required|string',
            'urgency' => 'required|in:low,medium,high',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

$data = $request->only([
    'title', 'facility_id', 'location_detail', 'urgency', 'description'
]);
$data['user_id']    = Auth::id();
$data['status']     = 'pending';
$data['sla_deadline'] = now()->addHours($slaHours);
        
        $facility = Facility::find($request->facility_id);
        $baseHours = $facility->sla_hours ?? 48;
        
        switch ($request->urgency) {
            case 'high': $slaHours = $baseHours * 0.5; break;
            case 'medium': $slaHours = $baseHours * 0.75; break;
            default: $slaHours = $baseHours; break;
        }
        
        $data['sla_deadline'] = now()->addHours($slaHours);
        
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('reports', 'public');
        }

        $report = Report::create($data);

 ReportStatus::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'description' => 'Laporan berhasil dikirim, menunggu verifikasi'
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'create_report',
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'ip_address' => $request->ip(),
        ]);

        NotificationService::newReportCreated($report);

        return redirect()->route('mahasiswa.reports.show', $report)
            ->with('success', 'Laporan berhasil dikirim');
    }

public function show(Report $report)
{
    $user = Auth::user();
    if ($report->user_id !== $user->id && !in_array($user->role, ['admin', 'teknisi'])) {
        abort(403);
    }
    $report->load('comments.user', 'statusHistory.user', 'facility');

    $view = match($user->role) {
        'admin'    => 'admin.reports.show',
        'teknisi'  => 'teknisi.tasks.show',
        default    => 'mahasiswa.reports.show',
    };

    return view($view, compact('report'));
}

    // Submit rating for completed report (only by the reporter, once)
 
    public function rating(Request $request, Report $report)
    {
        // Validasi: hanya pemilik laporan
        if ($report->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke laporan ini.'
            ], 403);
        }
        
        // Validasi: laporan harus selesai
        if ($report->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya laporan yang sudah selesai yang dapat diberi rating.'
            ], 400);
        }
        
        // Validasi: hanya sekali
        if ($report->rating !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memberikan rating untuk laporan ini.'
            ], 400);
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'rating_comment' => 'nullable|string|max:500',
        ]);
        
        // Update rating
        $report->update([
            'rating' => $request->rating,
            'rating_comment' => $request->rating_comment,
        ]);
        
        // Kirim notifikasi ke teknisi
        NotificationService::sendToRole(
            'teknisi',
            'Rating Baru untuk Laporan #' . str_pad($report->id, 5, '0', STR_PAD_LEFT),
            'Mahasiswa memberi rating ' . $request->rating . ' bintang untuk laporan yang Anda tangani',
            'success',
            $report->id
        );
        
        // Kirim notifikasi ke admin
        NotificationService::sendToRole(
            'admin',
            'Rating Baru untuk Laporan #' . str_pad($report->id, 5, '0', STR_PAD_LEFT),
            'Mahasiswa memberi rating ' . $request->rating . ' bintang',
            'info',
            $report->id
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas rating dan masukan Anda!',
            'rating' => $report->rating,
            'rating_comment' => $report->rating_comment
        ]);
    }

    /**
     * Add comment to report
     */
    public function addComment(Request $request, Report $report)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);
        
        $comment = Comment::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'user_type' => Auth::user()->role,
        ]);
        
        // Load user data untuk response
        $comment->load('user');
        
        // Kirim notifikasi ke pihak terkait
        $this->sendCommentNotification($report, $comment);
        
        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan',
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user_name' => $comment->user->name,
                'user_role' => $comment->user->role,
                'user_avatar' => $comment->user->avatar_url,
                'created_at' => $comment->created_at->format('d M Y, H:i')
            ]
        ]);
    }
    
    /**
     * Send notification when comment is added
     */
    private function sendCommentNotification($report, $comment)
    {
        $user = Auth::user();
        $title = 'Komentar Baru pada Laporan #' . str_pad($report->id, 5, '0', STR_PAD_LEFT);
        $message = $user->name . ' (' . $user->role . ') menambahkan komentar: "' . substr($comment->comment, 0, 100) . '"';
        
        // Notifikasi ke pemilik laporan (jika bukan dirinya sendiri)
        if ($report->user_id !== $user->id) {
            NotificationService::send($report->user_id, $title, $message, 'info', $report->id);
        }
        
        // Notifikasi ke teknisi dan admin
        if ($user->role == 'mahasiswa') {
            NotificationService::sendToRole('teknisi', $title, $message, 'info', $report->id);
            NotificationService::sendToRole('admin', $title, $message, 'info', $report->id);
        }
    }

    public function tracking()
    {
        return view('mahasiswa.tracking');
    }

    public function searchTracking(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $reportId = $this->parseReportId($request->code);
        
        if (!$reportId) {
            return response()->json(['success' => false, 'message' => 'Format tidak valid'], 400);
        }

        $report = Report::with(['facility', 'statusHistory.user'])
            ->where('user_id', Auth::id())
            ->where('id', $reportId)
            ->first();

        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan'], 404);
        }

        $timeline = [];
        foreach ($report->statusHistory as $history) {
            $timeline[] = [
                'status' => $history->status == 'completed' ? 'completed' : ($history->status == 'in_progress' ? 'active' : 'pending'),
                'title' => $this->getStatusLabel($history->status),
                'date' => $history->created_at?->format('d M Y, H:i') ?? '-',
                'description' => $history->description,
                'user' => $history->user->name ?? 'Sistem'
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $report->id,
                'code' => '#' . str_pad($report->id, 5, '0', STR_PAD_LEFT),
                'title' => $report->title,
                'description' => $report->description,
                'location' => $report->location_detail,
                'facility' => $report->facility->name ?? '-',
                'status' => $report->status,
                'status_label' => $this->getStatusLabel($report->status),
                'status_badge' => $report->status_badge,
                'created_at' => $report->created_at->format('d M Y, H:i'),
                'sla_deadline' => $report->sla_deadline?->format('d M Y, H:i') ?? '-',
                'timeline' => $timeline
            ]
        ]);
    }

    private function parseReportId($input)
{
    $cleaned = ltrim(trim($input), '#');
    if (ctype_digit($cleaned) && strlen($cleaned) > 0) {
        return (int) $cleaned;  // "00001"→1, "00000"→0, "1"→1, semua valid
    }
    return null;
}

    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu', 'verified' => 'Diverifikasi',
            'in_progress' => 'Diproses', 'completed' => 'Selesai', 'rejected' => 'Ditolak'
        ];
        return $labels[$status] ?? $status;
    }
}