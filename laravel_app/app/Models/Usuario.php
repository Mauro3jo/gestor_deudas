<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Authenticatable {
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function tokens(): HasMany {
        return $this->hasMany(Token::class);
    }

    public function sueldos(): HasMany {
        return $this->hasMany(Sueldo::class);
    }

    public function tarjetasCredito(): HasMany {
        return $this->hasMany(TarjetaCredito::class);
    }

    public function gastos(): HasMany {
        return $this->hasMany(GastoMensual::class);
    }

    public function deudas(): HasMany {
        return $this->hasMany(Deuda::class);
    }

    public function pagosDeudas(): HasMany {
        return $this->hasMany(PagoDeuda::class);
    }
}
