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

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-secondary">Menunggu</span>',
            'verified' => '<span class="badge bg-info">Diverifikasi</span>',
            'in_progress' => '<span class="badge bg-warning">Diproses</span>',
            'completed' => '<span class="badge bg-success">Selesai</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
        ];
        return $badges[$this->status] ?? '';
    }

    public function getUrgencyBadgeAttribute()
    {
        $badges = [
            'low' => '<span class="badge bg-success">Rendah</span>',
            'medium' => '<span class="badge bg-warning">Sedang</span>',
            'high' => '<span class="badge bg-danger">Tinggi</span>',
        ];
        return $badges[$this->urgency] ?? '';
    }
}