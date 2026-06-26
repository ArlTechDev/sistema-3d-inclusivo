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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institucion_id')->nullable()->constrained('instituciones')->nullOnDelete();
            $table->enum('estado', ['Pendiente', 'En impresión', 'Completado', 'Rechazado'])->default('Pendiente');
            $table->date('fecha_solicitud');
            $table->decimal('total_gramos_pla', 8, 2)->default(0);
            $table->decimal('costo_total', 8, 2)->default(0);
            $table->string('gcode_path')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
