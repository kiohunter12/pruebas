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
        Schema::create('expediente_workflow_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->comment('Expediente en proceso');
            $table->unsignedBigInteger('workflow_step_id')->nullable()->comment('Etapa del flujo');
            $table->enum('estado', ['pendiente', 'en_proceso', 'aprobado', 'rechazado', 'observado'])->default('pendiente');
            $table->foreignId('asignado_a')->nullable()->constrained('users')->comment('Usuario responsable de esta etapa');
            $table->datetime('fecha_inicio')->nullable()->comment('Cuándo se inició esta etapa');
            $table->datetime('fecha_limite')->nullable()->comment('Fecha límite para completar');
            $table->datetime('fecha_completado')->nullable()->comment('Cuándo se completó');
            $table->foreignId('completado_por')->nullable()->constrained('users')->comment('Quien completó la etapa');
            $table->text('comentarios')->nullable()->comment('Comentarios del responsable');
            $table->text('motivo_rechazo')->nullable()->comment('Motivo si fue rechazado');
            $table->json('documentos_adjuntos')->nullable()->comment('Documentos subidos en esta etapa');
            $table->json('metadata')->nullable()->comment('Datos adicionales de la etapa');
            $table->timestamps();
            
            // Índices
            $table->index(['expediente_id', 'estado']);
            $table->index(['asignado_a', 'estado']);
            $table->index('fecha_limite');
            $table->index(['expediente_id', 'workflow_step_id'], 'idx_exp_workflow_step');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expediente_workflow_progress');
    }
};
