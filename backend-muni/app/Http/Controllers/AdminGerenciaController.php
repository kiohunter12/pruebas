<?php

namespace App\Http\Controllers;

use App\Models\Gerencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminGerenciaController extends Controller
{
    /**
     * Display a listing of gerencias.
     */
    public function index(): JsonResponse
    {
        $gerencias = Gerencia::with(['gerenciaPadre', 'subgerencias', 'users'])
                            ->activas()
                            ->ordenadas()
                            ->get();

        return response()->json([
            'success' => true,
            'data' => $gerencias
        ]);
    }

    /**
     * Store a newly created gerencia.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:gerencias,nombre',
            'codigo' => 'required|string|max:10|unique:gerencias,codigo',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|string|in:' . implode(',', array_keys(Gerencia::getTipos())),
            'gerencia_padre_id' => 'nullable|exists:gerencias,id',
            'flujos_permitidos' => 'nullable|array',
            'flujos_permitidos.*' => 'string|in:' . implode(',', array_keys(\App\Models\Expediente::getTiposTramite())),
            'orden' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Validar que si es subgerencia, tenga gerencia padre
        if ($request->tipo === Gerencia::TIPO_SUBGERENCIA && !$request->gerencia_padre_id) {
            return response()->json([
                'success' => false,
                'message' => 'Las subgerencias deben tener una gerencia padre'
            ], 422);
        }

        // Validar que si es subgerencia, la padre sea del tipo 'gerencia'
        if ($request->tipo === Gerencia::TIPO_SUBGERENCIA) {
            $gerenciaPadre = Gerencia::find($request->gerencia_padre_id);
            if (!$gerenciaPadre || $gerenciaPadre->tipo !== Gerencia::TIPO_GERENCIA) {
                return response()->json([
                    'success' => false,
                    'message' => 'La gerencia padre debe ser del tipo Gerencia'
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            $gerencia = Gerencia::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'tipo' => $request->tipo,
                'gerencia_padre_id' => $request->gerencia_padre_id,
                'flujos_permitidos' => $request->flujos_permitidos,
                'orden' => $request->orden ?? 0,
                'activo' => true,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gerencia creada exitosamente',
                'data' => $gerencia
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear gerencia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified gerencia.
     */
    public function show(Gerencia $gerencia): JsonResponse
    {
        $gerencia->load(['gerenciaPadre', 'subgerencias', 'users']);

        return response()->json([
            'success' => true,
            'data' => $gerencia
        ]);
    }

    /**
     * Update the specified gerencia.
     */
    public function update(Request $request, Gerencia $gerencia): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|string|max:255|unique:gerencias,nombre,' . $gerencia->id,
            'codigo' => 'sometimes|string|max:10|unique:gerencias,codigo,' . $gerencia->id,
            'descripcion' => 'nullable|string',
            'tipo' => 'sometimes|string|in:' . implode(',', array_keys(Gerencia::getTipos())),
            'gerencia_padre_id' => 'nullable|exists:gerencias,id',
            'flujos_permitidos' => 'nullable|array',
            'flujos_permitidos.*' => 'string|in:' . implode(',', array_keys(\App\Models\Expediente::getTiposTramite())),
            'orden' => 'nullable|integer|min:0',
            'activo' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $gerencia->update($request->only([
                'nombre', 'codigo', 'descripcion', 'tipo', 
                'gerencia_padre_id', 'flujos_permitidos', 'orden', 'activo'
            ]));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gerencia actualizada exitosamente',
                'data' => $gerencia
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar gerencia: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified gerencia.
     */
    public function destroy(Gerencia $gerencia): JsonResponse
    {
        // Verificar que no tenga expedientes asociados
        if ($gerencia->expedientes()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la gerencia porque tiene expedientes asociados'
            ], 400);
        }

        // Verificar que no tenga usuarios asociados
        if ($gerencia->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la gerencia porque tiene usuarios asociados'
            ], 400);
        }

        // Verificar que no tenga subgerencias
        if ($gerencia->subgerencias()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la gerencia porque tiene subgerencias asociadas'
            ], 400);
        }

        $gerencia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gerencia eliminada exitosamente'
        ]);
    }

    /**
     * Asignar usuario a gerencia.
     */
    public function asignarUsuario(Request $request, Gerencia $gerencia): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);
        
        // Verificar que el usuario no esté ya asignado a otra gerencia
        if ($user->gerencia_id && $user->gerencia_id !== $gerencia->id) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario ya está asignado a otra gerencia'
            ], 400);
        }

        $user->update(['gerencia_id' => $gerencia->id]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario asignado a gerencia exitosamente',
            'data' => $user
        ]);
    }

    /**
     * Remover usuario de gerencia.
     */
    public function removerUsuario(Request $request, Gerencia $gerencia): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);
        
        if ($user->gerencia_id !== $gerencia->id) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no pertenece a esta gerencia'
            ], 400);
        }

        $user->update(['gerencia_id' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario removido de gerencia exitosamente'
        ]);
    }

    /**
     * Obtener gerencias por tipo de trámite.
     */
    public function porTipoTramite(string $tipoTramite): JsonResponse
    {
        $gerencias = Gerencia::porFlujo($tipoTramite)
                            ->activas()
                            ->ordenadas()
                            ->get();

        return response()->json([
            'success' => true,
            'data' => $gerencias
        ]);
    }

    /**
     * Obtener estadísticas de gerencias.
     */
    public function estadisticas(): JsonResponse
    {
        $estadisticas = [
            'total_gerencias' => Gerencia::gerencias()->activas()->count(),
            'total_subgerencias' => Gerencia::subgerencias()->activas()->count(),
            'gerencias_con_usuarios' => Gerencia::has('users')->activas()->count(),
            'gerencias_con_expedientes' => Gerencia::has('expedientes')->activas()->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $estadisticas
        ]);
    }

    // ===================================================================
    // MÉTODOS DE GESTIÓN DE USUARIOS
    // ===================================================================

    /**
     * Listar todos los usuarios
     */
    public function getUsuarios(Request $request): JsonResponse
    {
        $query = User::with(['gerencia', 'roles', 'permissions']);

        // Filtros opcionales
        if ($request->has('gerencia_id')) {
            $query->where('gerencia_id', $request->gerencia_id);
        }

        if ($request->has('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('activo')) {
            $query->where('activo', $request->boolean('activo'));
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $usuarios = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $usuarios
        ]);
    }

    /**
     * Crear nuevo usuario
     */
    public function createUsuario(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'gerencia_id' => 'nullable|exists:gerencias,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
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
                'activo' => $request->get('activo', true)
            ]);

            // Asignar roles si se proporcionan
            if ($request->has('roles')) {
                $user->assignRole($request->roles);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'data' => $user->load(['gerencia', 'roles'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener usuario específico
     */
    public function getUsuario(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $user->load(['gerencia', 'roles', 'permissions'])
        ]);
    }

    /**
     * Actualizar usuario
     */
    public function updateUsuario(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6',
            'gerencia_id' => 'sometimes|nullable|exists:gerencias,id',
            'activo' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->only(['name', 'email', 'gerencia_id', 'activo']);
            
            if ($request->has('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente',
                'data' => $user->load(['gerencia', 'roles'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar usuario
     */
    public function deleteUsuario(User $user): JsonResponse
    {
        try {
            // Verificar si el usuario tiene expedientes asignados
            if ($user->expedientesAsignados()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el usuario porque tiene expedientes asignados'
                ], 400);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Asignar rol a usuario
     */
    public function asignarRol(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|exists:roles,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->assignRole($request->role);

            return response()->json([
                'success' => true,
                'message' => 'Rol asignado exitosamente',
                'data' => $user->load(['roles'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar rol: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remover rol de usuario
     */
    public function removerRol(User $user, Role $role): JsonResponse
    {
        try {
            $user->removeRole($role);

            return response()->json([
                'success' => true,
                'message' => 'Rol removido exitosamente',
                'data' => $user->load(['roles'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al remover rol: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Asignar permisos a usuario
     */
    public function asignarPermisos(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->givePermissionTo($request->permissions);

            return response()->json([
                'success' => true,
                'message' => 'Permisos asignados exitosamente',
                'data' => $user->load(['permissions'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar permisos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar contraseña de usuario
     */
    public function cambiarContraseñaUsuario(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar contraseña: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener roles disponibles
     */
    public function getRoles(): JsonResponse
    {
        $roles = Role::all();

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    /**
     * Obtener permisos disponibles
     */
    public function getPermissions(): JsonResponse
    {
        $permissions = Permission::all()->groupBy('guard_name');

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }
}
