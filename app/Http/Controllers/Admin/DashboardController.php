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
        // Statistik
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
            'sla_violations' => Report::where('status', '!=', 'completed')
                ->where('sla_deadline', '<', now())
                ->count(),
        ];

        // Data urgensi
        $urgencyData = [
            'low' => Report::where('urgency', 'low')->count(),
            'medium' => Report::where('urgency', 'medium')->count(),
            'high' => Report::where('urgency', 'high')->count(),
        ];

        // Top 5 fasilitas
        $topFacilities = Facility::withCount('reports')
            ->orderBy('reports_count', 'desc')
            ->limit(5)
            ->get();

        // Distribusi rating
        $ratingDistribution = [
            Report::where('rating', 1)->count(),
            Report::where('rating', 2)->count(),
            Report::where('rating', 3)->count(),
            Report::where('rating', 4)->count(),
            Report::where('rating', 5)->count(),
        ];

        // Data tren bulanan
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyLabels[] = $month->format('M Y');
            $monthlyData[] = Report::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // Laporan terbaru
        $recent_reports = Report::with(['user', 'facility'])->latest()->limit(10)->get();
        $recent_users = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'urgencyData',
            'topFacilities',
            'ratingDistribution',
            'monthlyLabels',
            'monthlyData',
            'recent_reports',
            'recent_users'
        ));
    }
}