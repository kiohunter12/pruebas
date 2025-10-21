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
        Schema::table('expedientes', function (Blueprint $table) {
            $table->unsignedBigInteger('workflow_id')->nullable()->after('gerencia_id');
            $table->unsignedBigInteger('current_step_id')->nullable()->after('workflow_id');
            $table->json('workflow_data')->nullable()->after('current_step_id');
            $table->timestamp('step_started_at')->nullable()->after('workflow_data');
            $table->timestamp('step_deadline')->nullable()->after('step_started_at');
            $table->json('step_history')->nullable()->after('step_deadline');

            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('set null');
            $table->foreign('current_step_id')->references('id')->on('workflow_steps')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('expedientes', function (Blueprint $table) {
            $table->dropForeign(['workflow_id']);
            $table->dropForeign(['current_step_id']);
            $table->dropColumn(['workflow_id', 'current_step_id', 'workflow_data', 'step_started_at', 'step_deadline', 'step_history']);
        });
    }
};
