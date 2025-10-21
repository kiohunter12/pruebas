@extends('layouts.app')

@section('title', 'Detalles de Gerencia')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('gerencias.index') }}" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Gerencias</span>
                            <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 10h-1v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('gerencias.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Gerencias</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-900">{{ $gerencia->nombre }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="mt-4 flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $gerencia->nombre }}</h1>
                        <span class="ml-3 inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium
                            {{ $gerencia->tipo === 'gerencia' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                            {{ ucfirst($gerencia->tipo) }}
                        </span>
                        <span class="ml-2 inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium
                            {{ $gerencia->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $gerencia->activo ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                    <p class="mt-2 text-gray-600">{{ $gerencia->descripcion ?: 'Sin descripción disponible' }}</p>
                </div>

                <div class="flex items-center space-x-3">
                    @can('editar_gerencias')
                    <a href="{{ route('gerencias.edit', $gerencia) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                    @endcan
                    
                    <a href="{{ route('gerencias.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Volver al Listado
                    </a>
                </div>
            </div>
        </div>

        <!-- Información Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Información General</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Código</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $gerencia->codigo }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($gerencia->tipo) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $gerencia->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $gerencia->activo ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </dd>
                            </div>
                            @if($gerencia->gerencia_padre_id && $gerencia->gerenciaPadre)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Gerencia Padre</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('gerencias.show', $gerencia->gerenciaPadre) }}" 
                                       class="text-blue-600 hover:text-blue-500">
                                        {{ $gerencia->gerenciaPadre->nombre }}
                                    </a>
                                </dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $gerencia->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $gerencia->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                        
                        @if($gerencia->descripcion)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $gerencia->descripcion }}</dd>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="space-y-6">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Estadísticas</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                    <span class="text-sm text-gray-500">Usuarios</span>
                                </div>
                                <span class="text-2xl font-bold text-gray-900">{{ $gerencia->users_count ?? 0 }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4l-2 16h14L17 4" />
                                    </svg>
                                    <span class="text-sm text-gray-500">Subgerencias</span>
                                </div>
                                <span class="text-2xl font-bold text-gray-900">{{ $gerencia->subgerencias_count ?? 0 }}</span>
                            </div>
                            
                            @if(isset($expedientesCount))
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-500">Expedientes</span>
                                </div>
                                <span class="text-2xl font-bold text-gray-900">{{ $expedientesCount }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subgerencias -->
        @if($gerencia->subgerencias && $gerencia->subgerencias->count() > 0)
        <div class="mb-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Subgerencias</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Subgerencias que pertenecen a esta gerencia</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($gerencia->subgerencias as $subgerencia)
                        <div class="px-4 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4l-2 16h14L17 4" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $subgerencia->nombre }}</h4>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $subgerencia->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $subgerencia->activo ? 'Activa' : 'Inactiva' }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600">Código: {{ $subgerencia->codigo }}</p>
                                        @if($subgerencia->descripcion)
                                            <p class="mt-1 text-sm text-gray-500">{{ $subgerencia->descripcion }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('gerencias.show', $subgerencia) }}" 
                                       class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-gray-500 bg-white hover:bg-gray-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Usuarios Asignados -->
        @if($gerencia->users && $gerencia->users->count() > 0)
        <div class="mb-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Usuarios Asignados</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Personal que pertenece a esta gerencia</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($gerencia->users as $usuario)
                        <div class="px-4 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $usuario->name }}</h4>
                                            @if($usuario->roles->isNotEmpty())
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $usuario->roles->first()->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600">{{ $usuario->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $usuario->activo ?? true ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $usuario->activo ?? true ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Acciones Rápidas -->
        @canany(['editar_gerencias', 'eliminar_gerencias'])
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Acciones</h3>
            </div>
            <div class="px-4 py-4">
                <div class="flex flex-wrap gap-3">
                    @can('editar_gerencias')
                    <a href="{{ route('gerencias.edit', $gerencia) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Información
                    </a>

                    <form method="POST" action="{{ route('gerencias.toggle-status', $gerencia) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md {{ $gerencia->activo ? 'text-white bg-red-600 hover:bg-red-700' : 'text-white bg-green-600 hover:bg-green-700' }}"
                                onclick="return confirm('¿Está seguro de {{ $gerencia->activo ? 'desactivar' : 'activar' }} esta gerencia?')">
                            @if($gerencia->activo)
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                </svg>
                                Desactivar
                            @else
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Activar
                            @endif
                        </button>
                    </form>
                    @endcan

                    @can('gestionar_gerencias')
                    <a href="{{ route('gerencias.create') }}?tipo=subgerencia&gerencia_padre_id={{ $gerencia->id }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Crear Subgerencia
                    </a>
                    @endcan

                    @can('eliminar_gerencias')
                    <form method="POST" action="{{ route('gerencias.destroy', $gerencia) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                                onclick="return confirm('¿Está seguro de eliminar esta gerencia? Esta acción no se puede deshacer.')">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        @endcanany
    </div>
</div>
@endsection
