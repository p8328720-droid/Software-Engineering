<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, check the current column type
        $columnType = DB::select("SHOW COLUMNS FROM reports WHERE Field = 'status'");
        
        if (!empty($columnType)) {
            // If it's ENUM, convert to VARCHAR
            Schema::table('reports', function (Blueprint $table) {
                $table->string('status', 50)->default('pending')->change();
            });
        }
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('status', 20)->default('pending')->change();
        });
    }
};