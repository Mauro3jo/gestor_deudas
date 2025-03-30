<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Token;

class AuthToken
{
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');

        if (!$header) {
            return response()->json(['error' => 'Token no enviado'], 401);
        }

        // Permitir formato: "Bearer token" o directamente "token"
        $tokenValue = str_starts_with($header, 'Bearer ')
            ? substr($header, 7)
            : $header;

        $token = Token::where('token', $tokenValue)->first();

        if (!$token || !$token->usuario) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        // Inyectar usuario autenticado en el request
        $request->merge(['usuario' => $token->usuario]);

        return $next($request);
    }
}
