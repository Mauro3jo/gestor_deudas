<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'tarjeta_id',
        'nombre',
        'monto_cuota',
        'cuota_actual',
        'cuota_final',
        'tipo',
        'fecha',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function tarjeta()
    {
        return $this->belongsTo(Tarjeta::class);
    }
}
