<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('cierres_mensuales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');

            $table->integer('mes');
            $table->integer('anio');

            $table->decimal('total_ingresos', 15, 2);
            $table->decimal('total_egresos', 15, 2);
            $table->decimal('diferencia', 15, 2);

            $table->integer('cantidad_ingresos');
            $table->integer('cantidad_egresos');

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('cierres_mensuales');
    }
};
