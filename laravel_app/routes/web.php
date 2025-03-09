<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SueldoController;
use App\Http\Controllers\TarjetaCreditoController;
use App\Http\Controllers\GastoMensualController;
use App\Http\Controllers\DeudaController;
use App\Http\Controllers\PagoDeudaController;
use App\Http\Controllers\CuotaGastoController;

// Autenticación de Usuarios
Route::post('/register', [UsuarioController::class, 'register']);
Route::post('/login', [UsuarioController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Usuario
    Route::post('/logout', [UsuarioController::class, 'logout']);
    Route::get('/user', [UsuarioController::class, 'userProfile']);

    // Sueldos
    Route::resource('/sueldos', SueldoController::class);

    // Tarjetas de crédito
    Route::resource('/tarjetas', TarjetaCreditoController::class);

    // Gastos
    Route::resource('/gastos', GastoMensualController::class);

    // Cuotas de Gastos (Filtradas por usuario y mes)
    Route::get('/cuotas/{usuario_id}', [CuotaGastoController::class, 'getCuotasPorMes']);

    // Deudas
    Route::resource('/deudas', DeudaController::class);
    
    // Obtener deudas por mes
    Route::get('/deudas/{usuario_id}/mes', [DeudaController::class, 'getDeudasPorMes']);
    
    // Pago de deudas
    Route::post('/deudas/{id}/pay', [DeudaController::class, 'pay']);
});

// Chequear conexión con la base de datos
Route::get('/check-database', [DatabaseController::class, 'checkConnection']);

require __DIR__.'/auth.php';
