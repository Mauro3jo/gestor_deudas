<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function ingresos()
    {
        return $this->hasMany(Ingreso::class);
    }

    public function egresos()
    {
        return $this->hasMany(Egreso::class);
    }

    public function tarjetas()
    {
        return $this->hasMany(Tarjeta::class);
    }
}
