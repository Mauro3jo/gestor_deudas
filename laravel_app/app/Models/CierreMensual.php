<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreMensual extends Model
{
    use HasFactory;

    // 👇 Esta línea es clave para evitar el error de tabla no encontrada
    protected $table = 'cierres_mensuales';

    protected $fillable = [
        'usuario_id',
        'mes',
        'anio',
        'total_ingresos',
        'total_egresos',
        'diferencia',
        'cantidad_ingresos',
        'cantidad_egresos',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
