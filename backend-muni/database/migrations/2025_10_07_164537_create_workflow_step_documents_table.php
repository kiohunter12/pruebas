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
        Schema::create('workflow_step_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workflow_step_id');
            $table->unsignedBigInteger('tipo_documento_id');
            $table->boolean('es_obligatorio')->default(true);
            $table->integer('orden')->default(0);
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('workflow_step_id')
                  ->references('id')->on('workflow_steps')
                  ->onDelete('cascade');
                  
            $table->foreign('tipo_documento_id')
                  ->references('id')->on('tipo_documentos')
                  ->onDelete('cascade');
            
            // Índices para mejorar el rendimiento
            $table->index(['workflow_step_id', 'orden'], 'wsd_step_orden_idx');
            $table->unique(['workflow_step_id', 'tipo_documento_id'], 'wsd_step_doc_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_step_documents');
    }
};
