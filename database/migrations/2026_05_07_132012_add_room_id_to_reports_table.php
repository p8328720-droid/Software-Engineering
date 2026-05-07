<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Add room_id column
            $table->foreignId('room_id')->nullable()->after('reporter_id')->constrained('rooms')->onDelete('set null');
            
            // Add index for better performance
            $table->index('room_id');
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['room_id']);
            
            // Drop the column
            $table->dropColumn('room_id');
        });
    }
};