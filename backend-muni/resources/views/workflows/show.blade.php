@extends('layouts.app')

@section('title', 'Workflow - ' . $workflow->nombre)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $workflow->nombre }}</h1>
                    <p class="mt-2 text-gray-600">Información detallada del workflow</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('workflows.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </a>
                    @can('editar_workflows')
                    <a href="{{ route('workflows.edit', $workflow) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Mensajes -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Información Básica -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Workflow</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Detalles y configuración</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $workflow->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $workflow->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Workflow
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200">
                        <dl>
                            @if($workflow->codigo)
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Código</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $workflow->codigo }}</dd>
                            </div>
                            @endif
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Gerencia Responsable</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <a href="{{ route('gerencias.show', $workflow->gerencia) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $workflow->gerencia->nombre }}
                                    </a>
                                </dd>
                            </div>
                            @if($workflow->descripcion)
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $workflow->descripcion }}</dd>
                            </div>
                            @endif
                            @if($workflow->creador)
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Creado por</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $workflow->creador->name }}</dd>
                            </div>
                            @endif
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $workflow->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $workflow->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Pasos del Workflow -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Pasos del Workflow</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Secuencia de pasos definidos ({{ $workflow->steps->count() }} pasos)</p>
                    </div>
                    
                    @if($workflow->steps->count() > 0)
                    <div class="border-t border-gray-200">
                        <div class="flow-root">
                            <ul class="divide-y divide-gray-200">
                                @foreach($workflow->steps->sortBy('orden') as $step)
                                <li class="px-4 py-4">
                                    <div class="flex items-center space-x-4">
                                        <!-- Número del Paso -->
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                                <span class="text-sm font-medium text-blue-600">{{ $step->orden }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Información del Paso -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $step->nombre }}</p>
                                                    @if($step->descripcion)
                                                    <p class="text-sm text-gray-500">{{ $step->descripcion }}</p>
                                                    @endif
                                                </div>
                                                
                                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                    @if($step->usuarioResponsable ?? $step->usuario_responsable_id)
                                                    <div class="flex items-center">
                                                        <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        <span>{{ $step->usuarioResponsable->name ?? 'Usuario ID: ' . $step->usuario_responsable_id }}</span>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($step->tiempo_limite_dias ?? $step->dias_limite)
                                                    <div class="flex items-center">
                                                        <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span>{{ $step->tiempo_limite_dias ?? $step->dias_limite }} días</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Flecha hacia el siguiente paso -->
                                        @if(!$loop->last)
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Documentos del Paso (Compacto) -->
                                    @if($step->documentos->count() > 0)
                                    <div class="mt-2 ml-12">
                                        <div class="text-xs text-gray-600 mb-1">
                                            📄 Documentos ({{ $step->documentos->count() }}):
                                        </div>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($step->documentos as $documento)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-800">
                                                <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $documento->tipoDocumento->nombre }}
                                                @if($documento->pivot->es_obligatorio ?? $documento->es_obligatorio)
                                                <span class="ml-1 text-red-600">*</span>
                                                @endif
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @else
                    <div class="border-t border-gray-200 text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin pasos definidos</h3>
                        <p class="mt-1 text-sm text-gray-500">Este workflow no tiene pasos configurados.</p>
                        @can('editar_workflows')
                        <div class="mt-6">
                            <a href="{{ route('workflows.edit', $workflow) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Configurar Pasos
                            </a>
                        </div>
                        @endcan
                    </div>
                    @endif
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Estadísticas -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Estadísticas</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Total de Pasos</span>
                                <span class="text-sm font-medium text-gray-900">{{ $stats['total_pasos'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Expedientes Procesados</span>
                                <span class="text-sm font-medium text-gray-900">{{ $stats['expedientes_procesados'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Expedientes Activos</span>
                                <span class="text-sm font-medium text-gray-900">{{ $stats['expedientes_activos'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Tiempo Promedio</span>
                                <span class="text-sm font-medium text-gray-900">{{ $stats['tiempo_promedio'] }} días</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                @canany(['editar_workflows', 'eliminar_workflows'])
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Acciones</h3>
                        
                        <div class="space-y-3">
                            @can('editar_workflows')
                            <a href="{{ route('workflows.edit', $workflow) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar Workflow
                            </a>
                            @endcan

                            @can('eliminar_workflows')
                            <form method="POST" action="{{ route('workflows.destroy', $workflow) }}" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        onclick="return confirm('¿Está seguro de eliminar este workflow? Esta acción no se puede deshacer.')">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar Workflow
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
                @endcanany
            </div>
        </div>
    </div>
</div>
@endsection