<?php

namespace App\Http\Controllers;

use App\Models\Deuda;
use Illuminate\Http\Request;

class DeudaController extends Controller {
    /**
     * Obtener las deudas de un usuario agrupadas por mes y tarjeta.
     */
    public function getDeudasPorMes($usuario_id) {
        $deudas = Deuda::where('usuario_id', $usuario_id)
                       ->orderBy('fecha_vencimiento', 'asc')
                       ->get()
                       ->groupBy(function ($item) {
                           return date('Y-m', strtotime($item->fecha_vencimiento));
                       })
                       ->map(function ($items) {
                           return $items->groupBy('tarjeta_id');
                       });

        return response()->json($deudas);
    }

    /**
     * Registrar una nueva deuda.
     */
    public function store(Request $request) {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'monto' => 'required|numeric',
            'descripcion' => 'required|string',
            'fecha_vencimiento' => 'required|date',
            'tarjeta_id' => 'nullable|exists:tarjetas_credito,id',
        ]);

        $deuda = Deuda::create($request->all());
        return response()->json($deuda, 201);
    }

    /**
     * Editar una deuda existente.
     */
    public function update(Request $request, $id) {
        $deuda = Deuda::findOrFail($id);
        $deuda->update($request->all());
        return response()->json($deuda);
    }

    /**
     * Eliminar una deuda.
     */
    public function destroy($id) {
        Deuda::findOrFail($id)->delete();
        return response()->json(['message' => 'Deuda eliminada']);
    }
}
