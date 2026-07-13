<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->date('fecha_llegada')->nullable()->after('estado');
            $table->string('hora_llegada')->nullable()->after('fecha_llegada');
            $table->date('fecha_retiro')->nullable()->after('hora_llegada');
            $table->string('hora_retiro')->nullable()->after('fecha_retiro');
        });

        // Sumamos "cancelada" como estado posible (el dueño puede cancelar su reserva).
        DB::statement("ALTER TABLE reservas MODIFY estado ENUM('pendiente', 'aceptada', 'rechazada', 'cancelada') DEFAULT 'pendiente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE reservas MODIFY estado ENUM('pendiente', 'aceptada', 'rechazada') DEFAULT 'pendiente'");

        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn(['fecha_llegada', 'hora_llegada', 'fecha_retiro', 'hora_retiro']);
        });
    }
};
