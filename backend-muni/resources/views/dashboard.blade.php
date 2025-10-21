@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        Bienvenido, {{ Auth::user()->name }}
                    </h1>
                    {{-- Mostrar rol y gerencia según permisos --}}
                    <div class="mt-2 flex flex-wrap gap-2">
                        @role('superadministrador')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-crown mr-1"></i> Super Administrador
                            </span>
                        @endrole
                        
                        @role('administrador')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-user-shield mr-1"></i> Administrador
                            </span>
                        @endrole
                        
                        @role('jefe_gerencia')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-users mr-1"></i> Jefe de Gerencia
                            </span>
                        @endrole
                        
                        @role('gerente')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-user-tie mr-1"></i> Gerente
                            </span>
                        @endrole
                        
                        @role('subgerente')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-user mr-1"></i> Subgerente
                            </span>
                        @endrole
                        
                        @role('ciudadano')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-user-circle mr-1"></i> Ciudadano
                            </span>
                        @endrole
                        
                        @if(auth()->user()->gerencia)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-building mr-1"></i> {{ auth()->user()->gerencia->nombre }}
                            </span>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Welcome Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="sm:flex sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Panel de Control
                                </h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                    Sistema de Gestión Documental - Municipalidad
                                </p>
                            </div>
                            <div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Sistema Activo</p>
                                        <p class="text-sm text-gray-500">Conectado</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats - Personalizado por rol -->
                        <div class="mt-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Estadísticas para Administradores --}}
                                @role('superadministrador|administrador')
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">Total Expedientes</div>
                                                <div class="text-2xl font-bold text-gray-900">{{ \App\Models\Expediente::count() }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">Usuarios Sistema</div>
                                                <div class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-purple-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">Gerencias</div>
                                                <div class="text-2xl font-bold text-gray-900">{{ \App\Models\Gerencia::count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endrole

                                {{-- Estadísticas para Jefes de Gerencia --}}
                                @role('jefe_gerencia')
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">Mi Gerencia</div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ auth()->user()->gerencia ? \App\Models\Expediente::where('gerencia_id', auth()->user()->gerencia_id)->count() : 0 }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-yellow-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">Pendientes</div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ auth()->user()->gerencia ? \App\Models\Expediente::where('gerencia_id', auth()->user()->gerencia_id)->where('estado', 'en_proceso')->count() : 0 }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">Mi Equipo</div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ auth()->user()->gerencia ? \App\Models\User::where('gerencia_id', auth()->user()->gerencia_id)->count() : 0 }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endrole

                                {{-- Estadísticas para Gerentes y Subgerentes --}}
                                @role('gerente|subgerente')
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">Asignados a Mí</div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ \App\Models\Expediente::where('responsable_id', auth()->id())->count() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-yellow-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">Por Procesar</div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ \App\Models\Expediente::where('responsable_id', auth()->id())->where('estado', 'en_proceso')->count() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">Completados</div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ \App\Models\Expediente::where('responsable_id', auth()->id())->where('estado', 'aprobado')->count() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endrole

                                {{-- Estadísticas para Ciudadanos --}}
                                @role('ciudadano')
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">Mis Trámites</div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ \App\Models\Expediente::where('solicitante_dni', auth()->user()->dni ?? '')->count() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-yellow-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">En Proceso</div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ \App\Models\Expediente::where('solicitante_dni', auth()->user()->dni ?? '')->whereIn('estado', ['pendiente', 'en_proceso', 'revision_tecnica', 'revision_legal'])->count() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-green-50 p-4 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-500">Completados</div>
                                                <div class="text-2xl font-bold text-gray-900">
                                                    {{ \App\Models\Expediente::where('solicitante_dni', auth()->user()->dni ?? '')->whereIn('estado', ['aprobado', 'finalizado'])->count() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endrole
                            </div>
                        </div>

                        <!-- Quick Actions - Personalizadas por rol -->
                        <div class="mt-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                
                                {{-- Acciones para Administradores --}}
                                @role('superadministrador|administrador')
                                    @can('gestionar_usuarios')
                                        <a href="{{ route('usuarios.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                            Gestionar Usuarios
                                        </a>
                                    @endcan
                                    
                                    @can('gestionar_gerencias')
                                        <a href="{{ route('gerencias.index') ?? '#' }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l7-3 7 3z"></path>
                                            </svg>
                                            Gestionar Gerencias
                                        </a>
                                    @endcan

                                    @can('gestionar_workflows')
                                        <a href="{{ route('workflows.index') ?? '#' }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Flujos de Trabajo
                                        </a>
                                    @endcan

                                    @can('ver_reportes_sistema')
                                        <a href="{{ route('reportes.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Reportes Sistema
                                        </a>
                                    @endcan
                                @endrole

                                {{-- Acciones para Jefes de Gerencia --}}
                                @role('jefe_gerencia')
                                    @can('gestionar_expedientes')
                                        <a href="{{ route('gerencia.tramites.mis-asignados') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Mis Trámites
                                        </a>
                                    @endcan

                                    @can('asignar_expedientes')
                                        <a href="{{ route('gerencia.tramites.mis-asignados') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Asignar Trámites
                                        </a>
                                    @endcan

                                    @can('crear_workflows')
                                        <a href="{{ route('workflows.create') ?? '#' }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Crear Flujo
                                        </a>
                                    @endcan

                                    <a href="{{ route('reportes.index') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Reportes Gerencia
                                    </a>
                                @endrole

                                {{-- Acciones para Gerentes y Subgerentes --}}
                                @role('gerente|subgerente')
                                    @can('ver_expedientes')
                                        <a href="{{ route('gerencia.tramites.mis-asignados') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Mis Asignados
                                        </a>
                                    @endcan

                                    @can('procesar_expedientes')
                                        <a href="#" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Procesar
                                        </a>
                                    @endcan

                                    <a href="#" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        Buscar Expediente
                                    </a>

                                    <a href="#" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        Observaciones
                                    </a>
                                @endrole

                                {{-- Acciones para Ciudadanos --}}
                                @role('ciudadano')
                                    @can('registrar_expediente')
                                        <a href="{{ route('ciudadano.tramites.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Nuevo Trámite
                                        </a>
                                    @endcan

                                    <a href="{{ route('ciudadano.tramites.mis-tramites') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Mis Trámites
                                    </a>

                                    <a href="{{ route('ciudadano.tramites.mis-tramites') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        Consultar Estado
                                    </a>

                                    <a href="{{ route('ciudadano.tramites.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-md text-sm font-medium transition duration-150 ease-in-out flex items-center justify-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        Pagos
                                    </a>
                                @endrole
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="mt-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Actividad Reciente</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-center text-gray-500 py-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <p class="mt-2 text-sm">No hay actividad reciente</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
