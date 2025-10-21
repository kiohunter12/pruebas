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
        Schema::table('tipo_tramites', function (Blueprint $table) {
            $table->foreignId('workflow_id')->nullable()->after('gerencia_id')->constrained('workflows')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tipo_tramites', function (Blueprint $table) {
            $table->dropForeign(['workflow_id']);
            $table->dropColumn('workflow_id');
        });
    }
};
