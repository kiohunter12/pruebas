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
        Schema::create('tipo_tramites', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->string('codigo')->unique()->nullable();
            $table->foreignId('gerencia_id')->nullable()->constrained('gerencias');
            $table->json('documentos_requeridos')->nullable(); // tipos de documentos que requiere
            $table->decimal('costo', 10, 2)->default(0.00);
            $table->integer('tiempo_estimado_dias')->default(5);
            $table->boolean('requiere_pago')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_tramites');
    }
};
