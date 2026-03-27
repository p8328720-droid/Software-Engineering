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
            $table->integer('total_reports');
            $table->integer('critical_reports');
            $table->float('avg_resolution_time');
            $table->float('risk_score');
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical']);
            $table->text('recommendations')->nullable();
            $table->date('period_start');
            $table->date('period_end');
            $table->timestamps();
            
            $table->index(['facility_id', 'risk_level']);
            $table->index('period_end');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_indicators');
    }
};