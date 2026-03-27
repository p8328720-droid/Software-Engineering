<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles are stored in config/roles.php, but we can also store in database if needed
        // This seeder is for reference only
        
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Teknisi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Supervisor', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}