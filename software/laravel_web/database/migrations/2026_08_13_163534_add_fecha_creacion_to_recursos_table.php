<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega la columna fecha_creacion (nullable) que el formulario de
     * recursos ya solicitaba pero no se persistía.
     */
    public function up(): void
    {
        Schema::table('recursos', function (Blueprint $table) {
            $table->date('fecha_creacion')->nullable()->after('tiempo_minutos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recursos', function (Blueprint $table) {
            $table->dropColumn('fecha_creacion');
        });
    }
};
