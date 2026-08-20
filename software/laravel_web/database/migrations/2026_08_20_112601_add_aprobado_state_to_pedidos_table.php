<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado 
                ENUM('Pendiente','Aprobado','En impresión','Completado','Rechazado') 
                DEFAULT 'Pendiente'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado 
                ENUM('Pendiente','En impresión','Completado','Rechazado') 
                DEFAULT 'Pendiente'");
        }
    }
};
