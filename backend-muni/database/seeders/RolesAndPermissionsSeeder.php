<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos para el sistema de trámites municipales
        $permissions = [
            // Permisos de expedientes (siguiendo el patrón de las rutas API)
            'ver_expedientes',
            'registrar_expediente',
            'editar_expediente',
            'eliminar_expediente',
            'derivar_expediente',
            'emitir_resolucion',
            'rechazar_expediente',
            'finalizar_expediente',
            'archivar_expediente',
            'subir_documento',
            'eliminar_documento',
            'ver_todos_expedientes', // Para ver expedientes de todas las gerencias
            
            // Permisos de usuarios
            'gestionar_usuarios',
            'crear_usuarios',
            'editar_usuarios',
            'eliminar_usuarios',
            'asignar_roles',
            'gestionar_permisos',
            'ver_todos_usuarios',
            
            // Permisos de gerencias
            'gestionar_gerencias',
            'crear_gerencias',
            'editar_gerencias',
            'eliminar_gerencias',
            'asignar_usuarios_gerencia',
            
            // Permisos de procedimientos TUPA
            'gestionar_procedimientos',
            'crear_procedimientos',
            'editar_procedimientos',
            'eliminar_procedimientos',
            
            // Permisos de tipos de trámite
            'gestionar_tipos_tramite',
            'crear_tipos_tramite',
            'editar_tipos_tramite',
            'eliminar_tipos_tramite',
            'activar_tipos_tramite',
            'ver_tipos_tramite',
            
            // Permisos de reportes y estadísticas
            'ver_reportes',
            'exportar_datos',
            'ver_estadisticas_gerencia',
            'ver_estadisticas_sistema',
            
            // Permisos de configuración
            'configurar_sistema',
            'gestionar_respaldos',
            'ver_logs',
            
            // Permisos de notificaciones
            'enviar_notificaciones',
            'gestionar_notificaciones',
            
            // Permisos de pagos
            'gestionar_pagos',
            'confirmar_pagos',
            'ver_pagos',
            
            // Permisos de quejas y reclamos
            'gestionar_quejas',
            'responder_quejas',
            'escalar_quejas',
            
            // Permisos de gestión de flujos de trabajo
            'crear_reglas_flujo',
            'editar_reglas_flujo',
            'eliminar_reglas_flujo',
            'ver_reglas_flujo',
            'activar_desactivar_reglas',
            'crear_etapas_flujo',
            'editar_etapas_flujo',
            'eliminar_etapas_flujo',
            'ver_etapas_flujo',
            
            // Permisos de workflows personalizables
            'gestionar_workflows',
            'crear_workflows',
            'editar_workflows',
            'eliminar_workflows',
            'ver_workflows',
            'activar_workflows',
            'clonar_workflows'
        ];

        // Añadir permisos específicos de control de trámites (aprobar/rechazar/observar)
        $permissions = array_merge($permissions, [
            'aprobar_tramite',
            'rechazar_tramite',
            'observar_tramite'
        ]);

        // Crear todos los permisos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Crear roles y asignar permisos
        
        // ROL: Superadministrador
        $superAdmin = Role::firstOrCreate(['name' => 'superadministrador', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all()); // Todos los permisos

        // ROL: Administrador
        $admin = Role::firstOrCreate(['name' => 'administrador', 'guard_name' => 'web']);
        $admin->givePermissionTo([
            'ver_expedientes',
            'registrar_expediente',
            'editar_expediente',
            'derivar_expediente',
            'emitir_resolucion',
            'rechazar_expediente',
            'finalizar_expediente',
            'archivar_expediente',
            'subir_documento',
            'ver_todos_expedientes',
            'gestionar_usuarios',
            'crear_usuarios',
            'editar_usuarios',
            'asignar_roles',
            'gestionar_permisos',
            'ver_todos_usuarios',
            'gestionar_gerencias',
            'gestionar_procedimientos',
            'gestionar_tipos_tramite',
            'crear_tipos_tramite',
            'editar_tipos_tramite',
            'eliminar_tipos_tramite',
            'activar_tipos_tramite',
            'ver_tipos_tramite',
            'ver_reportes',
            'exportar_datos',
            'ver_estadisticas_gerencia',
            'ver_estadisticas_sistema',
            'enviar_notificaciones',
            'gestionar_notificaciones',
            'gestionar_pagos',
            'confirmar_pagos',
            'ver_pagos',
            'gestionar_quejas',
            'responder_quejas',
            'escalar_quejas',
            'gestionar_workflows',
            'crear_workflows',
            'editar_workflows',
            'eliminar_workflows',
            'ver_workflows',
            'activar_workflows',
            'clonar_workflows'
        ]);

        // ROL: Jefe de Gerencia
        $jefeGerencia = Role::firstOrCreate(['name' => 'jefe_gerencia', 'guard_name' => 'web']);
        $jefeGerencia->givePermissionTo([
            'ver_expedientes',
            'registrar_expediente',
            'editar_expediente',
            'derivar_expediente',
            'emitir_resolucion',
            'rechazar_expediente',
            'finalizar_expediente',
            'subir_documento',
            'crear_usuarios',
            'editar_usuarios',
            'asignar_usuarios_gerencia',
            'gestionar_tipos_tramite',
            'crear_tipos_tramite',
            'editar_tipos_tramite',
            'eliminar_tipos_tramite',
            'activar_tipos_tramite',
            'ver_tipos_tramite',
            'ver_reportes',
            'ver_estadisticas_gerencia',
            'enviar_notificaciones',
            'gestionar_pagos',
            'confirmar_pagos',
            'ver_pagos',
            'gestionar_quejas',
            'responder_quejas',
            'crear_reglas_flujo',
            'editar_reglas_flujo',
            'eliminar_reglas_flujo',
            'ver_reglas_flujo',
            'activar_desactivar_reglas',
            'crear_etapas_flujo',
            'editar_etapas_flujo',
            'eliminar_etapas_flujo',
            'ver_etapas_flujo',
            'gestionar_workflows',
            'crear_workflows',
            'editar_workflows',
            'ver_workflows',
            'activar_workflows',
            'clonar_workflows',
            // Permisos de control de trámites
            'aprobar_tramite',
            'rechazar_tramite',
            'observar_tramite'
        ]);

        // ROL: Gerente (antes Funcionario)
        $gerente = Role::firstOrCreate(['name' => 'gerente', 'guard_name' => 'web']);
        $gerente->givePermissionTo([
            'ver_expedientes',
            'registrar_expediente',
            'editar_expediente',
            'derivar_expediente',
            'subir_documento',
            'ver_reportes',
            'ver_estadisticas_gerencia',
            'enviar_notificaciones',
            'ver_pagos',
            'gestionar_quejas',
            'responder_quejas',
            // Control de trámites
            'aprobar_tramite',
            'rechazar_tramite',
            'observar_tramite'
        ]);

        // ROL: Subgerente (antes Funcionario Junior)
        $subgerente = Role::firstOrCreate(['name' => 'subgerente', 'guard_name' => 'web']);
        $subgerente->givePermissionTo([
            'ver_expedientes',
            'editar_expediente',
            'subir_documento',
            'enviar_notificaciones',
            'ver_pagos',
            'gestionar_quejas',
            // Control de trámites
            'aprobar_tramite',
            'rechazar_tramite',
            'observar_tramite'
        ]);

        // ROL: Ciudadano
        $ciudadano = Role::firstOrCreate(['name' => 'ciudadano', 'guard_name' => 'web']);
        $ciudadano->givePermissionTo([
            'registrar_expediente',
            'ver_expedientes', // Solo sus propios expedientes
            'subir_documento', // Solo a sus expedientes
            'ver_pagos', // Solo sus pagos
            'gestionar_quejas' // Solo crear quejas sobre sus expedientes
        ]);

        // ROL: Supervisor
        $supervisor = Role::firstOrCreate(['name' => 'supervisor', 'guard_name' => 'web']);
        $supervisor->givePermissionTo([
            'ver_expedientes',
            'editar_expediente',
            'derivar_expediente',
            'emitir_resolucion',
            'rechazar_expediente',
            'finalizar_expediente',
            'subir_documento',
            'ver_reportes',
            'ver_estadisticas_gerencia',
            'enviar_notificaciones',
            'gestionar_pagos',
            'confirmar_pagos',
            'ver_pagos',
            'gestionar_quejas',
            'responder_quejas',
            'escalar_quejas',
            'ver_workflows',
            // Control de trámites
            'aprobar_tramite',
            'rechazar_tramite',
            'observar_tramite'
        ]);

        
    }
}
