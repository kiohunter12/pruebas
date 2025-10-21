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
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('solicitante_nombre');
            $table->string('solicitante_dni');
            $table->string('solicitante_email');
            $table->string('solicitante_telefono');
            $table->string('tipo_tramite');
            $table->string('asunto');
            $table->text('descripcion');
            $table->string('estado')->default('pendiente');
            $table->timestamp('fecha_registro')->nullable();
            $table->timestamp('fecha_derivacion')->nullable();
            $table->timestamp('fecha_revision_tecnica')->nullable();
            $table->timestamp('fecha_revision_legal')->nullable();
            $table->timestamp('fecha_resolucion')->nullable();
            $table->timestamp('fecha_firma')->nullable();
            $table->foreignId('gerencia_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('gerencia_padre_id')->nullable()->constrained('gerencias')->onDelete('set null'); // Para subgerencias
            $table->foreignId('responsable_id')->nullable()->constrained('users')->onDelete('set null'); // Usuario responsable actual
            $table->foreignId('usuario_registro_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('usuario_revision_tecnica_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('usuario_revision_legal_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('usuario_resolucion_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('usuario_firma_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('observaciones')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->boolean('requiere_informe_legal')->default(false);
            $table->boolean('es_acto_administrativo_mayor')->default(false);
            $table->string('numero_resolucion')->nullable();
            $table->string('archivo_resolucion')->nullable();
            $table->boolean('notificado_ciudadano')->default(false);
            $table->timestamp('fecha_notificacion')->nullable();
            $table->timestamps();

            // Índices
            $table->index(['estado']);
            $table->index(['gerencia_id']);
            $table->index(['gerencia_padre_id']);
            $table->index(['responsable_id']);
            $table->index(['usuario_registro_id']);
            $table->index(['fecha_registro']);
            $table->index(['numero']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedientes');
    }
};
