<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Facility;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'in_progress_reports' => Report::where('status', 'in_progress')->count(),
            'completed_reports' => Report::where('status', 'completed')->count(),
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
}