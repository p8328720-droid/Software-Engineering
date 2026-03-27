<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name', 'category', 'location', 'description', 
        'image', 'status', 'sla_hours', 'is_active'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}