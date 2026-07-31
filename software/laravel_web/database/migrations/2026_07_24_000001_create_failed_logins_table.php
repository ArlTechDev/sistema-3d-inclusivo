<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('failed_logins', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('email')->nullable();
            $table->timestamp('attempted_at');
            $table->timestamp('blocked_until')->nullable();
            $table->timestamps();

            $table->index('ip_address');
            $table->index('blocked_until');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_logins');
    }
};
