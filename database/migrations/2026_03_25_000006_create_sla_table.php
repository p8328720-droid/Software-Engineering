<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla', function (Blueprint $table) {
            $table->id();
            $table->string('facility_category');
            $table->enum('urgency', ['low', 'medium', 'high']);
            $table->integer('response_hours');
            $table->integer('resolution_hours');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['facility_category', 'urgency']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla');
    }
};