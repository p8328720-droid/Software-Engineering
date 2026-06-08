<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function mahasiswaDashboard()
    {
        $userId = Auth::id();

        $stats = [
            'total_reports' => Report::where('user_id', $userId)->count(),
            'rejected_reports' => Report::where('user_id', $userId)->where('status', 'rejected')->count(),
            'in_progress_reports' => Report::where('user_id', $userId)->where('status', 'in_progress')->count(),
            'completed_reports' => Report::where('user_id', $userId)->where('status', 'completed')->count(),
        ];

        $recentReports = Report::with('facility')
            ->where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        return view('mahasiswa.dashboard', compact('stats', 'recentReports'));
    }

    public function teknisiDashboard()
    {
        // Statistik
        $stats = [
        'active_tasks' => Report::whereIn('status', ['pending', 'verified', 'in_progress'])->count(), // ✅
        'completed_tasks' => Report::where('status', 'completed')
            ->whereDate('resolved_at', today())
            ->count(),
        'total_reports' => Report::count(),
    ];

    
    // Tugas aktif
    $active_tasks = Report::with(['user', 'facility'])
        ->whereIn('status', ['pending', 'verified', 'in_progress']) // ✅
        ->orderBy('created_at', 'desc')
        ->get();

        // Tugas selesai dengan rating (untuk rata-rata rating teknisi)
        $completedTasks = Report::with(['user', 'facility'])
            ->where('status', 'completed')
            ->orderBy('resolved_at', 'desc')
            ->limit(10)
            ->get();

        // Hitung rata-rata rating dari laporan yang sudah dinilai
        $avgRating = Report::where('status', 'completed')
            ->whereNotNull('rating')
            ->avg('rating') ?? 0;

        // Statistik rating tambahan
        $ratingStats = [
            'total_rated' => Report::where('status', 'completed')->whereNotNull('rating')->count(),
            'total_completed' => Report::where('status', 'completed')->count(),
            'rating_distribution' => [
                1 => Report::where('rating', 1)->count(),
                2 => Report::where('rating', 2)->count(),
                3 => Report::where('rating', 3)->count(),
                4 => Report::where('rating', 4)->count(),
                5 => Report::where('rating', 5)->count(),
            ],
        ];

        return view('teknisi.dashboard', compact('stats', 'active_tasks', 'completedTasks', 'avgRating', 'ratingStats'));
    }

    public function adminDashboard()
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
        ];

        $recent_reports = Report::with(['user', 'facility'])
            ->latest()
            ->limit(10)
            ->get();

        $recent_users = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_reports', 'recent_users'));
    }
}
