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
        Schema::create('workflow_rules', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_regla')->comment('Nombre descriptivo de la regla');
            $table->string('tipo_tramite')->comment('Tipo de trámite que activa esta regla');
            $table->string('palabra_clave')->nullable()->comment('Palabra clave en el asunto para activar la regla');
            $table->foreignId('gerencia_destino_id')->constrained('gerencias')->comment('Gerencia a la que se asigna automáticamente');
            $table->integer('prioridad')->default(0)->comment('Prioridad de la regla (mayor número = mayor prioridad)');
            $table->boolean('activa')->default(true)->comment('Si la regla está activa');
            $table->text('descripcion')->nullable()->comment('Descripción de cuándo se aplica esta regla');
            $table->json('condiciones_adicionales')->nullable()->comment('Condiciones adicionales en formato JSON');
            $table->foreignId('created_by')->nullable()->constrained('users')->comment('Usuario que creó la regla');
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['tipo_tramite', 'activa']);
            $table->index(['palabra_clave', 'activa']);
            $table->index('prioridad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_rules');
    }
};
