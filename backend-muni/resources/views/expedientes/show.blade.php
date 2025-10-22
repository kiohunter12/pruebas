@extends('layouts.app')

@section('title', 'Detalle del Expediente')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Expediente {{ $expediente->numero ?? $expediente->id }}</h3>
                    <p class="text-sm text-gray-500">Creado: {{ $expediente->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <a href="{{ route('expedientes.index') }}" class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">Volver</a>
                </div>
            </div>

            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs text-gray-500">Asunto</dt>
                    <dd class="text-sm text-gray-900">{{ $expediente->asunto }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Solicitante</dt>
                    <dd class="text-sm text-gray-900">{{ $expediente->solicitante_nombre }} ({{ $expediente->solicitante_dni }})</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900">{{ $expediente->solicitante_email }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Teléfono</dt>
                    <dd class="text-sm text-gray-900">{{ $expediente->solicitante_telefono }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Gerencia</dt>
                    <dd class="text-sm text-gray-900">{{ $expediente->gerencia->nombre ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Estado</dt>
                    <dd class="text-sm text-gray-900">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($expediente->estado == 'pendiente') bg-yellow-100 text-yellow-800
                            @elseif($expediente->estado == 'en_proceso') bg-blue-100 text-blue-800
                            @elseif($expediente->estado == 'aprobado') bg-green-100 text-green-800
                            @elseif($expediente->estado == 'rechazado') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $expediente->estado)) }}
                        </span>
                    </dd>
                </div>
            </dl>

            <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Paso Actual</h4>
                <div class="text-sm text-gray-800 bg-gray-50 p-3 rounded">
                    @if($expediente->currentStep)
                        <strong>{{ $expediente->currentStep->nombre }}</strong>
                        <div class="text-xs text-gray-500">
                            Responsable: {{ $currentProgress && $currentProgress->asignado ? $currentProgress->asignado->name : 'Sin asignar' }}
                            @if($currentProgress && $currentProgress->fecha_limite)
                                - Quedan {{ now()->diffInDays($currentProgress->fecha_limite, false) }} días
                            @endif
                        </div>
                    @elseif($expediente->workflow)
                        <span class="text-gray-500">Workflow: {{ $expediente->workflow->nombre }}</span>
                    @else
                        <span class="text-gray-500">No hay workflow asignado</span>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Descripción</h4>
                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">{{ $expediente->descripcion }}</p>
            </div>

            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Documentos</h4>
                <ul class="space-y-2">
                    @forelse($expediente->documentos as $doc)
                        <li class="flex items-center justify-between bg-gray-50 p-3 rounded hover:bg-gray-100">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-sm">{{ $doc->nombre }}</div>
                                    <div class="text-xs text-gray-500">{{ strtoupper($doc->extension) }} - {{ number_format($doc->tamaño/1024, 2) }} KB</div>
                                </div>
                            </div>
                            <div>
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Descargar</a>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500 bg-gray-50 p-4 rounded text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            No hay documentos adjuntos
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Historial</h4>
                <ul class="space-y-2">
                    @forelse($expediente->historial as $h)
                        <li class="bg-white p-4 rounded border border-gray-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $h->accion }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $h->created_at->format('d/m/Y H:i') }} - {{ $h->created_at->diffForHumans() }}
                                        @if($h->usuario)
                                            <span class="ml-2">por {{ $h->usuario->name }}</span>
                                        @endif
                                    </div>
                                    @if($h->observaciones)
                                        <div class="text-sm text-gray-600 mt-2 bg-gray-50 p-2 rounded">{{ $h->observaciones }}</div>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500 bg-gray-50 p-4 rounded text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            No hay historial registrado
                        </li>
                    @endforelse
                </ul>
            </div>

            @if($expediente->observaciones)
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Observaciones</h4>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <p class="text-sm text-yellow-700">{{ $expediente->observaciones }}</p>
                </div>
            </div>
            @endif

            @if($expediente->motivo_rechazo)
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Motivo de Rechazo</h4>
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <p class="text-sm text-red-700">{{ $expediente->motivo_rechazo }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
