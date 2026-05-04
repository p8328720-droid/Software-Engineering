<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing supervisors to admin
        \App\Models\User::where('role', 'supervisor')->update(['role' => 'admin']);

        // Modify users table
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['mahasiswa', 'teknisi', 'admin'])->default('mahasiswa')->change();
        });

        // Modify comments table (if user_type used roles)
        Schema::table('comments', function (Blueprint $table) {
            $table->enum('user_type', ['mahasiswa', 'teknisi', 'admin'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['mahasiswa', 'teknisi', 'supervisor', 'admin'])->default('mahasiswa')->change();
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->enum('user_type', ['mahasiswa', 'teknisi', 'supervisor', 'admin'])->change();
        });
    }
};
