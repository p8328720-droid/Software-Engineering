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
        
        if ($user->isPelapor()) {
            $reports = Report::with('facility')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return view('pelapor.reports.index', compact('reports'));
        }
        
        // Add logic for other roles if they have an index view
        abort(403);
    }

    public function create()
    {
        if (!Auth::user()->isPelapor()) {
            abort(403);
        }
        
        $facilities = Facility::where('is_active', true)->get();
        return view('pelapor.reports.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isPelapor()) {
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
        
        return redirect()->route('pelapor.reports.show', $report)
            ->with('success', 'Laporan berhasil dikirim');
    }

    public function show($id)
    {
        $user = Auth::user();
        // Eager load statusHistory for the progress tracker and comments/user for comments section
        $report = Report::with(['user', 'facility', 'comments.user', 'statusHistory'])
            ->findOrFail($id);
            
        // Ensure pelapor can only view their own reports
        if ($user->isPelapor() && $report->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        // Fetch all technicians for the modals
        $technicians = \App\Models\User::where('role', 'teknisi')->get();
            
        // Render the unified reports view
        return view('reports.show', compact('report', 'technicians'));
    }
    
    /**
     * Verify a report and assign a technician.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyReport(Request $request, Report $report)
    {
        // Ensure the user is an admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'assignee_id' => 'required|exists:users,id',
            'verification_notes' => 'nullable|string|max:1000',
        ]);

        // Update report status
        $report->status = 'in_progress';
        $report->save(); // Save the status update

        // Create a new technician assignment record
        $report->technicianAssignments()->create([
            'technician_id' => $request->assignee_id,
            'assigned_by' => Auth::id(),
            'assigned_at' => now(),
        ]);

        // Add a status history entry
        $report->statusHistory()->create([
            'status' => 'in_progress',
            'description' => $request->verification_notes ?? 'Laporan diverifikasi dan ditetapkan kepada teknisi.',
            'user_id' => Auth::id(), // The admin performing the verification
        ]);

        return redirect()->route('admin.reports.index') // Assuming an admin reports index exists, or redirect back
            ->with('success', 'Laporan berhasil diverifikasi dan ditetapkan.');
    }

    /**
     * Store a new comment for a report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeComment(Request $request, Report $report)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        // Create the comment, associating it with the report and the authenticated user
        $report->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Forward a report to another technician.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forwardReport(Request $request, Report $report)
    {
        // Ensure the user is an admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'assignee_id' => 'required|exists:users,id',
            'forwarding_notes' => 'nullable|string|max:1000',
        ]);

        // Create a new technician assignment record
        $report->technicianAssignments()->create([
            'technician_id' => $request->assignee_id,
            'assigned_by' => Auth::id(),
            'assigned_at' => now(),
        ]);

        // Add a status history entry
        $report->statusHistory()->create([
            'status' => $report->status, // Use the current status or a new one if defined
            'description' => $request->forwarding_notes ?? 'Laporan diteruskan kepada teknisi lain.',
            'user_id' => Auth::id(), // The admin performing the forwarding
        ]);

        return redirect()->route('admin.reports.index') // Assuming an admin reports index exists, or redirect back
            ->with('success', 'Laporan berhasil diteruskan.');
    }

    /**
     * Display a listing of reports for admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminIndex()
    {
        // This method will list all reports for admin, potentially with filtering
        // For now, let's redirect to dashboard as a placeholder
        return redirect()->route('admin.dashboard')->with('info', 'Admin reports index is not yet implemented.');
    }
}