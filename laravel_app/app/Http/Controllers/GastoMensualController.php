<?php

namespace App\Http\Controllers;

use App\Models\GastoMensual;
use Illuminate\Http\Request;

class GastoMensualController extends Controller {
    /**
     * Obtener los gastos de un usuario agrupados por mes y tarjeta.
     */
    public function getGastosPorMes($usuario_id) {
        $gastos = GastoMensual::where('usuario_id', $usuario_id)
                              ->orderBy('fecha_gasto', 'asc')
                              ->get()
                              ->groupBy(function ($item) {
                                  return date('Y-m', strtotime($item->fecha_gasto));
                              })
                              ->map(function ($items) {
                                  return $items->groupBy('tarjeta_id');
                              });

        return response()->json($gastos);
    }

    /**
     * Registrar un nuevo gasto.
     */
    public function store(Request $request) {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'monto' => 'required|numeric',
            'descripcion' => 'required|string',
            'fecha_gasto' => 'required|date',
            'tarjeta_id' => 'nullable|exists:tarjetas_credito,id',
        ]);

        $gasto = GastoMensual::create($request->all());
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
