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
        Schema::table('recursos', function (Blueprint $table) {
            $table->string('archivo_stl')->nullable()->after('url_gcode');
            $table->string('archivo_glb')->nullable()->after('archivo_stl');
            $table->enum('tipo_placa', ['integrada', 'separada', 'sin_placa'])->default('sin_placa')->after('archivo_glb');
            $table->unsignedInteger('placa_ancho')->nullable()->after('tipo_placa');
            $table->unsignedInteger('placa_alto')->nullable()->after('placa_ancho');
            $table->unsignedInteger('placa_z_altura')->nullable()->after('placa_alto');
            $table->unsignedInteger('max_caracteres')->nullable()->after('placa_z_altura');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recursos', function (Blueprint $table) {
            $table->dropColumn([
                'archivo_stl',
                'archivo_glb',
                'tipo_placa',
                'placa_ancho',
                'placa_alto',
                'placa_z_altura',
                'max_caracteres',
            ]);
        });
    }
};
