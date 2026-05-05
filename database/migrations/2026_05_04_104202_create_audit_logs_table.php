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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Relasi langsung ke laporan yang lagi dikerjain
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');

            // Siapa yang ngubah status atau ngasih komentar?
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Statusnya berubah jadi apa? (Pending, Processing, Completed)
            $table->string('status_changed_to');

            // Komentar, alasan penolakan, atau catatan dari teknisi
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
