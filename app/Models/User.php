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
        'faculty', 'major', 'role',
    ];

    protected $hidden = ['password', 'remember_token'];

    // ========== RELATIONS ==========
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relasi ke notifikasi - HANYA SATU KALI DEKLARASI
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // ========== NOTIFICATION METHODS ==========
    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }

    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    public function markAllNotificationsAsRead()
    {
        $this->notifications()->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    // ========== HELPER METHODS ==========
    public function getAvatarUrlAttribute()
    {
        return 'https://ui-avatars.com/api/?background=FF6B35&color=fff&size=100&name='.urlencode($this->name);
    }
}
