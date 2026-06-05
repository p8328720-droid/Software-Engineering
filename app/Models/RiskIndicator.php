<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskIndicator extends Model
{
protected $fillable = [
    'facility_id', 'total_reports', 'critical_reports',
    'avg_resolution_time', 'risk_score', 'risk_level',
    'recommendations', 'period_start', 'period_end'
];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}