<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'nombre',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function egresos()
    {
        return $this->hasMany(Egreso::class);
    }
}
