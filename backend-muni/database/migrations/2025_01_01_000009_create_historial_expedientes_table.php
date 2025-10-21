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
        Schema::create('historial_expedientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained()->onDelete('cascade');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('accion');
            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo');
            $table->text('descripcion');
            $table->json('datos_adicionales')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Índices
            $table->index(['expediente_id']);
            $table->index(['usuario_id']);
            $table->index(['accion']);
            $table->index(['estado_nuevo']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_expedientes');
    }
};
