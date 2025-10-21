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
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->text('descripcion')->nullable();
            $table->string('tipo')->default('expediente'); // expediente, tramite, proceso
            $table->json('configuracion')->nullable(); // configuración adicional
            $table->boolean('activo')->default(true);
            $table->unsignedBigInteger('gerencia_id')->nullable(); // workflow específico de gerencia
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('gerencia_id')->references('id')->on('gerencias')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflows');
    }
};
