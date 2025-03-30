<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('egresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('tarjeta_id')->nullable()->constrained('tarjetas')->onDelete('set null');
            $table->string('nombre');
            $table->decimal('monto_cuota', 15, 2);
            $table->integer('cuota_actual')->nullable();
            $table->integer('cuota_final')->nullable();
            $table->enum('tipo', ['único', 'cuotas', 'mensual']);
            $table->date('fecha');
            $table->enum('estado', ['activo', 'finalizado'])->default('activo');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('egresos');
    }
};
