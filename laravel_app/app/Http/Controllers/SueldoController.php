<?php

namespace App\Http\Controllers;

use App\Models\Sueldo;
use Illuminate\Http\Request;

class SueldoController extends Controller {
    /**
     * Obtener todos los sueldos de un usuario agrupados por mes.
     */
    public function getSueldosPorMes($usuario_id) {
        $sueldos = Sueldo::where('usuario_id', $usuario_id)
                         ->orderBy('mes', 'asc')
                         ->get()
                         ->groupBy(function ($item) {
                             return date('Y-m', strtotime($item->mes));
                         });

        return response()->json($sueldos);
    }

    /**
     * Registrar un nuevo sueldo.
     */
    public function store(Request $request) {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'monto' => 'required|numeric',
            'mes' => 'required|date',
        ]);

        $sueldo = Sueldo::create($request->all());
        return response()->json($sueldo, 201);
    }

    /**
     * Editar un sueldo existente.
     */
    public function update(Request $request, $id) {
        $sueldo = Sueldo::findOrFail($id);
        $sueldo->update($request->all());
        return response()->json($sueldo);
    }

    /**
     * Eliminar un sueldo.
     */
    public function destroy($id) {
        Sueldo::findOrFail($id)->delete();
        return response()->json(['message' => 'Sueldo eliminado']);
    }
}
