@extends('layouts.app')

@section('title', 'Detalles del Trámite')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-file-alt mr-2"></i>Detalles del Trámite
                    </h1>
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
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ route('gerencia.tramites.mis-asignados') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Mis Asignados</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $expediente->numero }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('gerencia.tramites.mis-asignados') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 transition shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Mensajes de Éxito/Error -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-md animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-green-400 hover:text-green-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-md animate-fade-in">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-red-400 hover:text-red-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            
            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-bolt mr-2"></i>
                        Acciones del Trámite
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <!-- Aprobar -->
                        @if($expediente->estado == 'en_proceso' || $expediente->estado == 'pendiente' || $expediente->estado == 'observado' || $expediente->estado == 'en_revision')
                        <button 
                            onclick="aprobarTramite()"
                            class="flex flex-col items-center justify-center p-6 bg-green-50 hover:bg-green-100 border-2 border-green-200 hover:border-green-400 rounded-lg transition group">
                            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition shadow-lg">
                                <i class="fas fa-check text-white text-2xl"></i>
                            </div>
                            <span class="text-base font-bold text-green-700">Aprobar Trámite</span>
                            <span class="text-xs text-gray-600 mt-1">Aprobar el paso actual</span>
                        </button>
                        @else
                        <div class="flex flex-col items-center justify-center p-6 bg-gray-50 border-2 border-gray-200 rounded-lg opacity-50">
                            <div class="w-16 h-16 bg-gray-400 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-check text-white text-2xl"></i>
                            </div>
                            <span class="text-base font-bold text-gray-600">Aprobar Trámite</span>
                            <span class="text-xs text-gray-500 mt-1">No disponible</span>
                        </div>
                        @endif

                        <!-- Rechazar -->
                        @if($expediente->estado == 'en_proceso' || $expediente->estado == 'pendiente' || $expediente->estado == 'observado' || $expediente->estado == 'en_revision')
                        <button 
                            onclick="rechazarTramite()"
                            class="flex flex-col items-center justify-center p-6 bg-red-50 hover:bg-red-100 border-2 border-red-200 hover:border-red-400 rounded-lg transition group">
                            <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition shadow-lg">
                                <i class="fas fa-times text-white text-2xl"></i>
                            </div>
                            <span class="text-base font-bold text-red-700">Rechazar Trámite</span>
                            <span class="text-xs text-gray-600 mt-1">Rechazar con motivo</span>
                        </button>
                        @else
                        <div class="flex flex-col items-center justify-center p-6 bg-gray-50 border-2 border-gray-200 rounded-lg opacity-50">
                            <div class="w-16 h-16 bg-gray-400 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-times text-white text-2xl"></i>
                            </div>
                            <span class="text-base font-bold text-gray-600">Rechazar Trámite</span>
                            <span class="text-xs text-gray-500 mt-1">No disponible</span>
                        </div>
                        @endif

                        <!-- Agregar Observación -->
                        <button 
                            onclick="agregarObservacion()"
                            class="flex flex-col items-center justify-center p-6 bg-yellow-50 hover:bg-yellow-100 border-2 border-yellow-200 hover:border-yellow-400 rounded-lg transition group">
                            <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition shadow-lg">
                                <i class="fas fa-comment-alt text-white text-2xl"></i>
                            </div>
                            <span class="text-base font-bold text-yellow-700">Agregar Observación</span>
                            <span class="text-xs text-gray-600 mt-1">Registrar observación</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Progreso del Flujo de Trabajo -->
            @if($expediente->tipoTramite && $expediente->tipoTramite->workflow && $expediente->tipoTramite->workflow->steps->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-route mr-2"></i>
                        Progreso del Trámite
                    </h2>
                </div>
                <div class="p-8">
                    @php
                        $displayWorkflow = $expediente->workflow ?? ($expediente->tipoTramite->workflow ?? null);
                        $steps = $displayWorkflow->steps->sortBy('orden');
                        $currentStepId = $expediente->current_step_id;
                        $currentStepOrder = $expediente->currentStep ? $expediente->currentStep->orden : 0;
                    @endphp

                    <div class="overflow-x-auto pb-4">
                        <div class="flex items-start min-w-max px-6 py-6" style="gap: 24px;">
                            @foreach($steps as $index => $step)
                                @php
                                    $isCompleted = $step->orden < $currentStepOrder;
                                    $isCurrent = $step->id == $currentStepId;
                                    $stepProgress = $expediente->workflowProgress->firstWhere('workflow_step_id', $step->id);
                                @endphp

                                <div class="flex flex-col items-center text-center" style="min-width: 220px; position: relative;">
                                    <div role="img" aria-label="Paso {{ $step->orden }} - {{ $step->nombre }}">
                                        @if($isCompleted)
                                            <div class="step-circle completed flex items-center justify-center w-24 h-24 rounded-full bg-white border-4 border-green-400 shadow-sm">
                                                <i class="fas fa-check text-green-600 text-xl"></i>
                                            </div>
                                        @elseif($isCurrent)
                                            <div class="step-circle current flex items-center justify-center w-24 h-24 rounded-full bg-white border-4 border-blue-400 shadow-sm">
                                                <i class="fas fa-spinner fa-spin text-blue-500 text-xl"></i>
                                            </div>
                                        @else
                                            <div class="step-circle pending flex items-center justify-center w-24 h-24 rounded-full bg-white border-2 border-gray-200 shadow-sm">
                                                <span class="font-bold text-gray-600">{{ $step->orden }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4 w-52">
                                        <p class="text-sm font-semibold {{ $isCurrent ? 'text-blue-600' : ($isCompleted ? 'text-green-600' : 'text-gray-700') }}">{{ $step->nombre }}</p>
                                        @if($step->descripcion)
                                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit($step->descripcion, 60) }}</p>
                                        @endif
                                        <div class="mt-2 text-xs text-gray-500">
                                            <i class="fas fa-user mr-1"></i>
                                            @if($stepProgress && $stepProgress->asignado)
                                                <span class="font-medium text-gray-800">{{ $stepProgress->asignado->name }}</span>
                                                @if($stepProgress->asignado->gerencia)
                                                    <span class="text-gray-500"> - {{ $stepProgress->asignado->gerencia->nombre }}</span>
                                                @endif
                                                @if($stepProgress->fecha_limite)
                                                    <div class="text-xs text-gray-400">Quedan {{ now()->diffInDays($stepProgress->fecha_limite, false) }} días</div>
                                                @endif
                                            @elseif($step->responsable_rol)
                                                <span class="text-gray-700">{{ $step->responsable_rol }}</span>
                                            @else
                                                <span class="text-gray-500">Sin asignar</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if(!$loop->last)
                                    @php
                                        $connectorFilled = $steps[$index]->orden < $currentStepOrder;
                                    @endphp
                                    <div class="flex items-center" style="height: 1px; align-self: center;">
                                        <div class="h-1 mx-4" style="width: 160px;">
                                            <div class="h-1 w-full rounded {{ $connectorFilled ? 'bg-green-300' : 'bg-gray-200' }}"></div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Información adicional del progreso -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-2xl font-bold text-green-600">{{ $steps->filter(fn($s) => $s->orden < $currentStepOrder)->count() }}</p>
                                <p class="text-sm text-gray-600">Etapas Completadas</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-blue-600">1</p>
                                <p class="text-sm text-gray-600">Etapa Actual</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-600">{{ $steps->filter(fn($s) => $s->orden > $currentStepOrder)->count() }}</p>
                                <p class="text-sm text-gray-600">Etapas Pendientes</p>
                            </div>
                        </div>
                        
                        <!-- Barra de progreso general -->
                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Progreso General</span>
                                <span class="text-sm font-bold text-blue-600">
                                    {{ $steps->count() > 0 ? round((($currentStepOrder) / $steps->count()) * 100) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-500 to-blue-500 h-3 rounded-full transition-all duration-500" 
                                     style="width: {{ $steps->count() > 0 ? round((($currentStepOrder) / $steps->count()) * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Botón de Avanzar Workflow -->
            @if($expediente->currentStep && $expediente->estado != 'finalizado' && $expediente->estado != 'aprobado' && $expediente->estado != 'rechazado')
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Paso Actual</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $expediente->currentStep->nombre }} - {{ $expediente->currentStep->descripcion }}
                            </p>
                        </div>
                        <button 
                            onclick="abrirModalAvanzar()"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 transition shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-forward mr-2"></i>
                            Avanzar al Siguiente Paso
                        </button>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Columna Principal -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Información General -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Información General
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Asunto</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $expediente->asunto }}</p>
                                </div>
                                
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Descripción</label>
                                    <p class="mt-1 text-gray-700 whitespace-pre-line">{{ $expediente->descripcion }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Número de Expediente</label>
                                        <p class="mt-1 font-mono text-blue-600 font-bold">{{ $expediente->numero }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Tipo de Trámite</label>
                                        <p class="mt-1 text-gray-900 font-medium">{{ $expediente->tipoTramite->nombre ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Gerencia</label>
                                        <p class="mt-1 text-gray-900">{{ $expediente->gerencia->nombre ?? 'Sin asignar' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Fecha de Registro</label>
                                        <p class="mt-1 text-gray-900">
                                            {{ optional($expediente->fecha_registro)->format('d/m/Y H:i') ?? optional($expediente->fecha_ingreso)->format('d/m/Y H:i') ?? $expediente->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos Adjuntos -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-teal-600 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-black flex items-center">
                                <i class="fas fa-paperclip mr-2"></i>
                                Documentos Adjuntos
                                <span class="ml-2 px-2 py-1 bg-white text-green-600 rounded-full text-sm font-bold">
                                    {{ isset($visibleDocuments) ? $visibleDocuments->count() : $expediente->documentos->count() }}
                                </span>
                            </h2>
                        </div>
                        <div class="p-6">
                            @php $docsToShow = $visibleDocuments ?? $expediente->documentos; @endphp
                            @if($docsToShow->count() > 0)
                                <div class="space-y-3">
                                    @foreach($docsToShow as $documento)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-file-pdf text-blue-600"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $documento->nombre }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        Documento {{ $loop->iteration }}
                                                    </p>
                                                </div>
                                            </div>
                                            <a href="{{ route('ciudadano.tramites.descargar-documento', $documento->id) }}" 
                                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                                <i class="fas fa-download mr-1"></i>
                                                Descargar
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-folder-open text-gray-300 text-4xl mb-3"></i>
                                    <p class="text-gray-500">No hay documentos adjuntos</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Historial -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-history mr-2"></i>
                                Historial de Movimientos
                            </h2>
                        </div>
                        <div class="p-6">
                            @if($expediente->historial->count() > 0)
                                <div class="flow-root">
                                    <ul class="-mb-8">
                                        @foreach($expediente->historial as $index => $historial)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                            <i class="fas fa-circle text-white text-xs"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                            <div class="flex items-center justify-between mb-2">
                                                                <p class="text-sm font-medium text-gray-900">{{ $historial->accion }}</p>
                                                                <p class="text-xs text-gray-500">
                                                                    {{ $historial->created_at->format('d/m/Y H:i') }}
                                                                </p>
                                                            </div>
                                                            @if($historial->descripcion)
                                                            <p class="text-sm text-gray-600">{{ $historial->descripcion }}</p>
                                                            @endif
                                                            @if($historial->usuario)
                                                            <p class="text-xs text-gray-500 mt-2">
                                                                <i class="fas fa-user mr-1"></i>
                                                                {{ $historial->usuario->name }}
                                                            </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-clock text-gray-300 text-4xl mb-3"></i>
                                    <p class="text-gray-500">No hay movimientos registrados</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Columna Lateral -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Estado -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 bg-gray-800 border-b border-gray-200">
                            <h2 class="text-lg font-bold text-white flex items-center">
                                <i class="fas fa-flag mr-2"></i>
                                Estado Actual
                            </h2>
                        </div>
                        <div class="p-6">
                            @php
                                $estadoColors = [
                                    'pendiente' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => 'fa-clock'],
                                    'en_proceso' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'icon' => 'fa-spinner'],
                                    'en_revision' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-300', 'icon' => 'fa-search'],
                                    'observado' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'border' => 'border-orange-300', 'icon' => 'fa-exclamation-triangle'],
                                    'aprobado' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300', 'icon' => 'fa-check-circle'],
                                    'rechazado' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'icon' => 'fa-times-circle'],
                                    'finalizado' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300', 'icon' => 'fa-flag-checkered']
                                ];
                                $colors = $estadoColors[$expediente->estado] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300', 'icon' => 'fa-question'];
                            @endphp
                            
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-20 h-20 {{ $colors['bg'] }} rounded-full mb-4">
                                    <i class="fas {{ $colors['icon'] }} {{ $colors['text'] }} text-3xl"></i>
                                </div>
                                <p class="text-2xl font-bold {{ $colors['text'] }} mb-2">
                                    {{ ucfirst(str_replace('_', ' ', $expediente->estado)) }}
                                </p>
                                <div class="mt-4 p-3 {{ $colors['bg'] }} border {{ $colors['border'] }} rounded-lg">
                                    <p class="text-sm {{ $colors['text'] }} font-medium">
                                        @switch($expediente->estado)
                                            @case('pendiente')
                                                Su trámite está pendiente de revisión
                                                @break
                                            @case('en_proceso')
                                                Su trámite está siendo procesado
                                                @break
                                            @case('en_revision')
                                                Su trámite está en revisión
                                                @break
                                            @case('observado')
                                                Su trámite tiene observaciones
                                                @break
                                            @case('aprobado')
                                                Su trámite ha sido aprobado
                                                @break
                                            @case('rechazado')
                                                Su trámite ha sido rechazado
                                                @break
                                            @case('finalizado')
                                                Su trámite ha sido finalizado
                                                @break
                                            @default
                                                Estado del trámite
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Pago -->
                    @if(isset($expediente->requiere_pago) && $expediente->requiere_pago)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 {{ $expediente->pagado ? 'bg-green-600' : 'bg-yellow-600' }} border-b border-gray-200">
                            <h2 class="text-lg font-bold text-white flex items-center">
                                <i class="fas fa-dollar-sign mr-2"></i>
                                Información de Pago
                            </h2>
                        </div>
                        <div class="p-6">
                            @if($expediente->pagado)
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                        <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                                    </div>
                                    <p class="text-lg font-bold text-green-800 mb-2">Pago Realizado</p>
                                    <p class="text-3xl font-bold text-gray-900">S/. {{ number_format($expediente->monto ?? 0, 2) }}</p>
                                </div>
                            @else
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl"></i>
                                    </div>
                                    <p class="text-lg font-bold text-yellow-800 mb-2">Pago Pendiente</p>
                                    <p class="text-3xl font-bold text-gray-900 mb-4">S/. {{ number_format($expediente->monto ?? 0, 2) }}</p>
                                    <button class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        Realizar Pago
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Datos del Solicitante -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 bg-gray-800 border-b border-gray-200">
                            <h2 class="text-lg font-bold text-white flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                Solicitante
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase">Nombre Completo</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">
                                        {{ $expediente->solicitante_nombre }} {{ $expediente->solicitante_apellido }}
                                    </p>
                                </div>
                                @if($expediente->solicitante_dni)
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase">DNI</label>
                                    <p class="mt-1 text-sm text-gray-900 font-mono">{{ $expediente->solicitante_dni }}</p>
                                </div>
                                @endif
                                @if($expediente->solicitante_email)
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase">Email</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $expediente->solicitante_email }}</p>
                                </div>
                                @endif
                                @if($expediente->solicitante_telefono)
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase">Teléfono</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $expediente->solicitante_telefono }}</p>
                                </div>
                                @endif
                                @if($expediente->solicitante_direccion)
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase">Dirección</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $expediente->solicitante_direccion }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>

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
    
    /* Animación de pulso para el paso actual */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .7;
        }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Transiciones suaves para los elementos del workflow */
    .workflow-step {
        transition: all 0.3s ease-in-out;
    }
    
    .workflow-step:hover {
        transform: translateY(-2px);
    }
</style>

<!-- Modal para Aprobar Trámite -->
<div id="modalAprobar" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 overflow-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-2xl my-8">
            <form action="{{ route('gerencia.tramites.aprobar', $expediente->id) }}" method="POST">
                @csrf
                
                <!-- Header -->
                <div class="flex items-center justify-between p-5 border-b bg-white rounded-t-lg">
                    <h3 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-check-circle mr-2 text-green-600"></i>
                        Aprobar Trámite
                    </h3>
                    <button type="button" onclick="cerrarModalAprobar()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-5">
                    <div class="mb-4 p-4 bg-green-50 rounded-lg border border-green-200">
                        <p class="text-sm font-medium text-green-900 mb-1">
                            <i class="fas fa-info-circle mr-2"></i>
                            Va a aprobar el trámite:
                        </p>
                        <p class="text-lg font-bold text-green-700">{{ $expediente->numero }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $expediente->asunto }}</p>
                    </div>

                    <div>
                        <label for="comentarios_aprobar" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment-alt mr-2"></i>Comentarios (Opcional)
                        </label>
                        <textarea 
                            name="comentarios" 
                            id="comentarios_aprobar" 
                            rows="4"
                            placeholder="Agregue comentarios sobre la aprobación..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 p-5 border-t bg-gray-50 rounded-b-lg">
                    <button 
                        type="button" 
                        onclick="cerrarModalAprobar()"
                        class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition shadow-lg">
                        <i class="fas fa-check mr-2"></i>Aprobar Trámite
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Rechazar Trámite -->
<div id="modalRechazar" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 overflow-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-2xl my-8">
            <!-- Header -->
            <div class="flex items-center justify-between p-5 border-b">
                <h3 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-times-circle mr-2 text-red-600"></i>
                    Rechazar Trámite
                </h3>
                <button type="button" onclick="cerrarModalRechazar()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Form (Body + Footer dentro del form) -->
            <form action="{{ route('gerencia.tramites.rechazar', $expediente->id) }}" method="POST">
                @csrf
                
                <!-- Body -->
                <div class="p-5">
                    <div class="mb-4 p-4 bg-red-50 rounded-lg border border-red-200">
                        <p class="text-sm font-medium text-red-900 mb-1">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Va a rechazar el trámite:
                        </p>
                        <p class="text-lg font-bold text-red-700">{{ $expediente->numero }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $expediente->asunto }}</p>
                    </div>

                    <div class="mb-4">
                        <label for="motivo_rechazo" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-exclamation-circle mr-2"></i>Motivo del Rechazo *
                        </label>
                        <textarea 
                            name="motivo" 
                            id="motivo_rechazo" 
                            rows="3"
                            required
                            placeholder="Ingrese el motivo del rechazo..."
                            class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                    </div>

                    <div>
                        <label for="comentarios_rechazo" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment-alt mr-2"></i>Comentarios Adicionales (Opcional)
                        </label>
                        <textarea 
                            name="comentarios" 
                            id="comentarios_rechazo" 
                            rows="3"
                            placeholder="Comentarios adicionales..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 p-5 border-t bg-gray-50 rounded-b-lg">
                    <button 
                        type="button" 
                        onclick="cerrarModalRechazar()"
                        class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition shadow-lg">
                        <i class="fas fa-times mr-2"></i>Rechazar Trámite
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Agregar Observación -->
<div id="modalObservacion" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 overflow-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-2xl my-8">
            <!-- Header -->
            <div class="flex items-center justify-between p-5 border-b">
                <h3 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-comment-alt mr-2 text-yellow-600"></i>
                    Agregar Observación
                </h3>
                <button type="button" onclick="cerrarModalObservacion()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Form (Body + Footer dentro del form) -->
            <form action="{{ route('gerencia.tramites.observacion', $expediente->id) }}" method="POST">
                @csrf
                
                <!-- Body -->
                <div class="p-5">
                    <div class="mb-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-sm font-medium text-yellow-900 mb-1">
                            <i class="fas fa-info-circle mr-2"></i>
                            Trámite:
                        </p>
                        <p class="text-lg font-bold text-yellow-700">{{ $expediente->numero }}</p>
                    </div>

                    <div>
                        <label for="observacion_texto" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-edit mr-2"></i>Observación *
                        </label>
                        <textarea 
                            name="observacion" 
                            id="observacion_texto" 
                            rows="5"
                            required
                            placeholder="Ingrese su observación sobre este trámite..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent resize-none"></textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            La observación será registrada en el historial del trámite
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 p-5 border-t bg-gray-50 rounded-b-lg">
                    <button 
                        type="button" 
                        onclick="cerrarModalObservacion()"
                        class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="px-6 py-3 bg-yellow-600 text-white rounded-lg font-medium hover:bg-yellow-700 transition shadow-lg">
                        <i class="fas fa-save mr-2"></i>Guardar Observación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Avanzar Workflow -->
<div id="modalAvanzar" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" style="overflow-y: auto;">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white max-h-[90vh] overflow-y-auto">
        <!-- Header del Modal -->
        <div class="flex items-center justify-between pb-4 border-b">
            <h3 class="text-xl font-bold text-gray-900">
                <i class="fas fa-forward mr-2 text-blue-600"></i>
                Avanzar Trámite al Siguiente Paso
            </h3>
            <button onclick="cerrarModalAvanzar()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Contenido del Modal -->
        <form id="formAvanzar" action="{{ route('gerencia.tramites.avanzar', $expediente->id) }}" method="POST" class="mt-4">
            @csrf
            
            <!-- Paso Actual -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-sm font-medium text-blue-900 mb-1">Paso Actual:</p>
                <p class="text-lg font-bold text-blue-700">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    {{ $expediente->currentStep->nombre ?? 'Sin paso actual' }}
                </p>
            </div>

            <!-- Selector de Siguiente Paso -->
            <div class="mb-6">
                <label for="siguiente_paso" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-route mr-2"></i>Siguiente Paso *
                </label>
                <select 
                    name="siguiente_paso_id" 
                    id="siguiente_paso" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Seleccione el siguiente paso...</option>
                    @if($expediente->currentStep && $expediente->tipoTramite && $expediente->tipoTramite->workflow)
                        @php
                            $siguientesPasos = $expediente->tipoTramite->workflow->steps
                                ->where('orden', '>', $expediente->currentStep->orden)
                                ->sortBy('orden');
                        @endphp
                        @foreach($siguientesPasos as $paso)
                            <option value="{{ $paso->id }}">
                                {{ $paso->orden }}. {{ $paso->nombre }}
                                @if($paso->descripcion)
                                    - {{ Str::limit($paso->descripcion, 50) }}
                                @endif
                            </option>
                        @endforeach
                        
                        @if($siguientesPasos->isEmpty())
                            <option value="finalizado">✓ Finalizar Trámite (Último Paso)</option>
                        @endif
                    @endif
                </select>
            </div>

            <!-- Observaciones -->
            <div class="mb-6">
                <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-comment-alt mr-2"></i>Observaciones
                </label>
                <textarea 
                    name="observaciones" 
                    id="observaciones" 
                    rows="4"
                    placeholder="Ingrese observaciones sobre el avance del trámite..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                <p class="mt-1 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Opcional: Agregue notas o comentarios relevantes sobre este avance
                </p>
            </div>

            <!-- Acciones del Modal -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <button 
                    type="button" 
                    onclick="cerrarModalAvanzar()"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                <button 
                    type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 transition shadow-lg">
                    <i class="fas fa-check mr-2"></i>
                    Avanzar Trámite
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalAvanzar() {
        document.getElementById('modalAvanzar').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevenir scroll del body
    }

    function cerrarModalAvanzar() {
        document.getElementById('modalAvanzar').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Restaurar scroll del body
    }

    // Cerrar modal al hacer clic fuera de él
    document.getElementById('modalAvanzar').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalAvanzar();
        }
    });

    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalAvanzar();
        }
    });

    // Validación del formulario
    document.getElementById('formAvanzar').addEventListener('submit', function(e) {
        const siguientePaso = document.getElementById('siguiente_paso').value;
        if (!siguientePaso) {
            e.preventDefault();
            alert('Por favor, seleccione el siguiente paso');
            return false;
        }
        
        // Confirmación antes de avanzar
        if (!confirm('¿Está seguro de avanzar este trámite al siguiente paso?')) {
            e.preventDefault();
            return false;
        }
    });

    // ========== FUNCIONES DE ACCIONES ==========
    
    function aprobarTramite() {
        const modal = document.getElementById('modalAprobar');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function rechazarTramite() {
        const modal = document.getElementById('modalRechazar');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function agregarObservacion() {
        const modal = document.getElementById('modalObservacion');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    // ========== FUNCIONES PARA CERRAR MODALES ==========

    function cerrarModalAprobar() {
        const modal = document.getElementById('modalAprobar');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function cerrarModalRechazar() {
        const modal = document.getElementById('modalRechazar');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function cerrarModalObservacion() {
        const modal = document.getElementById('modalObservacion');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Modal Observación
    function abrirModalObservacion() {
        document.getElementById('modalObservacion').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalObservacion() {
        document.getElementById('modalObservacion').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Cerrar modales al hacer clic fuera
    document.getElementById('modalAprobar')?.addEventListener('click', function(e) {
        if (e.target === this) cerrarModalAprobar();
    });

    document.getElementById('modalRechazar')?.addEventListener('click', function(e) {
        if (e.target === this) cerrarModalRechazar();
    });

    document.getElementById('modalObservacion')?.addEventListener('click', function(e) {
        if (e.target === this) cerrarModalObservacion();
    });

    // Validación formulario aprobar
    document.getElementById('formAprobar')?.addEventListener('submit', function(e) {
        if (!confirm('¿Está seguro de APROBAR este trámite?')) {
            e.preventDefault();
            return false;
        }
    });

    // Validación formulario rechazar
    document.getElementById('formRechazar')?.addEventListener('submit', function(e) {
        const motivo = document.getElementById('motivo_rechazo').value.trim();
        if (!motivo) {
            e.preventDefault();
            alert('Por favor, ingrese el motivo del rechazo');
            return false;
        }
        if (!confirm('¿Está seguro de RECHAZAR este trámite?\n\nEsta acción notificará al ciudadano.')) {
            e.preventDefault();
            return false;
        }
    });

    // Validación formulario observación
    document.getElementById('formObservacion')?.addEventListener('submit', function(e) {
        const observacion = document.getElementById('observacion_texto').value.trim();
        if (!observacion) {
            e.preventDefault();
            alert('Por favor, ingrese una observación');
            return false;
        }
    });
</script>
@endsection
