<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;
use App\Models\Facility;
use App\Models\ReportStatus;
use App\Models\TechnicianAssignment;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswa = User::where('role', 1)->get();
        $facilities = Facility::all();
        $technicians = User::where('role', 2)->get();
        
        $statuses = [
            1 => 'diterima',
            2 => 'diproses',
            3 => 'selesai',
            4 => 'ditolak',
        ];
        
        // Create sample reports
        $reports = [
            [
                'title' => 'AC Ruang Kuliah 101 Tidak Dingin',
                'description' => 'AC di ruang kuliah 101 tidak mengeluarkan udara dingin, hanya angin biasa. Sudah dilaporkan sejak seminggu yang lalu.',
                'priority' => 2,
                'status' => 2,
                'facility_name' => 'Ruang Kuliah 101',
            ],
            [
                'title' => 'Proyektor Lab Komputer 1 Rusak',
                'description' => 'Proyektor di Lab Komputer 1 tidak bisa menyala, lampu indikator berkedip merah.',
                'priority' => 3,
                'status' => 2,
                'facility_name' => 'Lab Komputer 1',
            ],
            [
                'title' => 'Toilet Pria Gedung A Mampet',
                'description' => 'Toilet di lantai 1 Gedung A bagian pria mengalami mampet dan air tidak mengalir.',
                'priority' => 2,
                'status' => 1,
                'facility_name' => 'Toilet Pria Gedung A',
            ],
            [
                'title' => 'Lampu Parkiran Motor Timur Mati',
                'description' => 'Lampu penerangan di area parkiran motor timur mati total, sangat gelap di malam hari.',
                'priority' => 1,
                'status' => 1,
                'facility_name' => 'Parkiran Motor Timur',
            ],
            [
                'title' => 'Kursi Ruang Kuliah 102 Banyak Rusak',
                'description' => 'Sekitar 10 kursi di ruang kuliah 102 dalam kondisi rusak, beberapa kursi goyang dan meja patah.',
                'priority' => 2,
                'status' => 2,
                'facility_name' => 'Ruang Kuliah 102',
            ],
            [
                'title' => 'Koneksi Internet Lab Jaringan Lemot',
                'description' => 'Koneksi internet di lab jaringan sangat lambat, sering putus-putus saat praktikum.',
                'priority' => 3,
                'status' => 2,
                'facility_name' => 'Lab Jaringan',
            ],
            [
                'title' => 'Pintu Perpustakaan Rusak',
                'description' => 'Pintu masuk perpustakaan susah dibuka, handle pintu longgar.',
                'priority' => 2,
                'status' => 1,
                'facility_name' => 'Perpustakaan',
            ],
            [
                'title' => 'Keran Air Mushola Bocor',
                'description' => 'Keran air di area wudhu mushola bocor, air terus mengalir meskipun sudah ditutup.',
                'priority' => 2,
                'status' => 3,
                'facility_name' => 'Mushola',
            ],
            [
                'title' => 'Sound System Auditorium Bermasalah',
                'description' => 'Sound system di auditorium mengeluarkan suara berdengung saat digunakan.',
                'priority' => 2,
                'status' => 1,
                'facility_name' => 'Auditorium',
            ],
            [
                'title' => 'Lapangan Basket Retak',
                'description' => 'Lantai lapangan basket terdapat retakan yang cukup besar, berbahaya untuk pemain.',
                'priority' => 3,
                'status' => 1,
                'facility_name' => 'Lapangan Basket',
            ],
        ];
        
        foreach ($reports as $index => $reportData) {
            $facility = Facility::where('name', $reportData['facility_name'])->first();
            if (!$facility) continue;
            
            $reporter = $mahasiswa->random();
            
            $report = Report::create([
                'reporter_id' => $reporter->id,
                'facility_id' => $facility->id,
                'title' => $reportData['title'],
                'description' => $reportData['description'],
                'location' => $facility->location,
                'priority' => $reportData['priority'],
                'status' => $reportData['status'],
                'sla_deadline' => now()->addHours(24),
                'is_escalated' => false,
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
            
            // Create status history
            $statusHistory = [
                ['status' => 1, 'notes' => 'Laporan diterima dan menunggu verifikasi'],
            ];
            
            if ($reportData['status'] >= 2) {
                $statusHistory[] = ['status' => 2, 'notes' => 'Laporan sedang diproses oleh teknisi'];
            }
            
            if ($reportData['status'] >= 3) {
                $statusHistory[] = ['status' => 3, 'notes' => 'Perbaikan telah selesai dilakukan'];
                $report->update(['completed_at' => now()->subDays(rand(1, 10))]);
            }
            
            if ($reportData['status'] == 4) {
                $statusHistory = [
                    ['status' => 1, 'notes' => 'Laporan diterima'],
                    ['status' => 4, 'notes' => 'Laporan ditolak karena tidak valid'],
                ];
            }
            
            foreach ($statusHistory as $index => $history) {
                ReportStatus::create([
                    'report_id' => $report->id,
                    'status' => $history['status'],
                    'notes' => $history['notes'],
                    'changed_by' => $reporter->id,
                    'created_at' => $report->created_at->addHours($index * 2),
                ]);
            }
            
            // Assign technician for reports in progress or completed
            if (in_array($reportData['status'], [2, 3]) && $technicians->count() > 0) {
                $technician = $technicians->random();
                TechnicianAssignment::create([
                    'report_id' => $report->id,
                    'technician_id' => $technician->id,
                    'assigned_by' => User::where('role', 3)->first()->id ?? 1,
                    'started_at' => $report->created_at->addHours(rand(1, 12)),
                    'completed_at' => $reportData['status'] == 3 ? $report->created_at->addHours(rand(24, 72)) : null,
                    'notes' => 'Ditugaskan untuk penanganan laporan',
                ]);
            }
        }
        
        // Update facility risk levels
        foreach ($facilities as $facility) {
            $facility->updateRiskLevel();
        }
    }
}