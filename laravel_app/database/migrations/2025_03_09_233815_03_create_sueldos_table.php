<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('sueldos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->date('mes');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('sueldos');
    }
};
