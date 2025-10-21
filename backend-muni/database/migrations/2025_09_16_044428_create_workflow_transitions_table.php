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
        Schema::create('workflow_transitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workflow_id');
            $table->unsignedBigInteger('from_step_id')->nullable(); // null para paso inicial
            $table->unsignedBigInteger('to_step_id');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('condicion')->nullable(); // condición para la transición
            $table->json('reglas')->nullable(); // reglas específicas de la transición
            $table->boolean('automatica')->default(false); // si se ejecuta automáticamente
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');
            $table->foreign('from_step_id')->references('id')->on('workflow_steps')->onDelete('cascade');
            $table->foreign('to_step_id')->references('id')->on('workflow_steps')->onDelete('cascade');
            $table->index(['workflow_id', 'from_step_id', 'to_step_id'], 'wf_transitions_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_transitions');
    }
};
