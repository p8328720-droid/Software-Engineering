<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Mengizinkan mass-assignment untuk nama kategori dan SLA
    protected $fillable = [
        'name',
        'sla_hours',
    ];

    /**
     * Relasi: Satu kategori bisa menampung banyak laporan kerusakan.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}