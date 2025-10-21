<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    // ===================================================================
    // GESTIÓN DE ROLES
    // ===================================================================

    /**
     * Listar todos los roles
     */
    public function getRoles(): JsonResponse
    {
        $roles = Role::with(['permissions'])->get();

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    /**
     * Crear nuevo rol
     */
    public function createRole(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'guard_name' => 'required|string|in:web,api',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name
            ]);

            // Asignar permisos si se proporcionan
            if ($request->has('permissions')) {
                $role->givePermissionTo($request->permissions);
            }

            return response()->json([
                'success' => true,
                'message' => 'Rol creado exitosamente',
                'data' => $role->load(['permissions'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear rol: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener rol específico
     */
    public function getRole(Role $role): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $role->load(['permissions'])
        ]);
    }

    /**
     * Actualizar rol
     */
    public function updateRole(Request $request, Role $role): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if ($request->has('name')) {
                $role->update(['name' => $request->name]);
            }

            // Sincronizar permisos
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return response()->json([
                'success' => true,
                'message' => 'Rol actualizado exitosamente',
                'data' => $role->load(['permissions'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar rol: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar rol
     */
    public function deleteRole(Role $role): JsonResponse
    {
        try {
            // Verificar si el rol está asignado a usuarios
            if ($role->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el rol porque está asignado a usuarios'
                ], 400);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rol eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar rol: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===================================================================
    // GESTIÓN DE PERMISOS
    // ===================================================================

    /**
     * Listar todos los permisos
     */
    public function getPermissions(): JsonResponse
    {
        $permissions = Permission::all()->groupBy('guard_name');

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    /**
     * Crear nuevo permiso
     */
    public function createPermission(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'required|string|in:web,api'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $permission = Permission::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permiso creado exitosamente',
                'data' => $permission
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear permiso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener permiso específico
     */
    public function getPermission($permissionName): JsonResponse
    {
        $permission = Permission::where('name', $permissionName)->first();
        
        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permiso no encontrado'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $permission
        ]);
    }

    /**
     * Actualizar permiso
     */
    public function updatePermission(Request $request, $permissionName): JsonResponse
    {
        $permission = Permission::where('name', $permissionName)->first();
        
        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permiso no encontrado'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|unique:permissions,name,' . $permission->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $permission->update($request->only(['name']));

            return response()->json([
                'success' => true,
                'message' => 'Permiso actualizado exitosamente',
                'data' => $permission
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar permiso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar permiso
     */
    public function deletePermission($permissionName): JsonResponse
    {
        $permission = Permission::where('name', $permissionName)->first();
        
        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permiso no encontrado'
            ], 404);
        }
        
        try {
            // Verificar si el permiso está asignado a roles o usuarios
            if ($permission->roles()->count() > 0 || $permission->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el permiso porque está asignado'
                ], 400);
            }

            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permiso eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar permiso: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===================================================================
    // MÉTODOS AUXILIARES
    // ===================================================================

    /**
     * Obtener estadísticas de roles y permisos
     */
    public function getStats(): JsonResponse
    {
        $stats = [
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'usuarios_con_roles' => \App\Models\User::whereHas('roles')->count(),
            'roles_mas_usados' => Role::withCount('users')
                                    ->orderBy('users_count', 'desc')
                                    ->limit(5)
                                    ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
