<?php

namespace App\Services;

use App\Models\Report;
use App\Models\Facility;
use Carbon\Carbon;

class ReportService
{
    /**
     * Calculate SLA deadline based on facility and urgency.
     * 
     * @param Facility $facility
     * @param string $urgency
     * @return Carbon
     */
    public function calculateSLADeadline(Facility $facility, string $urgency): Carbon
    {
        $slaHours = $facility->sla_hours;
        
        if ($urgency == 'high') {
            $slaHours = $slaHours * 0.5;
        } elseif ($urgency == 'medium') {
            $slaHours = $slaHours * 0.75;
        }
        
        return now()->addHours($slaHours);
    }

    /**
     * Get statistics for a user or global.
     * 
     * @param int|null $userId
     * @return array
     */
    public function getStats(?int $userId = null): array
    {
        $query = Report::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];
    }
}
