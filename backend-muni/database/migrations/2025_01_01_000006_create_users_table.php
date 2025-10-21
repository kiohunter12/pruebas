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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('dni', 8)->unique()->nullable();
            $table->string('telefono', 15)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('activo')->default(true);
            $table->foreignId('gerencia_id')->nullable()->constrained()->onDelete('set null'); // Relación con gerencia
            $table->rememberToken();
            $table->timestamps();

            // Índices
            $table->index(['gerencia_id']);
            $table->index(['dni']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
