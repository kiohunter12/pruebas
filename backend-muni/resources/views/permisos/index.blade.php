@extends('layouts.app')

@section('title', 'Gestión de Permisos')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    <svg class="inline-block h-8 w-8 text-municipal-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Gestión de Permisos
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Administra los permisos del sistema organizados por módulos y funcionalidades
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <button type="button" 
                        onclick="openCreatePermissionModal()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-municipal-600 hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nuevo Permiso
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Permisos</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_permisos'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Permisos Activos</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['permisos_activos'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Módulos</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_modulos'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Permisos Críticos</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['permisos_criticos'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <!-- Búsqueda -->
                    <div>
                        <label for="permission-search" class="block text-sm font-medium text-gray-700">Buscar Permiso</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   id="permission-search" 
                                   class="focus:ring-municipal-500 focus:border-municipal-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                                   placeholder="Buscar por nombre..."
                                   onkeyup="filterPermissions()">
                        </div>
                    </div>

                    <!-- Filtro por Módulo -->
                    <div>
                        <label for="module-filter" class="block text-sm font-medium text-gray-700">Módulo</label>
                        <select id="module-filter" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm rounded-md"
                                onchange="filterPermissions()">
                            <option value="">Todos los módulos</option>
                            @foreach($permisosPorModulo as $modulo => $permisos)
                                <option value="{{ $modulo }}">{{ ucfirst(str_replace('_', ' ', $modulo)) }} ({{ $permisos->count() }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Tipo -->
                    <div>
                        <label for="type-filter" class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select id="type-filter" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm rounded-md"
                                onchange="filterPermissions()">
                            <option value="">Todos los tipos</option>
                            <option value="ver">Ver / Consultar</option>
                            <option value="crear">Crear / Registrar</option>
                            <option value="editar">Editar / Modificar</option>
                            <option value="eliminar">Eliminar</option>
                            <option value="gestionar">Gestionar / Administrar</option>
                            <option value="asignar">Asignar</option>
                            <option value="exportar">Exportar</option>
                        </select>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex items-end space-x-2">
                        <button type="button" 
                                onclick="clearFilters()"
                                class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions by Module -->
        <div class="space-y-6">
            @php
                $moduleColors = [
                    'users' => 'blue',
                    'expedientes' => 'green', 
                    'mesa' => 'orange',
                    'roles' => 'purple',
                    'reports' => 'indigo',
                    'config' => 'red',
                    'system' => 'gray',
                    'default' => 'blue'
                ];
                
                $moduleIcons = [
                    'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
                    'expedientes' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'mesa' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                    'roles' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                    'reports' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    'default' => 'M12 6v6m0 0v6m0-6h6m-6 0H6'
                ];
                
                $moduleDescriptions = [
                    'users' => 'Permisos relacionados con la administración de usuarios',
                    'expedientes' => 'Permisos para el manejo de expedientes y documentos',
                    'mesa' => 'Permisos para operaciones de mesa de partes',
                    'roles' => 'Permisos para gestión de roles y permisos',
                    'reports' => 'Permisos para generar y ver reportes',
                    'config' => 'Permisos de configuración del sistema',
                    'system' => 'Permisos del sistema y administración',
                    'default' => 'Otros permisos del sistema'
                ];
            @endphp
            
            @foreach($permisosPorModulo as $moduleName => $permisos)
                @php
                    $color = $moduleColors[$moduleName] ?? $moduleColors['default'];
                    $icon = $moduleIcons[$moduleName] ?? $moduleIcons['default'];
                    $description = $moduleDescriptions[$moduleName] ?? $moduleDescriptions['default'];
                @endphp
                
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="bg-gray-50 px-4 py-5 sm:px-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-{{ $color }}-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ ucfirst($moduleName) }}</h3>
                                    <p class="text-sm text-gray-500">{{ $description }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800">
                                    {{ $permisos->count() }} permisos
                                </span>
                                <button onclick="toggleModule('{{ $moduleName }}')" class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5 transform transition-transform duration-200" id="{{ $moduleName }}-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="{{ $moduleName }}-permissions" class="divide-y divide-gray-200">
                        @foreach($permisos as $permiso)
                            @php
                                $parts = explode('.', $permiso->name);
                                $action = $parts[1] ?? 'action';
                                $isCritical = in_array($action, ['delete', 'admin', 'system']);
                                $roleCount = $permiso->roles()->count();
                            @endphp
                            
                            <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-{{ $isCritical ? 'red' : 'green' }}-100">
                                                <svg class="h-4 w-4 text-{{ $isCritical ? 'red' : 'green' }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    @if($isCritical)
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    @endif
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center space-x-3">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $permiso->name }}</p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $isCritical ? 'red' : 'green' }}-100 text-{{ $isCritical ? 'red' : 'green' }}-800">
                                                    {{ $isCritical ? 'Crítico' : 'Activo' }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500">Permite {{ $action }} en {{ $moduleName }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500">Usado en {{ $roleCount }} roles</span>
                                        <div class="flex space-x-1">
                                            <button onclick="editPermission('{{ $permiso->name }}')" class="text-blue-600 hover:text-blue-900 p-1" title="Editar">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button onclick="togglePermission('{{ $permiso->name }}')" class="text-yellow-600 hover:text-yellow-900 p-1" title="Cambiar estado">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.382 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                </svg>
                                            </button>
                                            <button onclick="deletePermission('{{ $permiso->name }}')" class="text-red-600 hover:text-red-900 p-1" title="Eliminar">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-functions.js') }}"></script>
<script>
function toggleModule(moduleName) {
    const content = document.getElementById(moduleName + '-permissions');
    const chevron = document.getElementById(moduleName + '-chevron');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        chevron.classList.add('rotate-180');
    } else {
        content.classList.add('hidden');
        chevron.classList.remove('rotate-180');
    }
}

function filterPermissions() {
    const searchText = document.getElementById('permission-search').value.toLowerCase();
    const moduleFilter = document.getElementById('module-filter').value.toLowerCase();
    const typeFilter = document.getElementById('type-filter').value.toLowerCase();
    
    let visibleCount = 0;
    let totalCount = 0;
    
    // Iterar por cada módulo
    document.querySelectorAll('[id$="-permissions"]').forEach(moduleContainer => {
        const moduleName = moduleContainer.id.replace('-permissions', '');
        let moduleHasVisibleItems = false;
        
        // Filtrar permisos dentro del módulo
        moduleContainer.querySelectorAll('[class*="hover:bg-gray-50"]').forEach(permissionRow => {
            totalCount++;
            const permissionName = permissionRow.querySelector('.text-sm.font-medium').textContent.toLowerCase();
            
            let visible = true;
            
            // Filtro de búsqueda
            if (searchText && !permissionName.includes(searchText)) {
                visible = false;
            }
            
            // Filtro de módulo
            if (moduleFilter && moduleName !== moduleFilter) {
                visible = false;
            }
            
            // Filtro de tipo
            if (typeFilter) {
                const hasType = permissionName.includes(typeFilter);
                if (!hasType) {
                    visible = false;
                }
            }
            
            if (visible) {
                permissionRow.style.display = '';
                moduleHasVisibleItems = true;
                visibleCount++;
            } else {
                permissionRow.style.display = 'none';
            }
        });
        
        // Mostrar/ocultar módulo completo
        const moduleCard = moduleContainer.closest('.bg-white.shadow');
        if (moduleHasVisibleItems) {
            moduleCard.style.display = '';
            // Auto-expandir el módulo si hay filtros activos
            if (searchText || typeFilter) {
                moduleContainer.classList.remove('hidden');
                const chevron = document.getElementById(moduleName + '-chevron');
                if (chevron) {
                    chevron.classList.add('rotate-180');
                }
            }
        } else {
            moduleCard.style.display = 'none';
        }
    });
    
    // Actualizar contador de resultados
    updateResultsCounter(visibleCount, totalCount);
}

function clearFilters() {
    document.getElementById('permission-search').value = '';
    document.getElementById('module-filter').value = '';
    document.getElementById('type-filter').value = '';
    filterPermissions();
}

function updateResultsCounter(visible, total) {
    // Buscar o crear el contador
    let counter = document.getElementById('results-counter');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'results-counter';
        counter.className = 'bg-blue-50 border border-blue-200 rounded-lg px-4 py-2 mb-4 text-sm text-blue-800';
        
        const container = document.querySelector('.space-y-6');
        container.parentNode.insertBefore(counter, container);
    }
    
    if (visible === total) {
        counter.style.display = 'none';
    } else {
        counter.style.display = 'block';
        counter.innerHTML = `
            <svg class="inline-block h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Mostrando <strong>${visible}</strong> de <strong>${total}</strong> permisos
        `;
    }
}

// Event listeners para actualizar vista previa del nombre
document.addEventListener('DOMContentLoaded', function() {
    const moduleSelect = document.getElementById('permission-module');
    const actionSelect = document.getElementById('permission-action');
    
    if (moduleSelect && actionSelect) {
        moduleSelect.addEventListener('change', updatePermissionPreview);
        actionSelect.addEventListener('change', updatePermissionPreview);
    }
    
    // Inicializar contador de resultados
    filterPermissions();
});
</script>
@endpush

@include('permisos.partials.create-modal')