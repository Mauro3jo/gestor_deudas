<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'monto',
        'descripcion',
        'fecha',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
