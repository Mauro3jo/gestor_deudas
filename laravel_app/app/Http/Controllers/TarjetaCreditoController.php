<?php

namespace App\Http\Controllers;

use App\Models\TarjetaCredito;
use Illuminate\Http\Request;

class TarjetaCreditoController extends Controller {
    /**
     * Obtener todas las tarjetas de un usuario.
     */
    public function getTarjetas($usuario_id) {
        return response()->json(
            TarjetaCredito::where('usuario_id', $usuario_id)->get()
        );
    }

    /**
     * Registrar una nueva tarjeta de crédito.
     */
    public function store(Request $request) {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'tipo_tarjeta' => 'required|string',
            'ultimos_digitos' => 'required|string|min:4|max:4',
            'fecha_vencimiento' => 'required|date',
        ]);

        $tarjeta = TarjetaCredito::create($request->all());
        return response()->json($tarjeta, 201);
    }

    /**
     * Editar una tarjeta existente.
     */
    public function update(Request $request, $id) {
        $tarjeta = TarjetaCredito::findOrFail($id);
        $tarjeta->update($request->all());
        return response()->json($tarjeta);
    }

    /**
     * Eliminar una tarjeta.
     */
    public function destroy($id) {
        TarjetaCredito::findOrFail($id)->delete();
        return response()->json(['message' => 'Tarjeta eliminada']);
    }
}
