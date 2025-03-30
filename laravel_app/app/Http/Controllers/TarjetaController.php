<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarjeta;

class TarjetaController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->usuario;

        $tarjetas = Tarjeta::where('usuario_id', $usuario->id)->get();

        return response()->json($tarjetas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $usuario = $request->usuario;

        $tarjeta = Tarjeta::create([
            'nombre' => $request->nombre,
            'usuario_id' => $usuario->id,
        ]);

        return response()->json($tarjeta, 201);
    }
}
