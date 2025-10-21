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
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->string('tupa_code')->unique(); // Código TUPA
            $table->string('name'); // Nombre del procedimiento
            $table->text('description'); // Descripción detallada
            $table->json('requirements'); // Requisitos como JSON
            $table->decimal('fee', 8, 2)->default(0); // Costo del trámite
            $table->integer('max_days'); // Días máximos para resolución
            $table->string('department'); // Departamento responsable
            $table->enum('silence_type', ['positive', 'negative'])->default('negative'); // Tipo de silencio administrativo
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['tupa_code']);
            $table->index(['department']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedures');
    }
};
