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
        Schema::create('tipo_tramite_tipo_documento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_tramite_id')->constrained('tipo_tramites')->onDelete('cascade');
            $table->foreignId('tipo_documento_id')->constrained('tipo_documentos')->onDelete('cascade');
            $table->boolean('requerido')->default(true)->comment('Si el documento es obligatorio u opcional');
            $table->integer('orden')->default(0)->comment('Orden de visualización');
            $table->timestamps();

            $table->unique(['tipo_tramite_id', 'tipo_documento_id'], 'tt_td_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_tramite_tipo_documento');
    }
};
