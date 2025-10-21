@extends('layouts.app')

@section('title', 'Ver Tipo de Trámite - ' . $tipoTramite->nombre)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $tipoTramite->nombre }}</h1>
                    <p class="mt-2 text-gray-600">Información detallada del tipo de trámite</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('tipos-tramite.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </a>
                    @can('editar_tipos_tramite')
                    <a href="{{ route('tipos-tramite.edit', $tipoTramite) }}" 
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
            <div class="lg:col-span-2">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Trámite</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">Detalles y configuración del tipo de trámite</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $tipoTramite->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $tipoTramite->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                                @if($tipoTramite->requiere_pago)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Requiere Pago
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Código</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $tipoTramite->codigo }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Gerencia Responsable</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <a href="{{ route('gerencias.show', $tipoTramite->gerencia) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $tipoTramite->gerencia->nombre }}
                                    </a>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Costo</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="text-lg font-semibold">S/ {{ number_format($tipoTramite->costo, 2) }}</span>
                                    @if($tipoTramite->costo == 0)
                                        <span class="ml-2 text-green-600">(Gratuito)</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Tiempo Estimado</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="inline-flex items-center">
                                        <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $tipoTramite->tiempo_estimado_dias }} día(s)
                                    </span>
                                </dd>
                            </div>
                            @if($tipoTramite->descripcion)
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $tipoTramite->descripcion }}</dd>
                            </div>
                            @endif
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $tipoTramite->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $tipoTramite->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Documentos Requeridos -->
                @if($tipoTramite->documentos_requeridos && count($tipoTramite->documentos_requeridos) > 0)
                <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Documentos Requeridos</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Lista de documentos necesarios para este trámite</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <ul class="divide-y divide-gray-200">
                            @foreach($tipoTramite->documentos_requeridos as $index => $docId)
                                @php
                                    // Aquí podrías hacer una consulta para obtener el nombre del documento
                                    // Por ahora mostramos el ID
                                @endphp
                                <li class="px-4 py-4 flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm text-gray-900">Documento ID: {{ $docId }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>

            <!-- Panel Lateral -->
            <div>
                <!-- Estadísticas -->
                @if(isset($stats))
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Estadísticas</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Expedientes Procesados</span>
                                <span class="text-sm font-medium text-gray-900">{{ $stats['expedientes_procesados'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Expedientes Completados</span>
                                <span class="text-sm font-medium text-gray-900">{{ $stats['expedientes_completados'] }}</span>
                            </div>
                            @if($tipoTramite->requiere_pago)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Ingresos Generados</span>
                                <span class="text-sm font-medium text-gray-900">S/ {{ number_format($stats['ingresos_generados'] ?? 0, 2) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Acciones Rápidas -->
                @canany(['editar_tipos_tramite', 'eliminar_tipos_tramite'])
                <div class="mt-6 bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Acciones</h3>
                        
                        <div class="space-y-3">
                            @can('editar_tipos_tramite')
                            <a href="{{ route('tipos-tramite.edit', $tipoTramite) }}" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar Trámite
                            </a>

                            <!-- Toggle Status -->
                            <form method="POST" action="{{ route('tipos-tramite.toggle-status', $tipoTramite) }}" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md {{ $tipoTramite->activo ? 'text-red-700 bg-red-50 hover:bg-red-100' : 'text-green-700 bg-green-50 hover:bg-green-100' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        onclick="return confirm('¿Está seguro de {{ $tipoTramite->activo ? 'desactivar' : 'activar' }} este tipo de trámite?')">
                                    @if($tipoTramite->activo)
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

                            @can('eliminar_tipos_tramite')
                            <form method="POST" action="{{ route('tipos-tramite.destroy', $tipoTramite) }}" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        onclick="return confirm('¿Está seguro de eliminar este tipo de trámite? Esta acción no se puede deshacer.')">
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
    </div>
</div>
@endsection