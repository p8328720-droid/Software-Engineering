<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('location');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['baik', 'perlu_perbaikan', 'rusak'])->default('baik');
            $table->integer('sla_hours')->default(48);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};