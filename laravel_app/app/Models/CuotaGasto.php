<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuotaGasto extends Model {
    use HasFactory;

    protected $fillable = ['gasto_id', 'numero_cuota', 'total_cuotas', 'monto', 'fecha_vencimiento', 'pagado'];

    public function gasto() {
        return $this->belongsTo(GastoMensual::class);
    }
}
