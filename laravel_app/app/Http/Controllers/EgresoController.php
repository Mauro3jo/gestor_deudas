<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Egreso;
use Carbon\Carbon;

class EgresoController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->usuario;

        $egresos = Egreso::where('usuario_id', $usuario->id)->get();

        return response()->json($egresos);
    }

    public function store(Request $request)
    {
        $usuario = $request->usuario;

        $request->validate([
            'nombre' => 'required|string|max:255',
            'monto_cuota' => 'required|numeric',
            'cuota_actual' => 'required|integer|min:1',
            'cuota_final' => 'required|integer|min:1',
            'tipo' => 'required|in:único,cuotas,mensual',
            'fecha' => 'required|date',
            'tarjeta_id' => 'nullable|exists:tarjetas,id'
        ]);

        $dataBase = [
            'usuario_id'    => $usuario->id,
            'nombre'        => $request->nombre,
            'monto_cuota'   => $request->monto_cuota,
            'cuota_final'   => $request->cuota_final,
            'tipo'          => $request->tipo,
            'tarjeta_id'    => $request->tarjeta_id,
            'estado'        => 'activo',
        ];

        $fechaBase = Carbon::parse($request->fecha);

        // Si es único o mensual, se guarda solo uno
        if ($request->tipo === 'único' || $request->tipo === 'mensual') {
            $egreso = Egreso::create(array_merge($dataBase, [
                'fecha' => $fechaBase,
                'cuota_actual' => 1,
            ]));
            return response()->json($egreso);
        }

        // Si es por cuotas, generar múltiples egresos
        $egresos = [];

        for ($i = $request->cuota_actual; $i <= $request->cuota_final; $i++) {
            $nuevaFecha = (clone $fechaBase)->addMonths($i - $request->cuota_actual);
            $egresos[] = Egreso::create(array_merge($dataBase, [
                'cuota_actual' => $i,
                'fecha' => $nuevaFecha,
            ]));
        }

        return response()->json($egresos);
    }
    
}
