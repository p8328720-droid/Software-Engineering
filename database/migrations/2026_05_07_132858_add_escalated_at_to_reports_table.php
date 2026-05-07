<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            if (!Schema::hasColumn('reports', 'escalated_at')) {
                $table->timestamp('escalated_at')->nullable()->after('sla_deadline');
                $table->index('escalated_at');
            }
            
            if (!Schema::hasColumn('reports', 'escalated_by')) {
                $table->foreignId('escalated_by')->nullable()->after('escalated_at');
            }
            
            if (!Schema::hasColumn('reports', 'escalation_reason')) {
                $table->text('escalation_reason')->nullable()->after('escalated_by');
            }
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['escalated_at', 'escalated_by', 'escalation_reason']);
        });
    }
};