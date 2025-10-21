<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:superadministrador|administrador']);
    }

    /**
     * Agrupa los permisos por módulos
     */
    private function groupPermissionsByModule()
    {
        return Permission::all()->groupBy(function ($permission) {
            $name = $permission->name;
            
            // Agrupar por módulo basándose en palabras clave en el nombre del permiso
            if (str_contains($name, 'expediente')) {
                return 'expedientes';
            } elseif (str_contains($name, 'usuario')) {
                return 'usuarios';
            } elseif (str_contains($name, 'gerencia')) {
                return 'gerencias';
            } elseif (str_contains($name, 'procedimiento')) {
                return 'procedimientos';
            } elseif (str_contains($name, 'reporte') || str_contains($name, 'estadistica')) {
                return 'reportes';
            } elseif (str_contains($name, 'sistema') || str_contains($name, 'configurar') || str_contains($name, 'respaldo') || str_contains($name, 'logs')) {
                return 'sistema';
            } elseif (str_contains($name, 'notificacion')) {
                return 'notificaciones';
            } elseif (str_contains($name, 'pago')) {
                return 'pagos';
            } elseif (str_contains($name, 'queja')) {
                return 'quejas';
            } elseif (str_contains($name, 'workflow') || str_contains($name, 'flujo')) {
                return 'workflows';
            } else {
                return 'general';
            }
        });
    }

    /**
     * Display a listing of roles
     */
    public function index()
    {
        $roles = Role::with('permissions')
            ->withCount('users')
            ->paginate(10);

        $stats = [
            'total_roles' => Role::count(),
            'active_roles' => Role::count(), // Todos activos por defecto
            'total_permissions' => Permission::count(),
            'users_with_roles' => \App\Models\User::whereHas('roles')->count()
        ];

        return view('roles.index', compact('roles', 'stats'));
    }

    /**
     * Obtiene el nombre presentable de un permiso
     */
    private function getPermissionDisplayName($permission)
    {
        $names = [
            // Expedientes
            'ver_expedientes' => 'Ver expedientes',
            'registrar_expediente' => 'Registrar expedientes',
            'editar_expediente' => 'Editar expedientes',
            'eliminar_expediente' => 'Eliminar expedientes',
            'derivar_expediente' => 'Derivar expedientes',
            'emitir_resolucion' => 'Emitir resoluciones',
            'rechazar_expediente' => 'Rechazar expedientes',
            'finalizar_expediente' => 'Finalizar expedientes',
            'archivar_expediente' => 'Archivar expedientes',
            'subir_documento' => 'Subir documentos',
            'eliminar_documento' => 'Eliminar documentos',
            'ver_todos_expedientes' => 'Ver todos los expedientes',
            
            // Usuarios
            'gestionar_usuarios' => 'Gestionar usuarios',
            'crear_usuarios' => 'Crear usuarios',
            'editar_usuarios' => 'Editar usuarios',
            'eliminar_usuarios' => 'Eliminar usuarios',
            'asignar_roles' => 'Asignar roles',
            'gestionar_permisos' => 'Gestionar permisos',
            'ver_todos_usuarios' => 'Ver todos los usuarios',
            
            // Gerencias
            'gestionar_gerencias' => 'Gestionar gerencias',
            'crear_gerencias' => 'Crear gerencias',
            'editar_gerencias' => 'Editar gerencias',
            'eliminar_gerencias' => 'Eliminar gerencias',
            'asignar_usuarios_gerencia' => 'Asignar usuarios a gerencia',
            
            // Procedimientos
            'gestionar_procedimientos' => 'Gestionar procedimientos',
            'crear_procedimientos' => 'Crear procedimientos',
            'editar_procedimientos' => 'Editar procedimientos',
            'eliminar_procedimientos' => 'Eliminar procedimientos',
            
            // Reportes
            'ver_reportes' => 'Ver reportes',
            'exportar_datos' => 'Exportar datos',
            'ver_estadisticas_gerencia' => 'Ver estadísticas de gerencia',
            'ver_estadisticas_sistema' => 'Ver estadísticas del sistema',
            
            // Sistema
            'configurar_sistema' => 'Configurar sistema',
            'gestionar_respaldos' => 'Gestionar respaldos',
            'ver_logs' => 'Ver logs del sistema',
            
            // Notificaciones
            'enviar_notificaciones' => 'Enviar notificaciones',
            'gestionar_notificaciones' => 'Gestionar notificaciones',
            
            // Pagos
            'gestionar_pagos' => 'Gestionar pagos',
            'confirmar_pagos' => 'Confirmar pagos',
            'ver_pagos' => 'Ver pagos',
            
            // Quejas
            'gestionar_quejas' => 'Gestionar quejas',
            'responder_quejas' => 'Responder quejas',
            'escalar_quejas' => 'Escalar quejas',
            
            // Workflows
            'crear_reglas_flujo' => 'Crear reglas de flujo',
            'editar_reglas_flujo' => 'Editar reglas de flujo',
            'eliminar_reglas_flujo' => 'Eliminar reglas de flujo',
            'ver_reglas_flujo' => 'Ver reglas de flujo',
            'activar_desactivar_reglas' => 'Activar/desactivar reglas',
            'crear_etapas_flujo' => 'Crear etapas de flujo',
            'editar_etapas_flujo' => 'Editar etapas de flujo',
            'eliminar_etapas_flujo' => 'Eliminar etapas de flujo',
            'ver_etapas_flujo' => 'Ver etapas de flujo',
            'gestionar_workflows' => 'Gestionar workflows',
            'crear_workflows' => 'Crear workflows',
            'editar_workflows' => 'Editar workflows',
            'eliminar_workflows' => 'Eliminar workflows',
            'ver_workflows' => 'Ver workflows',
            'activar_workflows' => 'Activar workflows',
            'clonar_workflows' => 'Clonar workflows'
        ];

        return $names[$permission] ?? ucfirst(str_replace('_', ' ', $permission));
    }

    /**
     * Obtiene el nombre presentable de un módulo
     */
    private function getModuleDisplayName($module)
    {
        $names = [
            'expedientes' => 'Expedientes',
            'usuarios' => 'Usuarios',
            'gerencias' => 'Gerencias',
            'procedimientos' => 'Procedimientos TUPA',
            'reportes' => 'Reportes y Estadísticas',
            'sistema' => 'Configuración del Sistema',
            'notificaciones' => 'Notificaciones',
            'pagos' => 'Pagos',
            'quejas' => 'Quejas y Reclamos',
            'workflows' => 'Flujos de Trabajo',
            'general' => 'General'
        ];

        return $names[$module] ?? ucfirst($module);
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = $this->groupPermissionsByModule();
        $moduleNames = [];
        $permissionNames = [];
        
        foreach ($permissions->keys() as $module) {
            $moduleNames[$module] = $this->getModuleDisplayName($module);
        }
        
        foreach ($permissions->flatten() as $permission) {
            $permissionNames[$permission->name] = $this->getPermissionDisplayName($permission->name);
        }

        return view('roles.create', compact('permissions', 'moduleNames', 'permissionNames'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'web'
            ]);

            // Agregar campos personalizados si es necesario
            if (isset($validated['display_name']) || isset($validated['description'])) {
                // Puedes extender el modelo Role para incluir estos campos
                // Por ahora usaremos solo el name
            }

            if (isset($validated['permissions'])) {
                $permissions = Permission::whereIn('id', $validated['permissions'])->get();
                $role->syncPermissions($permissions);
            }

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Rol creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error al crear el rol: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified role
     */
    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        
        $stats = [
            'total_permissions' => $role->permissions->count(),
            'users_assigned' => $role->users->count(),
            'created_date' => $role->created_at->format('d/m/Y'),
            'last_updated' => $role->updated_at->format('d/m/Y H:i')
        ];

        return view('roles.show', compact('role', 'stats'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        $permissions = $this->groupPermissionsByModule();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        $moduleNames = [];
        $permissionNames = [];
        
        foreach ($permissions->keys() as $module) {
            $moduleNames[$module] = $this->getModuleDisplayName($module);
        }
        
        foreach ($permissions->flatten() as $permission) {
            $permissionNames[$permission->name] = $this->getPermissionDisplayName($permission->name);
        }

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions', 'moduleNames', 'permissionNames'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'name' => $validated['name']
            ]);

            if (isset($validated['permissions'])) {
                $permissions = Permission::whereIn('id', $validated['permissions'])->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Rol actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error al actualizar el rol: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        // Verificar que no sea un rol crítico del sistema
        if (in_array($role->name, ['superadministrador', 'administrador'])) {
            return back()->with('error', 'No se puede eliminar este rol del sistema.');
        }

        // Verificar que no tenga usuarios asignados
        if ($role->users()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un rol que tiene usuarios asignados.');
        }

        try {
            $role->delete();
            return redirect()->route('roles.index')
                ->with('success', 'Rol eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el rol: ' . $e->getMessage());
        }
    }

    /**
     * Get role data for AJAX requests
     */
    public function getData(Request $request)
    {
        $roles = Role::with('permissions')
            ->withCount('users')
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(10);

        return response()->json($roles);
    }

    /**
     * Assign permissions to role
     */
    public function assignPermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);

            return response()->json([
                'success' => true,
                'message' => 'Permisos asignados exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar permisos: ' . $e->getMessage()
            ], 500);
        }
    }
}