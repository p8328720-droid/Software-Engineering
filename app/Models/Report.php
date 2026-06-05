<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Report extends Model
{
    protected $fillable = [
        // ✅ FIX: 'admin_note' dihapus — kolom sudah tidak ada di DB
        // (migration: 2026_06_05_042121_remove_admin_note_from_reports_table)
        'user_id', 'facility_id', 'title', 'description', 'location_detail',
        'urgency', 'status', 'image_path', 'sla_deadline', 'resolved_at',
        'rating', 'rating_comment',
    ];
 
    protected $casts = [
        'sla_deadline' => 'datetime',
        'resolved_at'  => 'datetime',
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
 
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
 
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending'     => '<span class="badge bg-secondary">Menunggu</span>',
            'verified'    => '<span class="badge bg-info">Diverifikasi</span>',
            'in_progress' => '<span class="badge bg-warning">Diproses</span>',
            'completed'   => '<span class="badge bg-success">Selesai</span>',
            'rejected'    => '<span class="badge bg-danger">Ditolak</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
 
    public function getUrgencyBadgeAttribute()
    {
        $badges = [
            'low'    => '<span class="badge bg-success">Rendah</span>',
            'medium' => '<span class="badge bg-warning">Sedang</span>',
            'high'   => '<span class="badge bg-danger">Tinggi</span>',
        ];
        return $badges[$this->urgency] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
 
    public function canRate($userId)
    {
        return $this->user_id == $userId
            && $this->status === 'completed'
            && is_null($this->rating);
    }
 
    public function hasRated()
    {
        return ! is_null($this->rating);
    }
}