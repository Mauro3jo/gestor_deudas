<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tarjetas_credito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('tipo_tarjeta'); // Ejemplo: Visa, MasterCard
            $table->string('ultimos_digitos', 4); // Solo los últimos 4 números
            $table->date('fecha_vencimiento');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('tarjetas_credito');
    }
};
