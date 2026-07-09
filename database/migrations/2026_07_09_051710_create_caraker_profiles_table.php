<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
// Crea la tabla donde se guarda la información del perfil del cuidador.

    public function up(): void
    {
        Schema::create('caraker_profiles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
             // Relaciona el perfil con un usuario.

            $table->enum('vivienda', ['casa_patio', 'casa', 'quinta', 'depto']);
            $table->string('foto_hospedaje', 500)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('mascotas_propias')->nullable(); // texto libre: "Sí, tengo 2 gatos"

            $table->boolean('acepta_perro')->default(false);
            $table->boolean('acepta_gato')->default(false);
            $table->json('tamanos_aceptados')->nullable(); // ["Pequeño","Mediano"]

            $table->text('experiencia')->nullable();
            $table->boolean('declaracion')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caraker_profiles');
    }
};
