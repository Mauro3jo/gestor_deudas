<?php

namespace App\Http\Controllers;

use App\Models\GastoMensual;
use App\Models\CuotaGasto;
use Illuminate\Http\Request;

class GastoMensualController extends Controller {
    /**
     * Obtener todos los gastos del usuario en un mes específico.
     */
    public function index(Request $request) {
        $query = GastoMensual::query();

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        if ($request->filled('mes')) {
            $query->whereMonth('fecha_gasto', date('m', strtotime($request->mes)));
        }

        return response()->json($query->orderBy('fecha_gasto', 'desc')->get());
    }

    /**
     * Crear un nuevo gasto con cuotas automáticas.
     */
    public function store(Request $request) {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'monto' => 'required|numeric',
            'descripcion' => 'required|string',
            'fecha_gasto' => 'required|date',
            'tarjeta_id' => 'nullable|exists:tarjetas_credito,id',
            'total_cuotas' => 'nullable|integer|min:1',
        ]);

        $gasto = GastoMensual::create($request->all());

        if ($request->total_cuotas > 1) {
            $cuota_monto = round($request->monto / $request->total_cuotas, 2);
            for ($i = 1; $i <= $request->total_cuotas; $i++) {
                CuotaGasto::create([
                    'gasto_id' => $gasto->id,
                    'numero_cuota' => $i,
                    'total_cuotas' => $request->total_cuotas,
                    'monto' => $cuota_monto,
                    'fecha_vencimiento' => date('Y-m-d', strtotime("+$i months", strtotime($request->fecha_gasto))),
                    'pagado' => false,
                ]);
            }
        }

        return response()->json($gasto, 201);
    }

    /**
     * Editar un gasto existente.
     */
    public function update(Request $request, $id) {
        $gasto = GastoMensual::findOrFail($id);
        $gasto->update($request->all());
        return response()->json($gasto);
    }

    /**
     * Eliminar un gasto.
     */
    public function destroy($id) {
        GastoMensual::findOrFail($id)->delete();
        return response()->json(['message' => 'Gasto eliminado']);
    }
}
