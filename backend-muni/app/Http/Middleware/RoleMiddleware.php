<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $roles - Roles permitidos separados por |
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado'
            ], 401);
        }

        $user = Auth::user();

        // Convertir roles string a array
        $allowedRoles = explode('|', $roles);

        // Verificar si el usuario tiene alguno de los roles permitidos
        $hasRole = false;
        foreach ($allowedRoles as $role) {
            if ($user->hasRole(trim($role))) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para acceder a este recurso',
                'required_roles' => $allowedRoles,
                'user_roles' => $user->roles->pluck('name')->toArray()
            ], 403);
        }

        return $next($request);
    }
}
