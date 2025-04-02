<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingreso;
use App\Models\Egreso;
use App\Models\CierreMensual;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function mostrar(Request $request)
    {
        try {
            $usuario = $request->usuario;

            if (!$usuario) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            // 🔎 Agrupar egresos activos por mes y año
            $egresosActivos = Egreso::where('usuario_id', $usuario->id)
            ->where('estado', 'activo')
            ->with('tarjeta') // 👈 esto es clave
            ->get()
            ->groupBy(fn($e) => Carbon::parse($e->fecha)->format('Y-m'));
        
            $datosPorMes = [];

            foreach ($egresosActivos as $mesKey => $egresosMes) {
                [$anio, $mes] = explode('-', $mesKey);

                // ❌ Saltar si ya existe un cierre mensual para ese mes
                $yaCerrado = CierreMensual::where('usuario_id', $usuario->id)
                    ->where('mes', $mes)
                    ->where('anio', $anio)
                    ->exists();

                if ($yaCerrado) continue;

                // 📥 Obtener ingresos de ese mes
                $ingresos = Ingreso::where('usuario_id', $usuario->id)
                    ->whereYear('fecha', $anio)
                    ->whereMonth('fecha', $mes)
                    ->get();

                // 📦 Guardar los datos por mes
                $datosPorMes[] = [
                    'mes' => (int) $mes,
                    'anio' => (int) $anio,
                    'ingresos' => $ingresos->toArray(),
                    'egresos' => $egresosMes->toArray(),
                ];
            }

            // 💰 Diferencia acumulada de todos los cierres realizados
            $cierres = CierreMensual::where('usuario_id', $usuario->id)->get();
            $totalDiferencia = $cierres->sum('diferencia');

            return response()->json([
                'meses' => $datosPorMes,
                'diferencia_acumulada' => $totalDiferencia,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error en HomeController:', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return response()->json([
                'error' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
    }
}
