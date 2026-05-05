<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    // Sesuaikan dengan kolom di migration yang baru
    protected $fillable = [
        'report_id',
        'user_id',
        'status_changed_to',
        'notes',
    ];

    /**
     * Relasi kembali ke Laporan yang sedang diaudit
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Relasi ke User (Siapa yang mengubah status atau ngasih komentar)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
