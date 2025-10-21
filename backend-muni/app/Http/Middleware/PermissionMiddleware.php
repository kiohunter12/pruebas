<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permissions - Permisos requeridos separados por |
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado'
            ], 401);
        }

        $user = Auth::user();

        // Convertir permisos string a array
        $requiredPermissions = explode('|', $permissions);

        // Verificar si el usuario tiene todos los permisos requeridos
        foreach ($requiredPermissions as $permission) {
            if (!$user->hasPermissionTo(trim($permission))) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes los permisos necesarios para acceder a este recurso',
                    'required_permissions' => $requiredPermissions,
                    'user_permissions' => $user->getAllPermissions()->pluck('name')->toArray()
                ], 403);
            }
        }

        return $next($request);
    }
}
