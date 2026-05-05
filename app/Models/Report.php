<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'reporter_id',
        'room_id',
        'sla_id',
        'description',
        'image_path',
        'urgency',
        'status',
        'sla_deadline',
    ];

    protected $casts = [
        'sla_deadline' => 'datetime',
    ];

    /**
     * Relasi ke Pelapor (Mahasiswa/User)
     * Menggantikan relasi user() lama agar lebih deskriptif
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Relasi ke Ruangan (Lokasi Kerusakan)
     * Menggantikan relasi facility() punya temen lu
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi ke Kategori Kerusakan (Listrik, AC, dll)
     * Ini yang ngasih tau kita batas SLA-nya berapa lama
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Penugasan Teknisi
     * Pakai hasOne karena satu laporan biasanya di-handle satu penugasan aktif
     */
    public function assignment()
    {
        return $this->hasOne(TechnicianAssignment::class);
    }

    /**
     * Relasi ke Riwayat Perubahan Status (Audit Log)
     * Menggantikan statusHistory() dan comments() yang kepanjangan
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function sla()
    {
        return $this->belongsTo(Sla::class, 'sla_id');
    }
}
