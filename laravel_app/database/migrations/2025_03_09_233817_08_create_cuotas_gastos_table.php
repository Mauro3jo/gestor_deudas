<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('cuotas_gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gasto_id')->constrained('gastos_mensuales')->onDelete('cascade');
            $table->integer('numero_cuota');
            $table->integer('total_cuotas');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_vencimiento');
            $table->boolean('pagado')->default(false);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('cuotas_gastos');
    }
};
