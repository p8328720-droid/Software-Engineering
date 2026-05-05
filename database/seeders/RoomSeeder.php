<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['name' => 'Lab Komputer Dasar (Lab 1)'],
            ['name' => 'Lab Jaringan & Keamanan (Lab 2)'],
            ['name' => 'Lab Rekayasa Perangkat Lunak (Lab 3)'],
            ['name' => 'Ruang Kelas A.101'],
            ['name' => 'Ruang Kelas A.102'],
            ['name' => 'Ruang Kelas B.201'],
            ['name' => 'Perpustakaan Utama'],
            ['name' => 'Ruang Rapat Dosen'],
            ['name' => 'Aula Mahasiswa'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}