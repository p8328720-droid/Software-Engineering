<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@siruka.ac.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'faculty' => 'Fakultas Ilmu Komputer',
                'major' => 'Sistem Informasi',
            ]
        );
        
        // Supervisor
        User::updateOrCreate(
            ['email' => 'supervisor@siruka.ac.id'],
            [
                'name' => 'Supervisor Utama',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
                'phone' => '081234567891',
            ]
        );
        
        // Teknisi
        User::updateOrCreate(
            ['email' => 'teknisi@siruka.ac.id'],
            [
                'name' => 'Teknisi Utama',
                'password' => Hash::make('password'),
                'role' => 'teknisi',
                'phone' => '081234567895',
            ]
        );
        
        // Mahasiswa
        User::updateOrCreate(
            ['email' => 'mahasiswa@example.com'],
            [
                'name' => 'Mahasiswa Contoh',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'phone' => '081234567801',
                'student_id' => '123456789',
                'faculty' => 'Fakultas Ilmu Komputer',
                'major' => 'Teknik Informatika',
            ]
        );
        
        // Additional Students
        $students = [
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@example.com', 'student_id' => '20240010002', 'phone' => '081234567802'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@example.com', 'student_id' => '20240010003', 'phone' => '081234567803'],
        ];
        
        foreach ($students as $student) {
            User::updateOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'password' => Hash::make('password'),
                    'role' => 'mahasiswa',
                    'phone' => $student['phone'],
                    'student_id' => $student['student_id'],
                    'faculty' => 'Fakultas Teknik',
                    'major' => 'Teknik Elektro',
                ]
            );
        }
    }
}
