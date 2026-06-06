<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Facility;
use App\Models\Notification;
use App\Models\Report;
use App\Models\ReportStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            ['name' => 'Komputer', 'category' => 'Lab', 'location' => 'Gedung Utama Lt 2', 'status' => 'baik', 'sla_hours' => 24, 'is_active' => true],
            ['name' => 'Monitor', 'category' => 'Lab', 'location' => 'Gedung Utama Lt 2', 'status' => 'baik', 'sla_hours' => 24, 'is_active' => true],
            ['name' => 'Keyboard', 'category' => 'Lab', 'location' => 'Gedung Utama Lt 2', 'status' => 'baik', 'sla_hours' => 24, 'is_active' => true],
            ['name' => 'Mouse', 'category' => 'Lab', 'location' => 'Gedung Utama Lt 2', 'status' => 'baik', 'sla_hours' => 24, 'is_active' => true],
            ['name' => 'Proyektor Lab', 'category' => 'Lab', 'location' => 'Gedung Utama Lt 3', 'status' => 'baik', 'sla_hours' => 24, 'is_active' => true],
            ['name' => 'Koneksi Internet', 'category' => 'Lab', 'location' => 'Gedung Utama Lt 3', 'status' => 'baik', 'sla_hours' => 12, 'is_active' => true],

            // KELAS
            ['name' => 'Proyektor', 'category' => 'Kelas', 'location' => 'Gedung A Lt 1', 'status' => 'baik', 'sla_hours' => 24, 'is_active' => true],
            ['name' => 'AC', 'category' => 'Kelas', 'location' => 'Gedung A Lt 1', 'status' => 'baik', 'sla_hours' => 24, 'is_active' => true],
            ['name' => 'Kursi Mahasiswa', 'category' => 'Kelas', 'location' => 'Gedung B Lt 2', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
            ['name' => 'Meja Mahasiswa', 'category' => 'Kelas', 'location' => 'Gedung B Lt 2', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
            ['name' => 'Whiteboard', 'category' => 'Kelas', 'location' => 'Gedung B Lt 2', 'status' => 'baik', 'sla_hours' => 48, 'is_active' => true],
            ['name' => 'Speaker Kelas', 'category' => 'Kelas', 'location' => 'Gedung C Lt 1', 'status' => 'perlu_perbaikan', 'sla_hours' => 24, 'is_active' => true],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }

        // Ambil semua fasilitas yang sudah dibuat
        $komputer = Facility::where('name', 'Komputer')->first();
        $proyektor = Facility::where('name', 'Proyektor')->first();
        $ac = Facility::where('name', 'AC')->first();
        $kursi = Facility::where('name', 'Kursi Mahasiswa')->first();
        $whiteboard = Facility::where('name', 'Whiteboard')->first();

        // ========== 3. REPORTS (CONTOH LAPORAN) ==========
        // Laporan 1 - Pending
        $report1 = Report::create([
            'user_id' => $mahasiswa->id,
            'facility_id' => $ac->id,
            'title' => 'AC Kelas Tidak Dingin',
            'description' => 'AC tidak mengeluarkan udara dingin sehingga ruangan menjadi panas dan tidak nyaman untuk perkuliahan.',
            'location_detail' => 'Gedung A Lt 1, Ruang A101',
            'urgency' => 'high',
            'status' => 'pending',
            'image_path' => null,
            'sla_deadline' => Carbon::now()->addHours(24),
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // Laporan 2 - In Progress
        $report2 = Report::create([
            'user_id' => $mahasiswa->id,
            'facility_id' => $proyektor->id,
            'title' => 'Proyektor Tidak Bisa Menyala',
            'description' => 'Proyektor tidak dapat dinyalakan meskipun kabel listrik sudah terpasang dengan benar.',
            'location_detail' => 'Gedung B Lt 2, Ruang B201',
            'urgency' => 'medium',
            'status' => 'in_progress',
            'image_path' => null,
            'sla_deadline' => Carbon::now()->addHours(12),
            'created_at' => Carbon::now()->subDay(),
        ]);

        // Laporan 3 - Completed
        $report3 = Report::create([
            'user_id' => $mahasiswa->id,
            'facility_id' => $komputer->id,
            'title' => 'Komputer Lab Tidak Bisa Booting',
            'description' => 'Beberapa komputer di laboratorium berhenti pada layar boot dan tidak dapat masuk ke sistem.',
            'location_detail' => 'Lab Komputer, PC Nomor 5, 7, dan 9',
            'urgency' => 'high',
            'status' => 'completed',
            'image_path' => null,
            'sla_deadline' => Carbon::now()->subHours(2),
            'resolved_at' => Carbon::now()->subDay(),
            'rating' => 5,
            'rating_comment' => 'Perbaikan cepat dan semua komputer sudah dapat digunakan.',
            'created_at' => Carbon::now()->subDays(3),
        ]);

        // Laporan 4 - Completed
        $report4 = Report::create([
            'user_id' => $mahasiswa->id,
            'facility_id' => $kursi->id,
            'title' => 'Kursi Mahasiswa Rusak',
            'description' => 'Sandaran kursi patah sehingga tidak dapat digunakan dengan nyaman.',
            'location_detail' => 'Gedung B Lt 2, Ruang B202',
            'urgency' => 'low',
            'status' => 'completed',
            'image_path' => null,
            'sla_deadline' => Carbon::now()->subDays(1),
            'resolved_at' => Carbon::now()->subDays(1),
            'rating' => 3,
            'rating_comment' => 'Sudah diganti, namun cukup lama menunggu prosesnya.',
            'created_at' => Carbon::now()->subDays(5),
        ]);

        // Laporan 5 - Rejected
        $report5 = Report::create([
            'user_id' => $mahasiswa->id,
            'facility_id' => $whiteboard->id,
            'title' => 'Whiteboard Sulit Dihapus',
            'description' => 'Bekas spidol permanen masih terlihat setelah dibersihkan.',
            'location_detail' => 'Gedung A Lt 1, Ruang A102',
            'urgency' => 'low',
            'status' => 'rejected',
            'image_path' => null,
            'sla_deadline' => Carbon::now()->subHours(5),
            'resolved_at' => Carbon::now()->subHours(3),
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
