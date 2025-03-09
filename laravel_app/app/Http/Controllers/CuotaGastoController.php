<?php

namespace App\Http\Controllers;

use App\Models\CuotaGasto;
use Illuminate\Http\Request;

class CuotaGastoController extends Controller {
    /**
     * Obtener cuotas de gastos de un usuario por mes.
     */
    public function getCuotasPorMes(Request $request, $usuario_id) {
        $query = CuotaGasto::whereHas('gasto', function ($query) use ($usuario_id) {
            $query->where('usuario_id', $usuario_id);
        });

        if ($request->filled('mes')) {
            $query->whereMonth('fecha_vencimiento', date('m', strtotime($request->mes)));
        }

        return response()->json($query->orderBy('fecha_vencimiento', 'asc')->get());
    }
}
