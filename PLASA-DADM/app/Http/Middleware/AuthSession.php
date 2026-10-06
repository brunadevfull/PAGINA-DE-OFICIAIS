<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->get('user_id')) {
            return response()->json(['success' => false, 'message' => 'Não autenticado'], 401);
        }

        return $next($request);
    }
}
