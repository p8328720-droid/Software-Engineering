<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'user_id', 'facility_id', 'title', 'description', 
        'location_detail', 'urgency', 'status', 'image_path',
        'sla_deadline', 'resolved_at', 'admin_note', 'escalated_at'
    ];

    protected $casts = [
        'sla_deadline' => 'datetime',
        'resolved_at' => 'datetime',
        'escalated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(ReportStatus::class);
    }

    public function technicianAssignments()
    {
        return $this->hasMany(TechnicianAssignment::class);
    }
}