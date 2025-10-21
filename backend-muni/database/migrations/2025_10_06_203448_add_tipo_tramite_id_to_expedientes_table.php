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
            // Agregar campo tipo_tramite_id como foreign key
            $table->foreignId('tipo_tramite_id')->nullable()->after('id')->constrained('tipo_tramites')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expedientes', function (Blueprint $table) {
            $table->dropForeign(['tipo_tramite_id']);
            $table->dropColumn('tipo_tramite_id');
        });
    }
};
