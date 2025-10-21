<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gerencias', function (Blueprint $table) {
            $table->unsignedBigInteger('responsable_id')->nullable()->after('descripcion');
            $table->string('cargo_responsable')->nullable()->after('responsable_id');
            
            $table->foreign('responsable_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('gerencias', function (Blueprint $table) {
            $table->dropForeign(['responsable_id']);
            $table->dropColumn(['responsable_id', 'cargo_responsable']);
        });
    }
};