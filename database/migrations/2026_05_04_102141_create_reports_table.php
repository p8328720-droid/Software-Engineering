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
            // Relasi ke Pelapor
            $table->foreignId('reporter_id')->constrained("users")->onDelete('cascade');
            
            // Relasi ke Ruangan (Menggantikan Facility)
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            
            // Relasi ke Tabel SLA (Single Source of Truth untuk Kategori & Urgensi)
            $table->foreignId('sla_id')->constrained('sla')->onDelete('cascade');

            $table->string('title');
            $table->text('description');
            
            // Kita tetap simpan urgency di sini untuk kemudahan query/sorting
            $table->enum('urgency', ['low', 'medium', 'high'])->default('medium');
            
            // Status mengikuti enum lowercase
            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending');
            
            $table->string('image_path')->nullable();
            
            // Menggunakan nama kolom sesuai permintaan kamu
            $table->timestamp('sla_deadline');
            
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('escalated_at')->nullable();
            $table->timestamps();

            // Indexing untuk optimalisasi query dashboard
            $table->index(['status', 'sla_deadline']);
            $table->index('reporter_id');
            $table->index('room_id');
            $table->index('sla_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};