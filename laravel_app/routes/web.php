<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\CierreMensualController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TarjetaController;

use Inertia\Inertia;

// Página inicial redirige al login
Route::get('/', fn () => redirect('/login'));

// Rutas públicas de Inertia
Route::get('/registro', fn () => Inertia::render('Registro'));
Route::get('/login', fn () => Inertia::render('Login'));

// Vistas protegidas (se accede si el token es válido en frontend)
Route::get('/perfil', fn () => Inertia::render('Perfil'));
Route::get('/home', fn () => Inertia::render('Home'));
Route::get('/cerrar-mes', fn () => Inertia::render('CerrarMes'));

// Rutas públicas API
Route::post('/api/registro', [AuthController::class, 'registro']);
Route::post('/api/login', [AuthController::class, 'login']);

// Rutas protegidas API (requieren token)
Route::middleware('auth.token')->group(function () {

    // ✔️ API: Perfil
    Route::post('/api/perfil', [AuthController::class, 'perfil']); // CAMBIADO A POST

    // ✔️ API: Home (cargar datos)
    Route::post('/api/home', [HomeController::class, 'mostrar']); // YA ERA POST

    // ✔️ API: Tarjetas
    Route::post('/api/tarjetas', [TarjetaController::class, 'store']);
    Route::post('/api/tarjetas/listar', [TarjetaController::class, 'index']); // CAMBIADO A POST

    // ✔️ API: Cierres Mensuales
    Route::post('/api/cierres-mensuales', [CierreMensualController::class, 'store']);
    Route::post('/api/cierres-mensuales/historial', [CierreMensualController::class, 'historial']);
    Route::post('/api/cierres-mensuales/meses-activos', [CierreMensualController::class, 'mesesActivos']);

    // ✔️ API: Ingresos y Egresos
    Route::post('/api/ingresos', [IngresoController::class, 'store']);
    Route::post('/api/egresos', [EgresoController::class, 'store']);
});
