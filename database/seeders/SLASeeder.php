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
        $categories = ['AC', 'Laboratorium', 'Ruang Kuliah', 'Fasilitas Umum', 'Area Parkir', 'Olahraga'];
        $urgencies = ['low', 'medium', 'high'];

        foreach ($categories as $category) {
            foreach ($urgencies as $urgency) {
                $response = 24;
                $resolution = 72;

                if ($urgency === 'medium') {
                    $response = 12;
                    $resolution = 48;
                } elseif ($urgency === 'high') {
                    $response = 4;
                    $resolution = 24;
                }

                // Adjust based on category
                if ($category === 'Laboratorium' || $category === 'Ruang Kuliah') {
                    $resolution -= 12;
                }

                SLA::updateOrCreate(
                    [
                        'facility_category' => $category,
                        'urgency' => $urgency,
                    ],
                    [
                        'response_hours' => $response,
                        'resolution_hours' => $resolution,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
