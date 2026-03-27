<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Facility;
use App\Models\SLA;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@siruka.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'student_id' => null,
            'phone' => '081234567890',
            'faculty' => 'Fakultas Ilmu Komputer',
            'major' => 'Sistem Informasi',
        ]);

        // Create Technician User
        User::create([
            'name' => 'Teknisi Budi',
            'email' => 'teknisi@siruka.ac.id',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
            'student_id' => null,
            'phone' => '081234567891',
            'faculty' => null,
            'major' => null,
        ]);

        // Create Supervisor User
        User::create([
            'name' => 'Supervisor Joko',
            'email' => 'supervisor@siruka.ac.id',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'student_id' => null,
            'phone' => '081234567892',
            'faculty' => null,
            'major' => null,
        ]);

        // Create Mahasiswa User
        User::create([
            'name' => 'Mahasiswa Demo',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'student_id' => '123456789',
            'phone' => '081234567893',
            'faculty' => 'Fakultas Ilmu Komputer',
            'major' => 'Teknik Informatika',
        ]);

        // Create Facilities
        $facilities = [
            ['name' => 'AC Ruang 201', 'category' => 'AC', 'location' => 'Gedung A Lt 2', 'sla_hours' => 48],
            ['name' => 'Proyektor Epson', 'category' => 'Proyektor', 'location' => 'Gedung B Lt 1', 'sla_hours' => 24],
            ['name' => 'Komputer Lab 1', 'category' => 'Komputer', 'location' => 'Lab Komputer', 'sla_hours' => 72],
            ['name' => 'Lampu Gedung C', 'category' => 'Lampu', 'location' => 'Gedung C', 'sla_hours' => 24],
            ['name' => 'Kursi Kuliah', 'category' => 'Furniture', 'location' => 'Ruang 101', 'sla_hours' => 120],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }

        // Create SLA Rules
        $slaRules = [
            ['facility_category' => 'AC', 'urgency' => 'low', 'response_hours' => 24, 'resolution_hours' => 72],
            ['facility_category' => 'AC', 'urgency' => 'medium', 'response_hours' => 12, 'resolution_hours' => 48],
            ['facility_category' => 'AC', 'urgency' => 'high', 'response_hours' => 4, 'resolution_hours' => 24],
            ['facility_category' => 'Komputer', 'urgency' => 'low', 'response_hours' => 48, 'resolution_hours' => 120],
            ['facility_category' => 'Komputer', 'urgency' => 'medium', 'response_hours' => 24, 'resolution_hours' => 72],
            ['facility_category' => 'Komputer', 'urgency' => 'high', 'response_hours' => 8, 'resolution_hours' => 48],
            ['facility_category' => 'Proyektor', 'urgency' => 'low', 'response_hours' => 24, 'resolution_hours' => 48],
            ['facility_category' => 'Proyektor', 'urgency' => 'medium', 'response_hours' => 12, 'resolution_hours' => 24],
            ['facility_category' => 'Proyektor', 'urgency' => 'high', 'response_hours' => 4, 'resolution_hours' => 12],
            ['facility_category' => 'Lampu', 'urgency' => 'low', 'response_hours' => 48, 'resolution_hours' => 72],
            ['facility_category' => 'Lampu', 'urgency' => 'medium', 'response_hours' => 24, 'resolution_hours' => 48],
            ['facility_category' => 'Lampu', 'urgency' => 'high', 'response_hours' => 8, 'resolution_hours' => 24],
            ['facility_category' => 'Furniture', 'urgency' => 'low', 'response_hours' => 72, 'resolution_hours' => 168],
            ['facility_category' => 'Furniture', 'urgency' => 'medium', 'response_hours' => 48, 'resolution_hours' => 120],
            ['facility_category' => 'Furniture', 'urgency' => 'high', 'response_hours' => 24, 'resolution_hours' => 72],
        ];

        foreach ($slaRules as $rule) {
            SLA::create($rule);
        }
    }
}