<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function registro(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|min:6',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['mensaje' => 'Usuario registrado'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        // Crear token
        $token = Str::random(60);
        Token::create([
            'usuario_id' => $usuario->id,
            'token' => $token
        ]);

        return response()->json(['token' => $token], 200);
    }

    public function perfil(Request $request)
    {
        return response()->json($request->usuario);
    }
}
