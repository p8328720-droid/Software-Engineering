<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicianAssignment extends Model
{
    protected $fillable = [
        'report_id', 'technician_id', 'assigned_by', 
        'assigned_at', 'started_at', 'completed_at', 'notes'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}