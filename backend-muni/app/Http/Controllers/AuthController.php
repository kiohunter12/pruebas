<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Login del usuario
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Verificar si el usuario está activo
            if (isset($user->estado) && $user->estado !== 'activo') {
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario inactivo'
                ], 403);
            }

            // Generar token
            $token = $user->createToken('auth-token')->plainTextToken;

            // Cargar relaciones de forma segura
            try {
                $user->load(['gerencia', 'roles', 'permissions']);
                
                // Obtener permisos del usuario de forma segura
                $permissions = [];
                if (method_exists($user, 'getAllPermissions')) {
                    $permissions = $user->getAllPermissions()->pluck('name')->toArray();
                }
            } catch (\Exception $e) {
                // Si falla cargar relaciones, continuar sin ellas
                $permissions = [];
            }

            // Actualizar último acceso si el campo existe
            try {
                if (in_array('ultimo_acceso', $user->getFillable())) {
                    $user->update(['ultimo_acceso' => now()]);
                }
            } catch (\Exception $e) {
                // Ignorar error si no se puede actualizar
            }

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->roles->first()?->name ?? 'usuario',
                        'gerencia' => $user->gerencia ? [
                            'id' => $user->gerencia->id,
                            'nombre' => $user->gerencia->nombre,
                            'tipo' => $user->gerencia->tipo ?? null
                        ] : null,
                        'permissions' => $permissions
                    ],
                    'token' => $token,
                    'permissions' => $permissions
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciales inválidas'
        ], 401);
    }

    /**
     * Logout del usuario
     */
    public function logout(Request $request)
    {
        try {
            // Revocar token actual
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
            }

            Auth::logout();

            return response()->json([
                'success' => true,
                'message' => 'Logout exitoso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en logout'
            ], 500);
        }
    }

    /**
     * Obtener información del usuario autenticado
     */
    public function user(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Cargar relaciones necesarias
            $user->load(['gerencia', 'roles', 'permissions']);

            // Obtener permisos del usuario
            $permissions = $user->getAllPermissions()->pluck('name')->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->first()?->name ?? 'usuario',
                    'gerencia' => $user->gerencia ? [
                        'id' => $user->gerencia->id,
                        'nombre' => $user->gerencia->nombre,
                        'tipo' => $user->gerencia->tipo
                    ] : null,
                    'permissions' => $permissions,
                    'ultimo_acceso' => $user->ultimo_acceso,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información del usuario'
            ], 500);
        }
    }

    /**
     * Registrar nuevo usuario
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
            'gerencia_id' => 'nullable|exists:gerencias,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'gerencia_id' => $request->gerencia_id,
                'estado' => 'activo'
            ]);

            // Asignar rol
            $user->assignRole($request->role);

            // Asignar permisos del rol
            $role = $user->roles->first();
            if ($role) {
                $permissions = $role->permissions->pluck('name')->toArray();
                $user->syncPermissions($permissions);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $request->role,
                    'gerencia_id' => $request->gerencia_id
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar usuario'
            ], 500);
        }
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Contraseña actual incorrecta'
            ], 400);
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contraseña cambiada exitosamente'
        ]);
    }

    /**
     * Verificar si el email está disponible
     */
    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email inválido'
            ], 422);
        }

        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'disponible' => !$exists
            ]
        ]);
    }

    /**
     * Refrescar token
     */
    public function refresh(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Revocar token actual
            $request->user()->currentAccessToken()->delete();

            // Generar nuevo token
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => [
                    'token' => $token
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al refrescar token'
            ], 500);
        }
    }
}
