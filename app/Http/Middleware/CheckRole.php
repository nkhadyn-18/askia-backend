<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // Vérifie que l'utilisateur connecté a bien l'un des rôles autorisés
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $utilisateur = $request->user();

        if (! $utilisateur || ! in_array($utilisateur->role, $roles)) {
            return response()->json([
                'message' => 'Accès refusé. Vous n\'avez pas les droits nécessaires.',
            ], 403);
        }

        return $next($request);
    }
}
