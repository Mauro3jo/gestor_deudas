<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarjetaCredito extends Model {
    use HasFactory;

    protected $fillable = ['usuario_id', 'tipo_tarjeta', 'ultimos_digitos', 'fecha_vencimiento'];

    public function usuario() {
        return $this->belongsTo(Usuario::class);
    }

    public function gastos() {
        return $this->hasMany(GastoMensual::class);
    }

    public function deudas() {
        return $this->hasMany(Deuda::class);
    }
}
