<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    // Mengizinkan mass-assignment untuk nama ruangan
    protected $fillable = [
        'name',
    ];

    /**
     * Relasi: Satu ruangan bisa memiliki banyak laporan kerusakan.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}