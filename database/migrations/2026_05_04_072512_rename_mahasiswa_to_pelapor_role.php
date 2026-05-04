<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Temporarily disable strict SQL mode to allow ENUM updates
        DB::statement("SET sql_mode = ''");

        // Update existing 'mahasiswa' roles to 'pelapor' in the users table
        DB::statement("UPDATE users SET role = 'pelapor' WHERE role = 'mahasiswa'");
        
        // Drop the existing 'role' column from the users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        
        // Add the 'role' column back with the new ENUM values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['pelapor', 'teknisi', 'admin'])->default('pelapor')->after('password'); // Assuming 'password' is the last field before role, adjust 'after' if needed
        });

        // Update existing 'mahasiswa' user_types to 'pelapor' in the comments table
        DB::statement("UPDATE comments SET user_type = 'pelapor' WHERE user_type = 'mahasiswa'");
        
        // Modify the user_type column in the comments table to the new ENUM values
        Schema::table('comments', function (Blueprint $table) {
            $table->enum('user_type', ['pelapor', 'teknisi', 'admin'])->change();
        });

        // Restore original SQL mode
        DB::statement("SET sql_mode = @@SESSION.sql_mode");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Temporarily disable strict SQL mode for ENUM updates
        DB::statement("SET sql_mode = ''");

        // Update existing 'pelapor' roles back to 'mahasiswa' in the users table
        DB::statement("UPDATE users SET role = 'mahasiswa' WHERE role = 'pelapor'");

        // Drop the current 'role' column from the users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Add the 'role' column back with the old ENUM values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['mahasiswa', 'teknisi', 'admin'])->default('mahasiswa')->after('password'); // Adjust 'after' if needed
        });

        // Update existing 'pelapor' user_types back to 'mahasiswa' in the comments table
        DB::statement("UPDATE comments SET user_type = 'mahasiswa' WHERE user_type = 'pelapor'");

        // Modify the user_type column in the comments table back to the old ENUM values
        Schema::table('comments', function (Blueprint $table) {
            $table->enum('user_type', ['mahasiswa', 'teknisi', 'admin'])->change();
        });

        // Restore original SQL mode
        DB::statement("SET sql_mode = @@SESSION.sql_mode");
    }
};
