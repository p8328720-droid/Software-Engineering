<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kelistrikan & Lampu', 'sla_hours' => 24],
            ['name' => 'AC & Pendingin Ruangan', 'sla_hours' => 48],
            ['name' => 'Jaringan Internet & Wi-Fi', 'sla_hours' => 12],
            ['name' => 'Furnitur (Meja/Kursi/Lemari)', 'sla_hours' => 72],
            ['name' => 'Proyektor & Audio Visual', 'sla_hours' => 24],
            ['name' => 'Kebocoran & Pipa Air', 'sla_hours' => 24],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}