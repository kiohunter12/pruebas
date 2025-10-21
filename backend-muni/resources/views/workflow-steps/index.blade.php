@extends('layouts.app')

@section('title', 'Pasos del Workflow - ' . $workflow->nombre)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('workflows.index') }}" class="text-gray-600 hover:text-blue-600">
                                    Workflows
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <a href="{{ route('workflows.show', $workflow) }}" class="text-gray-600 hover:text-blue-600">
                                        {{ $workflow->nombre }}
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-500">Pasos</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-3xl font-bold text-gray-900">Pasos del Workflow</h1>
                    <p class="mt-2 text-gray-600">Gestione los pasos del workflow: {{ $workflow->nombre }}</p>
                </div>
                @can('editar_workflows')
                <div>
                    <a href="{{ route('workflow-steps.create', $workflow) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nuevo Paso
                    </a>
                </div>
                @endcan
            </div>
        </div>

        <!-- Mensajes -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <!-- Lista de Pasos -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            @if($steps->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($steps as $step)
                <li class="hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4 flex-1">
                                <!-- Número de orden -->
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full">
                                        <span class="text-sm font-bold text-blue-600">{{ $step->orden }}</span>
                                    </div>
                                </div>
                                
                                <!-- Información del paso -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $step->nombre }}
                                        </p>
                                        @if($step->es_final)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Paso Final
                                        </span>
                                        @endif
                                        @if($step->requiere_aprobacion)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Requiere Aprobación
                                        </span>
                                        @endif
                                    </div>
                                    @if($step->descripcion)
                                    <p class="text-sm text-gray-500">{{ Str::limit($step->descripcion, 100) }}</p>
                                    @endif
                                    <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                        @if($step->gerencia)
                                        <div class="flex items-center">
                                            <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <span>{{ $step->gerencia->nombre }}</span>
                                        </div>
                                        @endif
                                        @if($step->usuarioResponsable)
                                        <div class="flex items-center">
                                            <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span>{{ $step->usuarioResponsable->name }}</span>
                                        </div>
                                        @endif
                                        @if($step->tiempo_limite_dias)
                                        <div class="flex items-center">
                                            <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ $step->tiempo_limite_dias }} días</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Acciones -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('workflow-steps.show', [$workflow, $step]) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @can('editar_workflows')
                                <a href="{{ route('workflow-steps.edit', [$workflow, $step]) }}" 
                                   class="text-gray-600 hover:text-gray-900">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('workflow-steps.destroy', [$workflow, $step]) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('¿Está seguro de eliminar este paso?')">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Sin pasos</h3>
                <p class="mt-1 text-sm text-gray-500">No hay pasos definidos para este workflow.</p>
                @can('editar_workflows')
                <div class="mt-6">
                    <a href="{{ route('workflow-steps.create', $workflow) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Agregar Primer Paso
                    </a>
                </div>
                @endcan
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
