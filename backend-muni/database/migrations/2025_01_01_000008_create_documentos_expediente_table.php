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
        Schema::create('documentos_expediente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('tipo_documento');
            $table->string('archivo');
            $table->string('extension');
            $table->bigInteger('tamaño');
            $table->string('mime_type');
            $table->foreignId('usuario_subio_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('requerido')->default(true);
            $table->boolean('aprobado')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Índices
            $table->index(['expediente_id']);
            $table->index(['tipo_documento']);
            $table->index(['usuario_subio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_expediente');
    }
};
