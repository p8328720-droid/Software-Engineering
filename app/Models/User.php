<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'student_id', 'phone', 
        'faculty', 'major', 'role', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function technicianAssignments()
    {
        return $this->hasMany(TechnicianAssignment::class, 'technician_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    public function isSupervisor()
    {
        return $this->role === 'supervisor';
    }

    public function getAvatarUrlAttribute()
    {
        return "https://ui-avatars.com/api/?background=FF6B35&color=fff&size=100&name=" . urlencode($this->name);
    }
}