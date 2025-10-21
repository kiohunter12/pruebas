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
        Schema::create('gerencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['gerencia', 'subgerencia'])->default('gerencia');
            $table->foreignId('gerencia_padre_id')->nullable()->constrained('gerencias')->onDelete('cascade');
            $table->json('flujos_permitidos')->nullable(); // Tipos de trámite que puede procesar
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();

            // Índices
            $table->index(['tipo']);
            $table->index(['activo']);
            $table->index(['gerencia_padre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gerencias');
    }
};
