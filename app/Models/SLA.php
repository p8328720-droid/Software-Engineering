<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SLA extends Model
{
    protected $table = 'sla';
    
    protected $fillable = [
        'facility_category', 'urgency', 'response_hours', 'resolution_hours', 'is_active'
    ];
}