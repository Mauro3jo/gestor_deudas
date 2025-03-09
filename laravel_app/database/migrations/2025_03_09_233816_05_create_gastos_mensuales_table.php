<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('gastos_mensuales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->string('descripcion');
            $table->date('fecha_gasto');
            $table->foreignId('tarjeta_id')->nullable()->constrained('tarjetas_credito')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('gastos_mensuales');
    }
};
