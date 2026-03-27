<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SLA;

class SLASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slaSettings = [
            // Laboratorium
            ['facility_category' => 'Laboratorium', 'priority' => 1, 'hours_limit' => 48, 'is_active' => true],
            ['facility_category' => 'Laboratorium', 'priority' => 2, 'hours_limit' => 24, 'is_active' => true],
            ['facility_category' => 'Laboratorium', 'priority' => 3, 'hours_limit' => 12, 'is_active' => true],
            
            // Ruang Kuliah
            ['facility_category' => 'Ruang Kuliah', 'priority' => 1, 'hours_limit' => 72, 'is_active' => true],
            ['facility_category' => 'Ruang Kuliah', 'priority' => 2, 'hours_limit' => 48, 'is_active' => true],
            ['facility_category' => 'Ruang Kuliah', 'priority' => 3, 'hours_limit' => 24, 'is_active' => true],
            
            // Fasilitas Umum
            ['facility_category' => 'Fasilitas Umum', 'priority' => 1, 'hours_limit' => 96, 'is_active' => true],
            ['facility_category' => 'Fasilitas Umum', 'priority' => 2, 'hours_limit' => 48, 'is_active' => true],
            ['facility_category' => 'Fasilitas Umum', 'priority' => 3, 'hours_limit' => 24, 'is_active' => true],
            
            // Area Parkir
            ['facility_category' => 'Area Parkir', 'priority' => 1, 'hours_limit' => 48, 'is_active' => true],
            ['facility_category' => 'Area Parkir', 'priority' => 2, 'hours_limit' => 24, 'is_active' => true],
            ['facility_category' => 'Area Parkir', 'priority' => 3, 'hours_limit' => 12, 'is_active' => true],
            
            // Olahraga
            ['facility_category' => 'Olahraga', 'priority' => 1, 'hours_limit' => 72, 'is_active' => true],
            ['facility_category' => 'Olahraga', 'priority' => 2, 'hours_limit' => 48, 'is_active' => true],
            ['facility_category' => 'Olahraga', 'priority' => 3, 'hours_limit' => 24, 'is_active' => true],
        ];
        
        foreach ($slaSettings as $sla) {
            SLA::updateOrCreate(
                [
                    'facility_category' => $sla['facility_category'],
                    'priority' => $sla['priority'],
                ],
                $sla
            );
        }
    }
}