<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Report;
use App\Models\Room;
use App\Models\TechnicianAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelapors = User::where('role', 'pelapor')->get();
        $teknisis = User::where('role', 'teknisi')->get();
        $admins = User::where('role', 'admin')->get();

        $rooms = Room::all();
        $categories = Category::all();

        // Pastikan data master sudah ada sebelum bikin laporan
        if ($pelapors->isEmpty() || $rooms->isEmpty() || $categories->isEmpty() || $teknisis->isEmpty() || $admins->isEmpty()) {
            return;
        }

        // Data sampel laporan yang disesuaikan dengan MVP kita
        $reportData = [
            [
                'description' => 'AC di ruang kuliah tidak mengeluarkan udara dingin, hanya angin panas.',
                'status' => 'Processing',
                'category_name' => 'AC & Pendingin Ruangan',
            ],
            [
                'description' => 'Proyektor tidak bisa menyala, lampu indikator berkedip merah terus.',
                'status' => 'Pending',
                'category_name' => 'Proyektor & Audio Visual',
            ],
            [
                'description' => 'Air dari toilet pria meluber ke luar, sepertinya pipanya mampet.',
                'status' => 'Pending',
                'category_name' => 'Kebocoran & Pipa Air',
            ],
            [
                'description' => 'Lampu neon di tengah ruangan mati satu, agak gelap kalau kuliah sore.',
                'status' => 'Completed',
                'category_name' => 'Kelistrikan & Lampu',
            ],
        ];

        foreach ($reportData as $data) {
            // Ambil data acak / sesuaikan
            $room = $rooms->random();
            $category = Category::where('name', $data['category_name'])->first() ?? $categories->random();
            $pelapor = $pelapors->random();
            $admin = $admins->first();
            $teknisi = $teknisis->random();

            // Waktu pembuatan laporan diacak antara 1 sampai 10 hari yang lalu
            $createdAt = now()->subDays(rand(1, 10));

            // 1. BUAT LAPORAN
            $report = Report::create([
                'reporter_id' => $pelapor->id,
                'room_id' => $room->id,
                'category_id' => $category->id,
                'description' => $data['description'],
                'status' => $data['status'],
                // SLA dihitung otomatis dari tabel kategori
                'deadline' => (clone $createdAt)->addHours($category->sla_hours),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // 2. BUAT AUDIT LOG AWAL (Laporan Dibuat)
            AuditLog::create([
                'report_id' => $report->id,
                'user_id' => $pelapor->id,
                'status_changed_to' => 'Pending',
                'notes' => 'Laporan pertama kali dibuat oleh mahasiswa',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // 3. JIKA STATUSNYA DIPROSES / SELESAI
            if ($data['status'] !== 'Pending') {
                $assignedAt = (clone $createdAt)->addHours(1); // Ditugaskan 1 jam setelah lapor

                // Buat Penugasan Teknisi
                TechnicianAssignment::create([
                    'report_id' => $report->id,
                    'technician_id' => $teknisi->id,
                    'assigned_by' => $admin->id,
                    'assigned_at' => $assignedAt,
                    'started_at' => (clone $assignedAt)->addMinutes(30),
                    'completed_at' => $data['status'] === 'Completed' ? (clone $assignedAt)->addHours(3) : null,
                    'notes' => 'Mohon dicek segera alatnya.',
                ]);

                // Audit Log: Admin mengubah status jadi Processing
                AuditLog::create([
                    'report_id' => $report->id,
                    'user_id' => $admin->id,
                    'status_changed_to' => 'Processing',
                    'notes' => 'Laporan diverifikasi dan ditugaskan ke teknisi',
                    'created_at' => $assignedAt,
                    'updated_at' => $assignedAt,
                ]);

                // 4. JIKA STATUSNYA SELESAI
                if ($data['status'] === 'Completed') {
                    $completedAt = (clone $assignedAt)->addHours(3);

                    // Audit Log: Teknisi menyelesaikan laporan
                    AuditLog::create([
                        'report_id' => $report->id,
                        'user_id' => $teknisi->id,
                        'status_changed_to' => 'Completed',
                        'notes' => 'Pekerjaan selesai, komponen sudah diganti.',
                        'created_at' => $completedAt,
                        'updated_at' => $completedAt,
                    ]);
                }
            }
        }
    }
}
