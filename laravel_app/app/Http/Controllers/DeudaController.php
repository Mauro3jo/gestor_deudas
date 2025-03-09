<?php

namespace App\Http\Controllers;

use App\Models\Deuda;
use App\Models\PagoDeuda;
use Illuminate\Http\Request;

class DeudaController extends Controller {
    /**
     * Registrar una nueva deuda con cuotas automáticas.
     */
    public function store(Request $request) {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'monto' => 'required|numeric',
            'descripcion' => 'required|string',
            'fecha_vencimiento' => 'required|date',
            'tarjeta_id' => 'nullable|exists:tarjetas_credito,id',
            'total_cuotas' => 'required|integer|min:1',
        ]);

        $deuda = Deuda::create($request->all());

        // Dividir la deuda en cuotas
        $cuota_monto = round($request->monto / $request->total_cuotas, 2);
        for ($i = 1; $i <= $request->total_cuotas; $i++) {
            PagoDeuda::create([
                'deuda_id' => $deuda->id,
                'usuario_id' => $request->usuario_id,
                'monto' => $cuota_monto,
                'fecha_pago' => date('Y-m-d', strtotime("+$i months", strtotime($request->fecha_vencimiento))),
                'numero_cuota' => $i,
                'total_cuotas' => $request->total_cuotas,
            ]);
        }

        return response()->json($deuda, 201);
    }

    /**
     * Obtener deudas por mes.
     */
    public function getDeudasPorMes(Request $request, $usuario_id) {
        $query = PagoDeuda::where('usuario_id', $usuario_id);

        if ($request->filled('mes')) {
            $query->whereMonth('fecha_pago', date('m', strtotime($request->mes)));
        }

        return response()->json($query->orderBy('fecha_pago', 'asc')->get());
    }
}
