<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Facility;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung SLA violations dengan aman
        $slaViolations = 0;
        try {
            $slaViolations = Report::where('status', '!=', 'completed')
                ->where('sla_deadline', '<', now())
                ->count();
        } catch (\Exception $e) {
            $slaViolations = 0;
        }

        // ========== STATISTIK UTAMA ==========
        $stats = [
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'in_progress_reports' => Report::where('status', 'in_progress')->count(),
            'completed_reports' => Report::where('status', 'completed')->count(),
            'rejected_reports' => Report::where('status', 'rejected')->count(),
            'total_users' => User::count(),
            'total_technicians' => User::where('role', 'teknisi')->count(),
            'total_students' => User::where('role', 'mahasiswa')->count(),
            'total_facilities' => Facility::count(),
            'sla_violations' => $slaViolations,
        ];

        // ========== CHART 1: STATUS LAPORAN (DONUT) ==========
        $chartStatus = [
            'labels' => ['Pending', 'Diproses', 'Selesai', 'Ditolak'],
            'data' => [
                $stats['pending_reports'],
                $stats['in_progress_reports'],
                $stats['completed_reports'],
                $stats['rejected_reports']
            ],
            'colors' => ['#6c757d', '#ffc107', '#28a745', '#dc3545']
        ];

        // ========== CHART 2: TOP 5 FASILITAS (BAR) ==========
        $topFacilities = Facility::withCount('reports')
            ->orderBy('reports_count', 'desc')
            ->limit(5)
            ->get();
        $chartFacility = [
            'labels' => $topFacilities->pluck('name'),
            'data' => $topFacilities->pluck('reports_count')
        ];

        // ========== CHART 3: KOMPOSISI PENGGUNA (BAR) ==========
        $chartUsers = [
            'labels' => ['Mahasiswa', 'Teknisi', 'Admin'],
            'data' => [
                $stats['total_students'],
                $stats['total_technicians'],
                User::where('role', 'admin')->count()
            ],
            'colors' => ['#28a745', '#17a2b8', '#dc3545']
        ];

        // ========== DATA LAINNYA ==========
        $recent_reports = Report::with(['user', 'facility'])->latest()->limit(10)->get();
        $recent_users = User::latest()->limit(5)->get();
        
        $avgResolutionTime = Report::where('status', 'completed')
            ->whereNotNull('resolved_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours'))
            ->value('avg_hours') ?? 0;
            
        $avgRating = Report::whereNotNull('rating')->avg('rating') ?? 0;

        return view('admin.dashboard', compact(
            'stats',
            'chartStatus',
            'chartFacility',
            'chartUsers',
            'recent_reports',
            'recent_users',
            'avgResolutionTime',
            'avgRating'
        ));
    }
}