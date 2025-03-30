<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingreso;

class IngresoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'monto' => 'required|numeric',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
        ]);

        $usuario = $request->usuario;

        $ingreso = Ingreso::create([
            'usuario_id' => $usuario->id,
            'monto' => $request->monto,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
        ]);

        return response()->json($ingreso);
    }
}
