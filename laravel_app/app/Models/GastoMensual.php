<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastoMensual extends Model {
    use HasFactory;

    protected $fillable = ['usuario_id', 'monto', 'descripcion', 'fecha_gasto', 'tarjeta_id'];

    public function usuario() {
        return $this->belongsTo(Usuario::class);
    }

    public function tarjetaCredito() {
        return $this->belongsTo(TarjetaCredito::class, 'tarjeta_id');
    }
}
