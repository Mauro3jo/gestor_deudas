<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model {
    use HasFactory;

    protected $fillable = ['usuario_id', 'monto', 'descripcion', 'fecha_vencimiento', 'tarjeta_id'];

    public function usuario() {
        return $this->belongsTo(Usuario::class);
    }

    public function tarjetaCredito() {
        return $this->belongsTo(TarjetaCredito::class, 'tarjeta_id');
    }

    public function pagos() {
        return $this->hasMany(PagoDeuda::class);
    }
}
