@extends('layouts.app')

@section('title', 'Trámites Asignados')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-briefcase mr-2"></i>Trámites Asignados
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        <i class="fas fa-building mr-1"></i>
                        {{ $gerencia->nombre }}
                    </p>
                    <nav class="mt-2 flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    Inicio
                                </a>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Mis Asignados</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            
            <!-- Alertas -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow animate-fade-in">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Pendientes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $expedientes->where('estado', 'pendiente')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-spinner text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">En Proceso</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $expedientes->where('estado', 'en_proceso')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="fas fa-search text-purple-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">En Revisión</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $expedientes->where('estado', 'en_revision')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Observados</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $expedientes->where('estado', 'observado')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Trámites -->
            @forelse($expedientes as $expediente)
                @php
                    $estadoColors = [
                        'pendiente' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200'],
                        'en_proceso' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200'],
                        'en_revision' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-200'],
                        'observado' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'border' => 'border-orange-200'],
                    ];
                    $colors = $estadoColors[$expediente->estado] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200'];
                @endphp
                
                <div class="bg-white rounded-lg shadow hover:shadow-xl transition-all duration-300 mb-6 overflow-hidden transform hover:-translate-y-1">
                    <!-- Header con estado -->
                    <div class="px-6 py-4 {{ $colors['bg'] }} border-b {{ $colors['border'] }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colors['bg'] }} {{ $colors['text'] }} border {{ $colors['border'] }}">
                                    <span class="w-2 h-2 mr-2 rounded-full {{ str_replace('100', '500', $colors['bg']) }}"></span>
                                    {{ ucfirst(str_replace('_', ' ', $expediente->estado)) }}
                                </span>
                                <span class="text-sm font-mono text-gray-600 bg-white px-3 py-1 rounded-full border border-gray-200">
                                    {{ $expediente->numero }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ optional($expediente->fecha_registro)->format('d/m/Y') ?? $expediente->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-5">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    {{ $expediente->asunto }}
                                </h3>
                                <p class="text-gray-600 mb-4 line-clamp-2">
                                    {{ $expediente->descripcion }}
                                </p>
                                
                                <div class="flex flex-wrap gap-3">
                                    <!-- Tipo de Trámite -->
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                        <span class="font-medium">{{ $expediente->tipoTramite->nombre ?? 'N/A' }}</span>
                                    </div>
                                    
                                    <!-- Paso Actual -->
                                    @if($expediente->currentStep)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-tasks text-purple-500 mr-2"></i>
                                        <div>
                                            <div>{{ $expediente->currentStep->nombre }}</div>
                                            @php
                                                $progress = $expediente->workflowProgress->firstWhere('workflow_step_id', $expediente->currentStep->id);
                                            @endphp
                                            <div class="text-xs text-gray-500">
                                                Responsable: {{ $progress && $progress->asignado ? $progress->asignado->name : 'Sin asignar' }}
                                                @if($progress && $progress->fecha_limite)
                                                    - Quedan {{ now()->diffInDays($progress->fecha_limite, false) }} días
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Documentos -->
                                    @if($expediente->documentos && $expediente->documentos->count() > 0)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-paperclip text-green-500 mr-2"></i>
                                        <span>{{ $expediente->documentos->count() }} documento(s)</span>
                                    </div>
                                    @endif

                                    
                                    

                                    <!-- Solicitante -->
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-user text-indigo-500 mr-2"></i>
                                        <span>{{ $expediente->solicitante_nombre }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Icono del tipo de trámite -->
                            <div class="ml-4 flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-file-contract text-white text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                @if($expediente->workflow)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-route mr-1"></i>
                                        {{ $expediente->workflow->nombre }}
                                    </span>
                                @endif
                            </div>
                            
                            <a href="{{ route('gerencia.tramites.show', $expediente->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow">
                                <i class="fas fa-eye mr-2"></i>
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Estado Vacío -->
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                        <i class="fas fa-inbox text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No hay trámites asignados</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">
                        Actualmente no hay trámites asignados a tu gerencia.
                    </p>
                </div>
            @endforelse

            <!-- Paginación -->
            @if($expedientes->hasPages())
            <div class="mt-8">
                {{ $expedientes->links() }}
            </div>
            @endif

        </div>
    </main>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
