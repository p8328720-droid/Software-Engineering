<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            // Laboratorium
            ['name' => 'Lab Komputer 1', 'category' => 'Laboratorium', 'location' => 'Gedung A Lantai 2', 'description' => 'Laboratorium komputer dengan 40 unit PC'],
            ['name' => 'Lab Komputer 2', 'category' => 'Laboratorium', 'location' => 'Gedung A Lantai 3', 'description' => 'Laboratorium komputer dengan 30 unit PC'],
            ['name' => 'Lab Jaringan', 'category' => 'Laboratorium', 'location' => 'Gedung A Lantai 2', 'description' => 'Laboratorium jaringan komputer'],
            ['name' => 'Lab Kimia', 'category' => 'Laboratorium', 'location' => 'Gedung B Lantai 1', 'description' => 'Laboratorium kimia dasar dan analitik'],
            ['name' => 'Lab Fisika', 'category' => 'Laboratorium', 'location' => 'Gedung B Lantai 2', 'description' => 'Laboratorium fisika dasar'],
            ['name' => 'Lab Biologi', 'category' => 'Laboratorium', 'location' => 'Gedung B Lantai 3', 'description' => 'Laboratorium biologi dan mikroskop'],
            
            // Ruang Kuliah
            ['name' => 'Ruang Kuliah 101', 'category' => 'Ruang Kuliah', 'location' => 'Gedung C Lantai 1', 'description' => 'Kapasitas 100 kursi, AC, Proyektor'],
            ['name' => 'Ruang Kuliah 102', 'category' => 'Ruang Kuliah', 'location' => 'Gedung C Lantai 1', 'description' => 'Kapasitas 80 kursi, AC, Proyektor'],
            ['name' => 'Ruang Kuliah 103', 'category' => 'Ruang Kuliah', 'location' => 'Gedung C Lantai 1', 'description' => 'Kapasitas 60 kursi, AC'],
            ['name' => 'Ruang Kuliah 201', 'category' => 'Ruang Kuliah', 'location' => 'Gedung C Lantai 2', 'description' => 'Kapasitas 120 kursi, AC, Proyektor, Sound System'],
            ['name' => 'Ruang Kuliah 202', 'category' => 'Ruang Kuliah', 'location' => 'Gedung C Lantai 2', 'description' => 'Kapasitas 80 kursi, AC'],
            ['name' => 'Ruang Kuliah 301', 'category' => 'Ruang Kuliah', 'location' => 'Gedung C Lantai 3', 'description' => 'Kapasitas 100 kursi, AC, Proyektor'],
            ['name' => 'Ruang Seminar', 'category' => 'Ruang Kuliah', 'location' => 'Gedung C Lantai 4', 'description' => 'Kapasitas 200 kursi, AC, Proyektor, Sound System'],
            
            // Fasilitas Umum
            ['name' => 'Perpustakaan', 'category' => 'Fasilitas Umum', 'location' => 'Gedung D Lantai 1-2', 'description' => 'Perpustakaan pusat dengan koleksi 10.000 buku'],
            ['name' => 'Ruang Baca', 'category' => 'Fasilitas Umum', 'location' => 'Gedung D Lantai 3', 'description' => 'Ruang baca nyaman dengan AC'],
            ['name' => 'Toilet Pria Gedung A', 'category' => 'Fasilitas Umum', 'location' => 'Gedung A Lantai 1', 'description' => 'Toilet pria 4 bilik'],
            ['name' => 'Toilet Wanita Gedung A', 'category' => 'Fasilitas Umum', 'location' => 'Gedung A Lantai 1', 'description' => 'Toilet wanita 4 bilik'],
            ['name' => 'Toilet Pria Gedung B', 'category' => 'Fasilitas Umum', 'location' => 'Gedung B Lantai 1', 'description' => 'Toilet pria 3 bilik'],
            ['name' => 'Toilet Wanita Gedung B', 'category' => 'Fasilitas Umum', 'location' => 'Gedung B Lantai 1', 'description' => 'Toilet wanita 3 bilik'],
            ['name' => 'Mushola', 'category' => 'Fasilitas Umum', 'location' => 'Gedung A Lantai 1', 'description' => 'Mushola kapasitas 50 jamaah'],
            ['name' => 'Kantin Utama', 'category' => 'Fasilitas Umum', 'location' => 'Gedung E', 'description' => 'Kantin utama dengan 20 tenant'],
            ['name' => 'Kantin Belakang', 'category' => 'Fasilitas Umum', 'location' => 'Area Belakang', 'description' => 'Kantin dengan 10 tenant'],
            ['name' => 'Auditorium', 'category' => 'Fasilitas Umum', 'location' => 'Gedung F', 'description' => 'Auditorium kapasitas 500 kursi'],
            
            // Area Parkir
            ['name' => 'Parkiran Motor Timur', 'category' => 'Area Parkir', 'location' => 'Area Timur', 'description' => 'Parkiran motor kapasitas 200 motor'],
            ['name' => 'Parkiran Motor Barat', 'category' => 'Area Parkir', 'location' => 'Area Barat', 'description' => 'Parkiran motor kapasitas 150 motor'],
            ['name' => 'Parkiran Mobil', 'category' => 'Area Parkir', 'location' => 'Area Barat', 'description' => 'Parkiran mobil kapasitas 50 mobil'],
            ['name' => 'Parkiran Dosen', 'category' => 'Area Parkir', 'location' => 'Gedung A', 'description' => 'Parkiran khusus dosen'],
            
            // Olahraga
            ['name' => 'Lapangan Basket', 'category' => 'Olahraga', 'location' => 'Area Selatan', 'description' => 'Lapangan basket outdoor'],
            ['name' => 'Lapangan Futsal', 'category' => 'Olahraga', 'location' => 'Area Selatan', 'description' => 'Lapangan futsal indoor'],
            ['name' => 'Gym Center', 'category' => 'Olahraga', 'location' => 'Gedung G', 'description' => 'Pusat kebugaran dengan berbagai alat'],
            ['name' => 'Kolam Renang', 'category' => 'Olahraga', 'location' => 'Area Timur', 'description' => 'Kolam renang ukuran 25m'],
        ];
        
        foreach ($facilities as $facility) {
            Facility::updateOrCreate(
                ['name' => $facility['name'], 'location' => $facility['location']],
                [
                    'category' => $facility['category'],
                    'description' => $facility['description'],
                    'risk_level' => 1,
                    'total_reports' => 0,
                ]
            );
        }
    }
}