@extends('layouts.app')

@section('title', 'Trámites y Flujos de Trabajo')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Tipos de trámite y sus flujos de trabajo
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Visualiza el proceso completo de cada tipo de trámite</p>
                </div>
                <a href="{{ route('tramites.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm transition">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nuevo Trámite
                </a>
            </div>

            <div class="space-y-6">
                @forelse($tramites as $t)
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-200">
                        <!-- Header del Trámite -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-lg font-bold text-gray-900">{{ $t->nombre }}</h4>
                                            <div class="flex items-center mt-1 space-x-4 text-sm">
                                                <span class="flex items-center text-gray-600">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    {{ $t->gerencia->nombre ?? 'Sin gerencia' }}
                                                </span>
                                                <span class="flex items-center text-gray-600">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $t->tiempo_estimado_dias }} días
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $t->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $t->activo ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('tramites.edit', $t->id) }}" class="ml-4 inline-flex items-center px-3 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar
                                </a>
                            </div>
                        </div>

                        <!-- Flujo de Trabajo -->
                        <div class="px-6 py-5 bg-white">
                            @if($t->workflow && $t->workflow->steps->count() > 0)
                                <div class="mb-3 flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">Flujo de trabajo: {{ $t->workflow->nombre }}</span>
                                </div>
                                
                                <!-- Visualización del flujo -->
                                <div class="relative">
                                    <div class="flex items-start space-x-2 overflow-x-auto pb-2">
                                        @foreach($t->workflow->steps as $index => $step)
                                            <div class="flex items-center flex-shrink-0">
                                                <!-- Step Card -->
                                                <div class="relative group">
                                                    <div class="w-48 bg-gradient-to-br 
                                                        @if($step->tipo === 'inicio') from-green-50 to-green-100 border-green-300
                                                        @elseif($step->tipo === 'fin') from-purple-50 to-purple-100 border-purple-300
                                                        @else from-blue-50 to-blue-100 border-blue-300
                                                        @endif
                                                        border-2 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200">
                                                        
                                                        <!-- Step Icon -->
                                                        <div class="flex items-center mb-2">
                                                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                                @if($step->tipo === 'inicio') bg-green-500
                                                                @elseif($step->tipo === 'fin') bg-purple-500
                                                                @else bg-blue-500
                                                                @endif
                                                                text-white font-bold text-sm shadow">
                                                                {{ $step->orden }}
                                                            </div>
                                                            <span class="ml-2 text-xs font-semibold 
                                                                @if($step->tipo === 'inicio') text-green-700
                                                                @elseif($step->tipo === 'fin') text-purple-700
                                                                @else text-blue-700
                                                                @endif">
                                                                @if($step->tipo === 'inicio') 🚀 INICIO
                                                                @elseif($step->tipo === 'fin') 🏁 FIN
                                                                @else ⚙️ PROCESO
                                                                @endif
                                                            </span>
                                                        </div>
                                                        
                                                        <!-- Step Title -->
                                                        <h5 class="text-sm font-bold text-gray-900 mb-1 line-clamp-2">
                                                            {{ $step->nombre }}
                                                        </h5>
                                                        
                                                        <!-- Step Description -->
                                                        @if($step->descripcion)
                                                            <p class="text-xs text-gray-600 line-clamp-2 mb-2">
                                                                {{ $step->descripcion }}
                                                            </p>
                                                        @endif
                                                        
                                                        <!-- Responsable -->
                                                        @if($step->responsable_rol || $step->responsable_usuario_id)
                                                            <div class="flex items-center text-xs text-gray-700 mt-2 pt-2 border-t border-gray-200">
                                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                                <span class="truncate">{{ $step->responsable_rol ?? 'Asignado' }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Arrow -->
                                                @if(!$loop->last)
                                                    <div class="flex items-center justify-center px-2">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Stats del workflow -->
                                <div class="mt-4 flex items-center space-x-4 text-xs text-gray-600">
                                    <span class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-1"></span>
                                        {{ $t->workflow->steps->count() }} etapas
                                    </span>
                                    <span class="flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                        {{ $t->expedientes->count() }} expedientes procesados
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center justify-center py-8 text-gray-400">
                                    <svg class="w-12 h-12 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium">Sin flujo de trabajo definido</p>
                                        <p class="text-xs">Crea un workflow en la sección de flujos de trabajo</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay trámites registrados</h3>
                        <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo tipo de trámite.</p>
                        <div class="mt-6">
                            <a href="{{ route('tramites.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Nuevo Trámite
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($tramites->count() > 0)
                <div class="mt-6">
                    {{ $tramites->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
