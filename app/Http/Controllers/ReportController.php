<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Report;
use App\Models\Room;
use App\Models\Sla; // Menggunakan Sla (Case sensitive sesuai standard Laravel)
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Menampilkan daftar laporan berdasarkan role.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Report::with(['room', 'sla']);

        if ($user->role === 'pelapor') {
            $reports = $query->where('reporter_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return view('pelapor.reports.index', compact('reports'));
        }

        // Untuk Admin & Teknisi bisa melihat semua laporan
        $reports = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reports.index', compact('reports'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'pelapor') {
            abort(403);
        }

        $rooms = Room::all();
        
        // Ambil daftar kategori unik dari tabel SLA untuk dropdown di form
        $categories = Sla::where('is_active', true)
                        ->distinct()
                        ->pluck('facility_category');

        return view('pelapor.reports.create', compact('rooms', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'facility_category' => 'required|string', 
            'urgency' => 'required|in:low,medium,high',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // 1. Cari rule SLA spesifik (Matrix Kategori + Urgensi)
        $sla = Sla::where('facility_category', $request->facility_category)
                  ->where('urgency', $request->urgency)
                  ->firstOrFail();

        // 2. Hitung Deadline via Service
        $deadline = $this->reportService->calculateSLADeadline($request->facility_category, $request->urgency);

        // 3. Mapping data untuk mass assignment
        $data = $request->except(['image', 'facility_category']);
        $data['reporter_id'] = Auth::id();
        $data['status'] = 'pending';
        $data['sla_id'] = $sla->id;
        $data['sla_deadline'] = $deadline; // Tetap pakai sla_deadline sesuai request

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('reports', 'public');
        }

        $report = Report::create($data);

        // 4. Catat riwayat awal
        AuditLog::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status_changed_to' => 'pending',
            'notes' => "Laporan dibuat. Target resolusi: {$sla->resolution_hours} jam.",
        ]);

        return redirect()->route('pelapor.reports.show', $report->id)
            ->with('success', 'Laporan berhasil dikirim dan masuk antrean.');
    }

    public function show($id)
    {
        $report = Report::with(['reporter', 'room', 'sla', 'auditLogs.user', 'assignment.technician'])
            ->findOrFail($id);

        // Security check untuk pelapor
        if (Auth::user()->role === 'pelapor' && $report->reporter_id !== Auth::id()) {
            abort(403);
        }

        $technicians = User::where('role', 'teknisi')->get();

        return view('reports.show', compact('report', 'technicians'));
    }

    /**
     * Menugaskan teknisi ke laporan (Admin Only).
     */
    public function assignTechnician(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'assignee_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Update atau buat penugasan baru
        $report->assignment()->updateOrCreate(
            ['report_id' => $report->id],
            [
                'technician_id' => $request->assignee_id,
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'notes' => $request->notes,
            ]
        );

        // Ubah status laporan menjadi in_progress
        // $report->update(['status' => 'in_progress']);

        AuditLog::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status_changed_to' => 'in_progress',
            'notes' => $request->notes ?? 'Admin telah memverifikasi dan menugaskan teknisi.',
        ]);

        return redirect()->back()->with('success', 'Teknisi berhasil ditugaskan.');
    }

    /**
     * Menyimpan komentar/catatan tambahan ke Audit Log.
     */
    public function storeComment(Request $request, Report $report)
    {
        $request->validate(['comment' => 'required|string|max:1000']);

        AuditLog::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status_changed_to' => $report->status, // Status tidak berubah, hanya nambah catatan
            'notes' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Catatan berhasil ditambahkan.');
    }
}