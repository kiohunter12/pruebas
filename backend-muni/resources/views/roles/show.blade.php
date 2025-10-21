@extends('layouts.app')

@section('title', 'Rol: ' . $role->name)

@section('content')
<div class="bg-white shadow">
    <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8">
        <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
            <div class="min-w-0 flex-1">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <div>
                                <a href="{{ route('roles.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="sr-only">Roles</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <a href="{{ route('roles.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Roles</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">{{ $role->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <div class="flex items-center mt-2">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                        {{ ucfirst($role->name) }}
                    </h2>
                    @if(in_array($role->name, ['super_admin', 'admin']))
                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Rol del Sistema
                        </span>
                    @endif
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Información detallada del rol y sus permisos
                </p>
            </div>
            <div class="mt-6 flex space-x-3 md:ml-4 md:mt-0">
                <a href="{{ route('roles.edit', $role) }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    Editar
                </a>
                <a href="{{ route('roles.index') }}" class="inline-flex items-center rounded-md bg-municipal-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-municipal-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-municipal-600">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                    Volver a Roles
                </a>
            </div>
        </div>
    </div>
</div>

<div class="py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Estadísticas del rol -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Permisos</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_permissions'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Usuarios Asignados</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['users_assigned'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Fecha Creación</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['created_date'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Última Actualización</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['last_updated'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Permisos del rol -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Permisos Asignados</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Lista de todos los permisos que tiene este rol.</p>
                </div>
                
                @if($role->permissions->count() > 0)
                    <div class="px-4 py-5 sm:p-6">
                        @php
                            // Definir categorías de módulos (igual que en WebController)
                            $modulosMap = [
                                'expedientes' => ['ver_expedientes', 'registrar_expediente', 'editar_expediente', 'derivar_expediente', 'emitir_resolucion', 'rechazar_expediente', 'finalizar_expediente', 'archivar_expediente', 'subir_documento', 'ver_todos_expedientes', 'asignar_expediente', 'reasignar_expediente', 'consultar_historial', 'exportar_expedientes', 'eliminar_expediente'],
                                'usuarios' => ['gestionar_usuarios', 'crear_usuarios', 'editar_usuarios', 'eliminar_usuarios', 'ver_todos_usuarios', 'asignar_usuarios_gerencia'],
                                'roles_permisos' => ['asignar_roles', 'gestionar_permisos'],
                                'gerencias' => ['gestionar_gerencias', 'crear_gerencias', 'editar_gerencias'],
                                'procedimientos' => ['gestionar_procedimientos', 'crear_procedimientos', 'eliminar_procedimientos'],
                                'tipos_tramite' => ['gestionar_tipos_tramite', 'crear_tipos_tramite', 'editar_tipos_tramite', 'eliminar_tipos_tramite', 'activar_tipos_tramite', 'ver_tipos_tramite'],
                                'reportes' => ['ver_reportes', 'exportar_datos', 'ver_estadisticas_gerencia', 'ver_estadisticas_sistema'],
                                'configuracion' => ['configurar_sistema', 'gestionar_respaldos', 'ver_logs'],
                                'notificaciones' => ['enviar_notificaciones', 'gestionar_notificaciones'],
                                'pagos' => ['gestionar_pagos', 'confirmar_pagos', 'ver_pagos'],
                                'quejas' => ['gestionar_quejas', 'responder_quejas', 'escalar_quejas'],
                                'workflows' => ['gestionar_workflows', 'crear_workflows', 'editar_workflows', 'eliminar_workflows', 'ver_workflows', 'activar_workflows', 'clonar_workflows', 'crear_reglas_flujo', 'editar_reglas_flujo', 'eliminar_reglas_flujo', 'ver_reglas_flujo', 'activar_desactivar_reglas', 'crear_etapas_flujo', 'editar_etapas_flujo', 'eliminar_etapas_flujo', 'ver_etapas_flujo'],
                            ];
                            
                            // Colores e iconos por módulo
                            $moduleColors = [
                                'expedientes' => 'green',
                                'usuarios' => 'blue',
                                'roles_permisos' => 'purple',
                                'gerencias' => 'indigo',
                                'procedimientos' => 'pink',
                                'tipos_tramite' => 'orange',
                                'reportes' => 'cyan',
                                'configuracion' => 'red',
                                'notificaciones' => 'yellow',
                                'pagos' => 'teal',
                                'quejas' => 'rose',
                                'workflows' => 'violet',
                                'otros' => 'gray'
                            ];
                            
                            // Agrupar permisos del rol por módulo
                            $permissionsByModule = collect();
                            foreach ($modulosMap as $modulo => $permisosNombres) {
                                $permisosModulo = $role->permissions->filter(function($permiso) use ($permisosNombres) {
                                    return in_array($permiso->name, $permisosNombres);
                                });
                                
                                if ($permisosModulo->count() > 0) {
                                    $permissionsByModule->put($modulo, $permisosModulo);
                                }
                            }
                            
                            // Agregar permisos no categorizados
                            $permisosCategorizados = collect($modulosMap)->flatten()->toArray();
                            $permisosOtros = $role->permissions->filter(function($permiso) use ($permisosCategorizados) {
                                return !in_array($permiso->name, $permisosCategorizados);
                            });
                            
                            if ($permisosOtros->count() > 0) {
                                $permissionsByModule->put('otros', $permisosOtros);
                            }
                        @endphp
                        
                        <div class="space-y-4">
                            @foreach($permissionsByModule as $module => $permissions)
                                @php
                                    $color = $moduleColors[$module] ?? 'gray';
                                @endphp
                                <div class="border border-gray-200 rounded-lg">
                                    <div class="bg-{{ $color }}-50 px-4 py-3 border-b border-{{ $color }}-100">
                                        <h4 class="text-sm font-medium text-gray-900 capitalize flex items-center">
                                            <span class="w-2 h-2 bg-{{ $color }}-500 rounded-full mr-2"></span>
                                            {{ str_replace('_', ' ', $module) }}
                                            <span class="ml-auto text-xs text-{{ $color }}-700 bg-white px-2 py-1 rounded-full font-semibold">
                                                {{ $permissions->count() }} permisos
                                            </span>
                                        </h4>
                                    </div>
                                    <div class="p-4 bg-white">
                                        <div class="grid grid-cols-1 gap-2">
                                            @foreach($permissions as $permission)
                                                <div class="flex items-center py-1">
                                                    <svg class="h-4 w-4 text-{{ $color }}-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="text-sm text-gray-700">{{ str_replace('_', ' ', $permission->name) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin permisos asignados</h3>
                        <p class="mt-1 text-sm text-gray-500">Este rol no tiene permisos asignados actualmente.</p>
                        <div class="mt-6">
                            <a href="{{ route('roles.edit', $role) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-municipal-600 hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Asignar Permisos
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Usuarios con este rol -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Usuarios con este Rol</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Lista de usuarios que tienen asignado este rol.</p>
                </div>
                
                @if($role->users->count() > 0)
                    <ul class="divide-y divide-gray-200">
                        @foreach($role->users as $user)
                            <li class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-municipal-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-municipal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                    <div class="ml-auto">
                                        @if($user->is_active ?? true)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactivo
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin usuarios asignados</h3>
                        <p class="mt-1 text-sm text-gray-500">No hay usuarios con este rol asignado actualmente.</p>
                        <div class="mt-6">
                            <a href="{{ route('usuarios.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-municipal-600 hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Gestionar Usuarios
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection