<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Facility;
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

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isMahasiswa()) {
            $reports = Report::with('facility')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return view('mahasiswa.reports.index', compact('reports'));
        }
        
        // Add logic for other roles if they have an index view
        abort(403);
    }

    public function create()
    {
        if (!Auth::user()->isMahasiswa()) {
            abort(403);
        }
        
        $facilities = Facility::where('is_active', true)->get();
        return view('mahasiswa.reports.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isMahasiswa()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'facility_id' => 'required|exists:facilities,id',
            'location_detail' => 'required|string|max:255',
            'urgency' => 'required|in:low,medium,high',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $facility = Facility::findOrFail($request->facility_id);
        
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';
        $data['sla_deadline'] = $this->reportService->calculateSLADeadline($facility, $request->urgency);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reports', 'public');
            $data['image_path'] = $path;
        }
        
        $report = Report::create($data);
        
        return redirect()->route('mahasiswa.reports.show', $report)
            ->with('success', 'Laporan berhasil dikirim');
    }

    public function show($id)
    {
        $user = Auth::user();
        $report = Report::with(['user', 'facility', 'comments.user'])
            ->findOrFail($id);
            
        if ($user->isMahasiswa() && $report->user_id !== $user->id) {
            abort(403);
        }
        
        $view = 'mahasiswa.reports.show';
        if ($user->isTeknisi()) {
            $view = 'teknisi.reports.show';
        } elseif ($user->isSupervisor() || $user->isAdmin()) {
            // Check if there is a specific view for supervisor/admin
            // For now, use mahasiswa view or create a generic one
        }
            
        return view($view, compact('report'));
    }
}
