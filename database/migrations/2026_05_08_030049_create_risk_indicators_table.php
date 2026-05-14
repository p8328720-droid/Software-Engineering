<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->integer('total_reports')->default(0);
            $table->integer('critical_reports')->default(0);
            $table->float('avg_resolution_time')->default(0);
            $table->float('risk_score')->default(0);
            $table->enum('risk_level', ['rendah', 'sedang', 'tinggi', 'kritis'])->default('rendah');
            $table->text('recommendations')->nullable();
            $table->date('period_start');
            $table->date('period_end');
            $table->timestamps();
            
            // Index untuk optimasi
            $table->index(['facility_id', 'risk_level']);
            $table->index('period_end');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_indicators');
    }
};