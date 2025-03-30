<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingreso;
use App\Models\Egreso;
use App\Models\CierreMensual;
use Carbon\Carbon;

class CierreMensualController extends Controller
{
    public function store(Request $request)
    {
        $usuario = $request->usuario;

        $mes = $request->mes;
        $anio = $request->anio;

        // Buscar ingresos y egresos activos de ese mes
        $ingresos = Ingreso::where('usuario_id', $usuario->id)
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->get();

        $egresos = Egreso::where('usuario_id', $usuario->id)
            ->where('estado', 'activo')
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->get();

        $totalIngresos = $ingresos->sum('monto');
        $totalEgresos = $egresos->sum('monto_cuota');
        $diferencia = $totalIngresos - $totalEgresos;

        $cierre = CierreMensual::create([
            'usuario_id' => $usuario->id,
            'mes' => $mes,
            'anio' => $anio,
            'total_ingresos' => $totalIngresos,
            'total_egresos' => $totalEgresos,
            'diferencia' => $diferencia,
            'cantidad_ingresos' => $ingresos->count(),
            'cantidad_egresos' => $egresos->count(),
        ]);

        // Marcar egresos como finalizados
        foreach ($egresos as $egreso) {
            $egreso->estado = 'finalizado';
            $egreso->save();
        }

        return response()->json(['mensaje' => 'Cierre mensual realizado correctamente.', 'cierre' => $cierre]);
    }public function historial(Request $request)
    {
        try {
            $usuario = $request->usuario;
    
            if (!$usuario) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }
    
            $cierres = CierreMensual::where('usuario_id', $usuario->id)
                ->orderByDesc('anio')
                ->orderByDesc('mes')
                ->get();
    
            return response()->json($cierres);
        } catch (\Throwable $e) {
            \Log::error('Error en historial de cierres:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
    
            return response()->json(['error' => 'Error interno al obtener el historial.'], 500);
        }
    }
    
    
}
