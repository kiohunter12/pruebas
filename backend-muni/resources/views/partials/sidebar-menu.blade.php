@php
    $currentRoute = request()->route()->getName();
    $mobileClass = $mobile ?? false ? 'text-base font-medium' : 'text-sm font-medium';
    $mobilePadding = $mobile ?? false ? 'px-2 py-2' : 'px-2 py-2';
@endphp

<!-- Dashboard -->
<a href="{{ route('dashboard') }}" 
   class="@if($currentRoute == 'dashboard') bg-municipal-100 text-municipal-700 border-r-4 border-municipal-600 @else text-gray-700 hover:bg-municipal-50 hover:text-municipal-700 @endif group flex items-center {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
    <svg class="@if($currentRoute == 'dashboard') text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
    </svg>
    Dashboard
</a>

<!-- Separador -->
<div class="border-t border-gray-200 my-3"></div>

@hasanyrole('superadministrador|administrador|gerente|supervisor|gerente')
    <!-- GESTIÓN DOCUMENTAL -->
    <div class="px-3 py-2">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión Documental</p>
    </div>

    {{-- Mesa de Partes ELIMINADO - Sistema usa Workflows automáticos --}}

    <!-- Expedientes -->
    <div x-data="{ open: {{ str_contains($currentRoute, 'expedientes') ? 'true' : 'false' }} }">
        <button @click="open = !open" 
                class="@if(str_contains($currentRoute, 'expedientes')) text-municipal-700 @else text-gray-700 hover:text-municipal-700 @endif group flex items-center w-full {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
            <svg class="@if(str_contains($currentRoute, 'expedientes')) text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Expedientes
            <svg class="ml-auto h-4 w-4 transform transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">
            <a href="{{ route('expedientes.index') }}" 
               class="@if($currentRoute == 'expedientes.index') text-municipal-600 bg-municipal-50 @else text-gray-600 hover:text-municipal-600 @endif group flex items-center py-2 px-2 text-sm rounded-md">
                Todos los Expedientes
            </a>
            <a href="{{ route('expedientes.create') }}" 
               class="@if($currentRoute == 'expedientes.create') text-municipal-600 bg-municipal-50 @else text-gray-600 hover:text-municipal-600 @endif group flex items-center py-2 px-2 text-sm rounded-md">
                Crear Expediente
            </a>
            <a href="{{ route('expedientes.pendientes') }}" 
               class="@if($currentRoute == 'expedientes.pendientes') text-municipal-600 bg-municipal-50 @else text-gray-600 hover:text-municipal-600 @endif group flex items-center py-2 px-2 text-sm rounded-md">
                Pendientes
            </a>
            <a href="{{ route('expedientes.proceso') }}" 
               class="@if($currentRoute == 'expedientes.proceso') text-municipal-600 bg-municipal-50 @else text-gray-600 hover:text-municipal-600 @endif group flex items-center py-2 px-2 text-sm rounded-md">
                En Proceso
            </a>
            <a href="{{ route('expedientes.finalizados') }}" 
               class="@if($currentRoute == 'expedientes.finalizados') text-municipal-600 bg-municipal-50 @else text-gray-600 hover:text-municipal-600 @endif group flex items-center py-2 px-2 text-sm rounded-md">
                Finalizados
            </a>
        </div>
    </div>

    <!-- Trámites -->
    <div x-data="{ open: {{ str_contains($currentRoute, 'tipos-tramite') || str_contains($currentRoute, 'tramites') ? 'true' : 'false' }} }">
        <button @click="open = !open" 
                class="@if(str_contains($currentRoute, 'tipos-tramite') || str_contains($currentRoute, 'tramites')) text-municipal-700 @else text-gray-700 hover:text-municipal-700 @endif group flex items-center w-full {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
            <svg class="@if(str_contains($currentRoute, 'tipos-tramite') || str_contains($currentRoute, 'tramites')) text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            Gestión de Trámites
            <svg class="ml-auto h-4 w-4 transform transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">
            <a href="{{ route('tipos-tramite.index') }}" 
               class="@if(str_contains($currentRoute, 'tipos-tramite')) text-municipal-600 bg-municipal-50 @else text-gray-600 hover:text-municipal-600 @endif group flex items-center py-2 px-2 text-sm rounded-md">
                Tipos de Trámites
            </a>
            <a href="{{ route('tramites.index') }}" 
               class="@if($currentRoute == 'tramites.index') text-municipal-600 bg-municipal-50 @else text-gray-600 hover:text-municipal-600 @endif group flex items-center py-2 px-2 text-sm rounded-md">
                Procesos de Trámites
            </a>
        </div>
    </div>

    <!-- Workflows -->
    @can('ver_workflows')
        <a href="{{ route('workflows.index') }}" 
           class="@if(str_contains($currentRoute, 'workflows')) bg-municipal-100 text-municipal-700 border-r-4 border-municipal-600 @else text-gray-700 hover:bg-municipal-50 hover:text-municipal-700 @endif group flex items-center {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
            <svg class="@if(str_contains($currentRoute, 'workflows')) text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Flujos de Trabajo
        </a>
    @endcan

    <!-- Gerencias -->
    @hasanyrole('superadministrador|administrador|gerente')
        <a href="{{ route('gerencias.index') }}" 
           class="@if(str_contains($currentRoute, 'gerencias')) bg-municipal-100 text-municipal-700 border-r-4 border-municipal-600 @else text-gray-700 hover:bg-municipal-50 hover:text-municipal-700 @endif group flex items-center {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
            <svg class="@if(str_contains($currentRoute, 'gerencias')) text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Gerencias
        </a>
    @endhasanyrole

    <!-- Separador -->
    <div class="border-t border-gray-200 my-3"></div>
@endhasanyrole

<!-- ADMINISTRACIÓN (solo roles administrativos) -->
@hasanyrole('superadministrador|administrador|gerente')
    <div class="border-t border-gray-200 my-3"></div>
    <div class="px-3 py-2">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Administración</p>
    </div>

    <!-- Usuarios -->
    <a href="{{ route('usuarios.index') }}" 
       class="@if($currentRoute == 'usuarios.index') bg-municipal-100 text-municipal-700 border-r-4 border-municipal-600 @else text-gray-700 hover:bg-municipal-50 hover:text-municipal-700 @endif group flex items-center {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
        <svg class="@if($currentRoute == 'usuarios.index') text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
        </svg>
        Usuarios
    </a>

    <!-- Roles -->
    <a href="{{ route('roles.index') }}" 
       class="@if($currentRoute == 'roles.index') bg-municipal-100 text-municipal-700 border-r-4 border-municipal-600 @else text-gray-700 hover:bg-municipal-50 hover:text-municipal-700 @endif group flex items-center {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
        <svg class="@if($currentRoute == 'roles.index') text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        Roles y Permisos
    </a>

    <!-- Permisos -->
    <a href="{{ route('permisos.index') }}" 
       class="@if($currentRoute == 'permisos.index') bg-municipal-100 text-municipal-700 border-r-4 border-municipal-600 @else text-gray-700 hover:bg-municipal-50 hover:text-municipal-700 @endif group flex items-center {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
        <svg class="@if($currentRoute == 'permisos.index') text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        Permisos
    </a>

    <!-- Configuración -->
    <a href="{{ route('configuracion.index') }}" 
       class="@if($currentRoute == 'configuracion.index') bg-municipal-100 text-municipal-700 border-r-4 border-municipal-600 @else text-gray-700 hover:bg-municipal-50 hover:text-municipal-700 @endif group flex items-center {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
        <svg class="@if($currentRoute == 'configuracion.index') text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        Configuración
    </a>
@endhasanyrole

<!-- Separador -->
<div class="border-t border-gray-200 my-3"></div>

@can('ver_reportes')
    <!-- REPORTES -->
    <div class="px-3 py-2">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Reportes y Análisis</p>
    </div>

    <!-- Reportes -->
    <div x-data="{ open: {{ str_contains($currentRoute, 'reportes') ? 'true' : 'false' }} }">
    <button @click="open = !open" 
            class="@if(str_contains($currentRoute, 'reportes')) text-municipal-700 @else text-gray-700 hover:text-municipal-700 @endif group flex items-center w-full {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
        <svg class="@if(str_contains($currentRoute, 'reportes')) text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        Reportes
        <svg class="ml-auto h-4 w-4 transform transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
    <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">
        <a href="{{ route('reportes.index') }}" 
           class="@if($currentRoute == 'reportes.index') text-municipal-600 bg-municipal-50 @else text-gray-600 hover:text-municipal-600 @endif group flex items-center py-2 px-2 text-sm rounded-md">
            Dashboard Reportes
        </a>
        <a href="{{ route('reportes.expedientes') }}" 
           class="@if($currentRoute == 'reportes.expedientes') text-municipal-600 bg-municipal-50 @else text-gray-600 hover:text-municipal-600 @endif group flex items-center py-2 px-2 text-sm rounded-md">
            Reportes de Expedientes
        </a>
        <a href="{{ route('reportes.tramites') }}" 
           class="@if($currentRoute == 'reportes.tramites') text-municipal-600 bg-municipal-50 @else text-gray-600 hover:text-municipal-600 @endif group flex items-center py-2 px-2 text-sm rounded-md">
            Reportes de Trámites
        </a>
        <a href="{{ route('reportes.tiempos') }}" 
           class="@if($currentRoute == 'reportes.tiempos') text-municipal-600 bg-municipal-50 @else text-gray-600 hover:text-municipal-600 @endif group flex items-center py-2 px-2 text-sm rounded-md">
            Tiempos de Respuesta
        </a>
    </div>
    </div>
@endcan

<!-- Separador -->
<div class="border-t border-gray-200 my-3"></div>

<!-- PERFIL -->
<div class="px-3 py-2">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Perfil</p>
</div>

<!-- Mi Perfil -->
<a href="{{ route('profile.show') }}" 
   class="@if($currentRoute == 'profile.show') bg-municipal-100 text-municipal-700 border-r-4 border-municipal-600 @else text-gray-700 hover:bg-municipal-50 hover:text-municipal-700 @endif group flex items-center {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
    <svg class="@if($currentRoute == 'profile.show') text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    Mi Perfil
</a>

<!-- Configuraciones -->
<a href="{{ route('settings') }}" 
   class="@if($currentRoute == 'settings') bg-municipal-100 text-municipal-700 border-r-4 border-municipal-600 @else text-gray-700 hover:bg-municipal-50 hover:text-municipal-700 @endif group flex items-center {{ $mobilePadding }} {{ $mobileClass }} rounded-md transition duration-200">
    <svg class="@if($currentRoute == 'settings') text-municipal-500 @else text-gray-400 group-hover:text-municipal-500 @endif mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
    </svg>
    Configuraciones
</a>