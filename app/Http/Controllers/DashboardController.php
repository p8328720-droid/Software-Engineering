<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\User;
use App\Models\Facility;
use App\Models\TechnicianAssignment;
use App\Services\ReportService;

class DashboardController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isTeknisi()) {
            return $this->teknisiDashboard();
        } elseif ($user->isSupervisor()) {
            return $this->supervisorDashboard();
        } else {
            return $this->mahasiswaDashboard();
        }
    }

    private function adminDashboard()
    {
        $reportStats = $this->reportService->getStats();
        
        $stats = [
            'total_reports' => $reportStats['total'],
            'pending_reports' => $reportStats['pending'],
            'in_progress_reports' => $reportStats['in_progress'],
            'completed_reports' => $reportStats['completed'],
            'total_users' => User::count(),
            'total_technicians' => User::where('role', 'teknisi')->count(),
            'total_students' => User::where('role', 'mahasiswa')->count(),
            'total_facilities' => Facility::count(),
            'sla_violations' => Report::where('status', '!=', 'completed')
                ->where('sla_deadline', '<', now())
                ->count(),
        ];
        
        $recent_reports = Report::with(['user', 'facility'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $reports_by_status = Report::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
            
        $reports_by_facility = Facility::withCount('reports')
            ->orderBy('reports_count', 'desc')
            ->limit(5)
            ->get();
            
        $recent_users = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('admin.dashboard', compact('stats', 'recent_reports', 'reports_by_status', 'reports_by_facility', 'recent_users'));
    }

    private function mahasiswaDashboard()
    {
        $reportStats = $this->reportService->getStats(Auth::id());
        
        $stats = [
            'total_reports' => $reportStats['total'],
            'pending_reports' => $reportStats['pending'],
            'completed_reports' => $reportStats['completed'],
            'in_progress_reports' => $reportStats['in_progress'],
        ];
        
        $recent_reports = Report::with('facility')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('mahasiswa.dashboard', compact('stats', 'recent_reports'));
    }

    private function supervisorDashboard()
    {
        $stats = [
            'total_reports' => Report::count(),
            'sla_violations' => Report::where('status', '!=', 'completed')
                ->where('sla_deadline', '<', now())
                ->count(),
            'active_technicians' => User::where('role', 'teknisi')->count(),
        ];
        
        $reports = Report::with(['user', 'facility', 'technicianAssignments.technician'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('supervisor.dashboard', compact('stats', 'reports'));
    }

    private function teknisiDashboard()
    {
        $stats = [
            'active_tasks' => TechnicianAssignment::where('technician_id', Auth::id())
                ->whereNull('completed_at')
                ->count(),
            'completed_tasks' => TechnicianAssignment::where('technician_id', Auth::id())
                ->whereNotNull('completed_at')
                ->count(),
        ];
        
        $active_tasks = TechnicianAssignment::with('report')
            ->where('technician_id', Auth::id())
            ->whereNull('completed_at')
            ->get();
            
        return view('teknisi.dashboard', compact('stats', 'active_tasks'));
    }
}
