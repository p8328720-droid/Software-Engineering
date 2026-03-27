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
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 4,
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );
        
        // Supervisor
        User::updateOrCreate(
            ['email' => 'supervisor@example.com'],
            [
                'name' => 'Supervisor Utama',
                'password' => Hash::make('password'),
                'role' => 3,
                'phone' => '081234567891',
                'is_active' => true,
            ]
        );
        
        // Additional Supervisors
        $supervisors = [
            ['name' => 'Supervisor Teknik', 'email' => 'supervisor.teknik@example.com', 'phone' => '081234567892'],
            ['name' => 'Supervisor Fasilitas', 'email' => 'supervisor.fasilitas@example.com', 'phone' => '081234567893'],
            ['name' => 'Supervisor Laboratorium', 'email' => 'supervisor.lab@example.com', 'phone' => '081234567894'],
        ];
        
        foreach ($supervisors as $sup) {
            User::updateOrCreate(
                ['email' => $sup['email']],
                [
                    'name' => $sup['name'],
                    'password' => Hash::make('password'),
                    'role' => 3,
                    'phone' => $sup['phone'],
                    'is_active' => true,
                ]
            );
        }
        
        // Teknisi
        User::updateOrCreate(
            ['email' => 'teknisi@example.com'],
            [
                'name' => 'Teknisi Utama',
                'password' => Hash::make('password'),
                'role' => 2,
                'phone' => '081234567895',
                'is_active' => true,
            ]
        );
        
        // Additional Technicians
        $technicians = [
            ['name' => 'Teknisi Listrik', 'email' => 'teknisi.listrik@example.com', 'phone' => '081234567896'],
            ['name' => 'Teknisi AC', 'email' => 'teknisi.ac@example.com', 'phone' => '081234567897'],
            ['name' => 'Teknisi Komputer', 'email' => 'teknisi.komputer@example.com', 'phone' => '081234567898'],
            ['name' => 'Teknisi Plumbing', 'email' => 'teknisi.plumbing@example.com', 'phone' => '081234567899'],
            ['name' => 'Teknisi Umum', 'email' => 'teknisi.umum@example.com', 'phone' => '081234567800'],
        ];
        
        foreach ($technicians as $tech) {
            User::updateOrCreate(
                ['email' => $tech['email']],
                [
                    'name' => $tech['name'],
                    'password' => Hash::make('password'),
                    'role' => 2,
                    'phone' => $tech['phone'],
                    'is_active' => true,
                ]
            );
        }
        
        // Mahasiswa
        User::updateOrCreate(
            ['email' => 'mahasiswa@example.com'],
            [
                'name' => 'Mahasiswa Contoh',
                'password' => Hash::make('password'),
                'role' => 1,
                'phone' => '081234567801',
                'nim' => '20240010001',
                'is_active' => true,
            ]
        );
        
        // Additional Students
        $students = [
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@example.com', 'nim' => '20240010002', 'phone' => '081234567802'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@example.com', 'nim' => '20240010003', 'phone' => '081234567803'],
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@example.com', 'nim' => '20240010004', 'phone' => '081234567804'],
            ['name' => 'Dewi Sartika', 'email' => 'dewi.sartika@example.com', 'nim' => '20240010005', 'phone' => '081234567805'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko.prasetyo@example.com', 'nim' => '20240010006', 'phone' => '081234567806'],
            ['name' => 'Fitri Handayani', 'email' => 'fitri.handayani@example.com', 'nim' => '20240010007', 'phone' => '081234567807'],
            ['name' => 'Gilang Ramadhan', 'email' => 'gilang.ramadhan@example.com', 'nim' => '20240010008', 'phone' => '081234567808'],
            ['name' => 'Heni Kristina', 'email' => 'heni.kristina@example.com', 'nim' => '20240010009', 'phone' => '081234567809'],
            ['name' => 'Indra Wijaya', 'email' => 'indra.wijaya@example.com', 'nim' => '20240010010', 'phone' => '081234567810'],
            ['name' => 'Joko Susilo', 'email' => 'joko.susilo@example.com', 'nim' => '20240010011', 'phone' => '081234567811'],
        ];
        
        foreach ($students as $student) {
            User::updateOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'password' => Hash::make('password'),
                    'role' => 1,
                    'phone' => $student['phone'],
                    'nim' => $student['nim'],
                    'is_active' => true,
                ]
            );
        }
    }
}