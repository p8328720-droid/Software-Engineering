<?php

namespace App\Services;

use App\Models\Report;
use App\Models\Sla;
use Carbon\Carbon;

class ReportService
{
    /**
     * Hitung deadline SLA berdasarkan kategori fasilitas dan tingkat urgensi.
     * Menggunakan tabel 'sla' sebagai matriks referensi.
     */
    public function calculateSLADeadline(string $categoryName, string $urgency): Carbon
    {
        // Cari aturan SLA yang aktif di database
        $sla = Sla::where('facility_category', $categoryName)
                  ->where('urgency', $urgency)
                  ->where('is_active', true)
                  ->first();

        // Fallback: Jika tidak ketemu di tabel SLA, pakai default 72 jam (3 hari)
        $hours = $sla ? $sla->resolution_hours : 72;
        
        return now()->addHours($hours);
    }

    /**
     * Ambil statistik laporan (global atau per user).
     * Menggunakan kolom 'reporter_id' sesuai skema MVP.
     */
    public function getStats(?int $userId = null): array
    {
        $query = Report::query();
        
        if ($userId) {
            $query->where('reporter_id', $userId);
        }
        
        // Menggunakan lowercase status sesuai kesepakatan enum
        return [
            'total'       => (clone $query)->count(),
            'pending'     => (clone $query)->where('status', 'pending')->count(),
            'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
            'completed'   => (clone $query)->where('status', 'completed')->count(),
            'rejected'    => (clone $query)->where('status', 'rejected')->count(),
        ];
    }
}