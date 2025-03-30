<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->decimal('monto', 15, 2);
            $table->string('descripcion')->nullable();
            $table->date('fecha');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('ingresos');
    }
};
