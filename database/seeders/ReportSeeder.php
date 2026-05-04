<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;
use App\Models\Facility;
use App\Models\ReportStatus;
use App\Models\TechnicianAssignment;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'mahasiswa')->get();
        $facilities = Facility::all();
        $technicians = User::where('role', 'teknisi')->get();
        $supervisors = User::where('role', 'supervisor')->get();
        
        if ($students->isEmpty() || $facilities->isEmpty() || $technicians->isEmpty()) {
            return;
        }

        // Create sample reports
        $reportData = [
            [
                'title' => 'AC Ruang Kuliah 101 Tidak Dingin',
                'description' => 'AC di ruang kuliah 101 tidak mengeluarkan udara dingin, hanya angin biasa.',
                'urgency' => 'medium',
                'status' => 'in_progress',
                'facility_name' => 'Ruang Kuliah 101',
            ],
            [
                'title' => 'Proyektor Lab Komputer 1 Rusak',
                'description' => 'Proyektor di Lab Komputer 1 tidak bisa menyala, lampu indikator berkedip merah.',
                'urgency' => 'high',
                'status' => 'pending',
                'facility_name' => 'Lab Komputer 1',
            ],
            [
                'title' => 'Toilet Pria Gedung A Mampet',
                'description' => 'Toilet di lantai 1 Gedung A bagian pria mengalami mampet.',
                'urgency' => 'medium',
                'status' => 'pending',
                'facility_name' => 'Toilet Pria Gedung A',
            ],
            [
                'title' => 'Keran Air Mushola Bocor',
                'description' => 'Keran air di area wudhu mushola bocor.',
                'urgency' => 'low',
                'status' => 'completed',
                'facility_name' => 'Mushola',
            ],
        ];
        
        foreach ($reportData as $data) {
            $facility = Facility::where('name', $data['facility_name'])->first();
            if (!$facility) continue;
            
            $reporter = $students->random();
            
            $report = Report::create([
                'user_id' => $reporter->id,
                'facility_id' => $facility->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'location_detail' => $facility->location,
                'urgency' => $data['urgency'],
                'status' => $data['status'],
                'sla_deadline' => now()->addHours(24),
                'created_at' => now()->subDays(rand(1, 10)),
            ]);
            
            // Create initial status
            ReportStatus::create([
                'report_id' => $report->id,
                'user_id' => $reporter->id,
                'status' => 'pending',
                'description' => 'Laporan dibuat',
                'created_at' => $report->created_at,
            ]);
            
            if ($data['status'] !== 'pending') {
                $technician = $technicians->random();
                $supervisor = $supervisors->first() ?? User::where('role', 'admin')->first();
                
                TechnicianAssignment::create([
                    'report_id' => $report->id,
                    'technician_id' => $technician->id,
                    'assigned_by' => $supervisor->id,
                    'assigned_at' => $report->created_at->addHour(),
                    'started_at' => $report->created_at->addHours(2),
                    'completed_at' => $data['status'] === 'completed' ? $report->created_at->addHours(5) : null,
                    'notes' => 'Tolong segera diperbaiki',
                ]);

                ReportStatus::create([
                    'report_id' => $report->id,
                    'user_id' => $supervisor->id,
                    'status' => 'verified',
                    'description' => 'Laporan diverifikasi dan ditugaskan ke teknisi',
                    'created_at' => $report->created_at->addHour(),
                ]);

                if ($data['status'] === 'in_progress' || $data['status'] === 'completed') {
                    ReportStatus::create([
                        'report_id' => $report->id,
                        'user_id' => $technician->id,
                        'status' => 'in_progress',
                        'description' => 'Pekerjaan dimulai',
                        'created_at' => $report->created_at->addHours(2),
                    ]);
                }

                if ($data['status'] === 'completed') {
                    ReportStatus::create([
                        'report_id' => $report->id,
                        'user_id' => $technician->id,
                        'status' => 'completed',
                        'description' => 'Pekerjaan selesai',
                        'created_at' => $report->created_at->addHours(5),
                    ]);
                    $report->update(['resolved_at' => $report->created_at->addHours(5)]);
                }
            }
        }
    }
}
