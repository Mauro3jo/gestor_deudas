<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
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

    // Sueldos (Agrupados por mes)
    Route::get('/sueldos/{usuario_id}', [SueldoController::class, 'getSueldosPorMes']);
    Route::resource('/sueldos', SueldoController::class)->except(['index', 'show']);

    // Tarjetas de crédito
    Route::get('/tarjetas/{usuario_id}', [TarjetaCreditoController::class, 'getTarjetas']);
    Route::resource('/tarjetas', TarjetaCreditoController::class)->except(['index', 'show']);

    // Gastos (Agrupados por mes y tarjeta)
    Route::get('/gastos/{usuario_id}', [GastoMensualController::class, 'getGastosPorMes']);
    Route::resource('/gastos', GastoMensualController::class)->except(['index', 'show']);

    // Deudas (Agrupadas por mes y tarjeta)
    Route::get('/deudas/{usuario_id}', [DeudaController::class, 'getDeudasPorMes']);
    Route::post('/deudas/{id}/pay', [DeudaController::class, 'pay']);
    Route::resource('/deudas', DeudaController::class)->except(['index', 'show']);

    // Cuotas de Gastos (Agrupadas por mes)
    Route::get('/cuotas/{usuario_id}', [CuotaGastoController::class, 'getCuotasPorMes']);

    // Pagos de Deudas (CRUD)
    Route::resource('/pagos-deudas', PagoDeudaController::class);
});

// Chequear conexión con la base de datos
Route::get('/check-database', [DatabaseController::class, 'checkConnection']);

require __DIR__.'/auth.php';
