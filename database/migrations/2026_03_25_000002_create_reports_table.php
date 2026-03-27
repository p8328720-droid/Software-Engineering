<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('location_detail');
            $table->enum('urgency', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'verified', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->string('image_path')->nullable();
            $table->timestamp('sla_deadline');
            $table->timestamp('resolved_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamp('escalated_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'sla_deadline']);
            $table->index('user_id');
            $table->index('facility_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};