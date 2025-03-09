<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoDeuda extends Model {
    use HasFactory;

    protected $fillable = ['deuda_id', 'usuario_id', 'monto', 'fecha_pago', 'numero_cuota', 'total_cuotas'];

    public function deuda() {
        return $this->belongsTo(Deuda::class);
    }

    public function usuario() {
        return $this->belongsTo(Usuario::class);
    }
}
