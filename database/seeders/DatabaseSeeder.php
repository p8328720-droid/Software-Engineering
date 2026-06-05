<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Facility;
use App\Models\Report;
use App\Models\ReportStatus;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ========== 1. USERS ==========
        // Admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Teknisi
        $teknisi = User::create([
            'name' => 'Teknisi Budi',
            'email' => 'teknisi@example.com',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
        ]);

        // Mahasiswa (pelapor)
        $mahasiswa = User::create([
            'name' => 'Mahasiswa Demo',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'student_id' => '12345678',
            'phone' => '08123456789',
            'faculty' => 'Fakultas Ilmu Komputer',
            'major' => 'Teknik Informatika',
        ]);

        // ========== 2. FACILITIES (LAB & KELAS) ==========
        $facilities = [
            // LAB
            ['name' => 'Lab Komputer', 'category' => 'Lab', 'location' => 'Gedung Utama Lt 2', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
            ['name' => 'Lab Jaringan', 'category' => 'Lab', 'location' => 'Gedung Utama Lt 3', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
            ['name' => 'Lab Multimedia', 'category' => 'Lab', 'location' => 'Gedung Utama Lt 1', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
            // KELAS
            ['name' => 'Kelas Regular', 'category' => 'Kelas', 'location' => 'Gedung A Lantai 1', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
            ['name' => 'Kelas B Super', 'category' => 'Kelas', 'location' => 'Gedung B Lantai 2', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
            ['name' => 'Auditorium', 'category' => 'Kelas', 'location' => 'Gedung C Lt 1', 'status' => 'perlu_perbaikan', 'sla_hours' => 36, 'is_active' => true],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }

        // Ambil semua fasilitas yang sudah dibuat
        $lab1 = Facility::where('name', 'Lab Komputer')->first();
        $lab2 = Facility::where('name', 'Lab Jaringan')->first();
        $kelas1 = Facility::where('name', 'Kelas Regular')->first();
        $kelas2 = Facility::where('name', 'Kelas B Super')->first();
        $auditorium = Facility::where('name', 'Auditorium')->first();

        // ========== 3. REPORTS (CONTOH LAPORAN) ==========
        // Laporan 1 - Pending (belum diproses)
        $report1 = Report::create([
            'user_id' => $mahasiswa->id,
            'facility_id' => $lab1->id,
            'title' => 'AC Lab Komputer Tidak Dingin',
            'description' => 'AC di Lab Komputer tidak mengeluarkan udara dingin, hanya angin biasa. Ruangan menjadi panas dan tidak nyaman untuk praktikum.',
            'location_detail' => 'Gedung Utama Lt 2, Lab Komputer (dekat jendela)',
            'urgency' => 'high',
            'status' => 'pending',
            'image_path' => null,
            'sla_deadline' => Carbon::now()->addHours(24),
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // Laporan 2 - In Progress (sedang diproses)
        $report2 = Report::create([
            'user_id' => $mahasiswa->id,
            'facility_id' => $kelas1->id,
            'title' => 'Proyektor Kelas A 101 Mati Total',
            'description' => 'Proyektor tidak bisa dinyalakan, sudah dicoba dengan remote dan tombol manual tetap tidak hidup.',
            'location_detail' => 'Gedung A Lantai 1, Ruang 101',
            'urgency' => 'medium',
            'status' => 'in_progress',
            'image_path' => null,
            'sla_deadline' => Carbon::now()->addHours(12),
            'resolved_at' => null,
            'created_at' => Carbon::now()->subDays(1),
        ]);

        // Laporan 3 - Completed (selesai, sudah diberi rating)
        $report3 = Report::create([
            'user_id' => $mahasiswa->id,
            'facility_id' => $lab2->id,
            'title' => 'Komputer Lab Jaringan Rusak',
            'description' => '3 unit komputer tidak bisa booting, layar biru terus setelah dinyalakan.',
            'location_detail' => 'Gedung Utama Lt 3, Lab Jaringan (PC nomor 5,7,9)',
            'urgency' => 'high',
            'status' => 'completed',
            'image_path' => null,
            'sla_deadline' => Carbon::now()->subHours(2),
            'resolved_at' => Carbon::now()->subDay(),
            'rating' => 5,
            'rating_comment' => 'Terima kasih, perbaikan cepat dan memuaskan!',
            'created_at' => Carbon::now()->subDays(3),
        ]);

        // Laporan 4 - Completed (selesai, rating rendah)
        $report4 = Report::create([
            'user_id' => $mahasiswa->id,
            'facility_id' => $kelas2->id,
            'title' => 'Lampu Kelas B 201 Berkedip',
            'description' => 'Lampu neon di kelas berkedip-kedip, mengganggu proses belajar mengajar.',
            'location_detail' => 'Gedung B Lantai 2, Ruang 201',
            'urgency' => 'low',
            'status' => 'completed',
            'image_path' => null,
            'sla_deadline' => Carbon::now()->subDays(1),
            'resolved_at' => Carbon::now()->subDays(1),
            'rating' => 3,
            'rating_comment' => 'Perbaikan agak lambat, tapi sekarang sudah normal.',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        // Laporan 5 - Rejected (ditolak)
        $report5 = Report::create([
            'user_id' => $mahasiswa->id,
            'facility_id' => $auditorium->id,
            'title' => 'AC Auditorium Bocor',
            'description' => 'AC mengeluarkan air dan menetes ke lantai, sudah dilaporkan sebelumnya tapi belum ditangani.',
            'location_detail' => 'Gedung C Lt 1, Auditorium (panggung sebelah kiri)',
            'urgency' => 'medium',
            'status' => 'rejected',
            'image_path' => null,
            'sla_deadline' => Carbon::now()->subHours(5),
            'resolved_at' => Carbon::now()->subHours(3),
            'admin_note' => 'Kerusakan bukan wewenang teknisi internal, akan diteruskan ke vendor.',
            'created_at' => Carbon::now()->subDays(4),
        ]);

        // ========== 4. REPORT STATUS HISTORY ==========
        // History untuk report1 (pending)
        ReportStatus::create([
            'report_id' => $report1->id,
            'user_id' => $mahasiswa->id,
            'status' => 'pending',
            'description' => 'Laporan dibuat',
            'created_at' => $report1->created_at,
        ]);

        // History untuk report2 (in_progress)
        ReportStatus::create([
            'report_id' => $report2->id,
            'user_id' => $mahasiswa->id,
            'status' => 'pending',
            'description' => 'Laporan dibuat',
            'created_at' => $report2->created_at,
        ]);
        ReportStatus::create([
            'report_id' => $report2->id,
            'user_id' => $admin->id,
            'status' => 'verified',
            'description' => 'Laporan diverifikasi oleh admin',
            'created_at' => $report2->created_at->addHours(1),
        ]);
        ReportStatus::create([
            'report_id' => $report2->id,
            'user_id' => $teknisi->id,
            'status' => 'in_progress',
            'description' => 'Teknisi mulai mengerjakan perbaikan',
            'created_at' => $report2->created_at->addHours(2),
        ]);

        // History untuk report3 (completed dengan rating)
        ReportStatus::create([
            'report_id' => $report3->id,
            'user_id' => $mahasiswa->id,
            'status' => 'pending',
            'description' => 'Laporan dibuat',
            'created_at' => $report3->created_at,
        ]);
        ReportStatus::create([
            'report_id' => $report3->id,
            'user_id' => $admin->id,
            'status' => 'verified',
            'description' => 'Laporan diverifikasi oleh admin',
            'created_at' => $report3->created_at->addHours(1),
        ]);
        ReportStatus::create([
            'report_id' => $report3->id,
            'user_id' => $teknisi->id,
            'status' => 'in_progress',
            'description' => 'Teknisi mengganti komponen yang rusak',
            'created_at' => $report3->created_at->addDays(1),
        ]);
        ReportStatus::create([
            'report_id' => $report3->id,
            'user_id' => $teknisi->id,
            'status' => 'completed',
            'description' => 'Perbaikan selesai, semua komputer berfungsi normal',
            'created_at' => $report3->resolved_at,
        ]);

        // History untuk report4 (completed rating rendah)
        ReportStatus::create([
            'report_id' => $report4->id,
            'user_id' => $mahasiswa->id,
            'status' => 'pending',
            'description' => 'Laporan dibuat',
            'created_at' => $report4->created_at,
        ]);
        ReportStatus::create([
            'report_id' => $report4->id,
            'user_id' => $admin->id,
            'status' => 'verified',
            'description' => 'Laporan diverifikasi',
            'created_at' => $report4->created_at->addHours(2),
        ]);
        ReportStatus::create([
            'report_id' => $report4->id,
            'user_id' => $teknisi->id,
            'status' => 'in_progress',
            'description' => 'Teknisi memeriksa instalasi listrik',
            'created_at' => $report4->created_at->addDays(1),
        ]);
        ReportStatus::create([
            'report_id' => $report4->id,
            'user_id' => $teknisi->id,
            'status' => 'completed',
            'description' => 'Lampu diganti, sekarang normal',
            'created_at' => $report4->resolved_at,
        ]);

        // History untuk report5 (rejected)
        ReportStatus::create([
            'report_id' => $report5->id,
            'user_id' => $mahasiswa->id,
            'status' => 'pending',
            'description' => 'Laporan dibuat',
            'created_at' => $report5->created_at,
        ]);
        ReportStatus::create([
            'report_id' => $report5->id,
            'user_id' => $admin->id,
            'status' => 'rejected',
            'description' => 'Ditolak: kerusakan AC perlu penanganan dari vendor eksternal',
            'created_at' => $report5->resolved_at,
        ]);

        // ========== 5. KOMENTAR (CONTOH) ==========
        Comment::create([
            'report_id' => $report2->id,
            'user_id' => $mahasiswa->id,
            'comment' => 'Mohon segera diperbaiki karena ada ujian besok.',
            'user_type' => 'mahasiswa',
        ]);
        Comment::create([
            'report_id' => $report2->id,
            'user_id' => $teknisi->id,
            'comment' => 'Sedang menunggu spare part, akan diselesaikan besok.',
            'user_type' => 'teknisi',
        ]);
        Comment::create([
            'report_id' => $report3->id,
            'user_id' => $teknisi->id,
            'comment' => 'Komputer sudah diperbaiki, silakan dicek kembali.',
            'user_type' => 'teknisi',
        ]);

        // ========== 6. NOTIFIKASI (CONTOH) ==========
        Notification::create([
            'user_id' => $teknisi->id,
            'report_id' => $report1->id,
            'title' => 'Laporan Baru #00001',
            'message' => 'Laporan baru dari Mahasiswa Demo: "AC Lab Komputer Tidak Dingin"',
            'type' => 'info',
            'is_read' => false,
        ]);
        Notification::create([
            'user_id' => $mahasiswa->id,
            'report_id' => $report3->id,
            'title' => 'Laporan Selesai #00003',
            'message' => 'Laporan Anda telah selesai, silakan beri rating.',
            'type' => 'success',
            'is_read' => false,
        ]);

        $this->command->info('Database seeding selesai!');
        $this->command->info('User: admin@example.com / password | teknisi@example.com / password | mahasiswa@example.com / password');
    }
}