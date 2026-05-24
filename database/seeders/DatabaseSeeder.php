<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Facility;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Teknisi
        User::create([
            'name' => 'Teknisi Budi',
            'email' => 'teknisi@example.com',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
        ]);

        // Mahasiswa
        User::create([
            'name' => 'Mahasiswa Demo',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'student_id' => '12345678',
            'phone' => '08123456789',
            'faculty' => 'Fakultas Ilmu Komputer',
            'major' => 'Teknik Informatika',
        ]);

        // Facilities
        $facilities = [
            ['name' => 'Lab Komputer', 'category' => 'Lab', 'location' => 'Gedung Utama', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
            ['name' => 'Lab Jaringan', 'category' => 'Lab', 'location' => 'Gedung Utama', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
            ['name' => 'Lab Multimedia', 'category' => 'Lab', 'location' => 'Gedung Utama', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
            ['name' => 'Komputer PC Lab 1', 'category' => 'Komputer', 'location' => 'Lab Komputer', 'status' => 'baik', 'sla_hours' => 72, 'is_active' => true],
            ['name' => 'Komputer PC Lab 2', 'category' => 'Komputer', 'location' => 'Lab Komputer', 'status' => 'baik', 'sla_hours' => 72, 'is_active' => true],
            ['name' => 'Komputer Dosen', 'category' => 'Komputer', 'location' => 'Ruang Dosen', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}