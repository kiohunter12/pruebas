@extends('layouts.app')

@section('title', 'Editar Workflow - ' . $workflow->nombre)

@push('styles')
<style>
    .sortable-ghost {
        opacity: 0.4;
        background: #e3f2fd;
    }
    .sortable-drag {
        opacity: 0.8;
        cursor: move;
    }
    .drag-handle {
        cursor: move;
        touch-action: none;
    }
    .drag-handle:hover {
        background-color: #f3f4f6;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="workflowEditForm()"  x-init="initSortable()"
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Editar Workflow</h1>
                    <p class="mt-2 text-gray-600">Modificar configuración del workflow: {{ $workflow->nombre }}</p>
                </div>
                <div>
                    <a href="{{ route('workflows.show', $workflow) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Mensajes de Error -->
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong class="font-bold">¡Hay errores en el formulario!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('workflows.update', $workflow) }}" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Información Básica -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Información Básica</h3>
                    
                    <!-- Información del Tipo de Trámite -->
                    @if($tipoTramite)
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h4 class="text-sm font-medium text-blue-900">Tipo de Trámite: {{ $tipoTramite->nombre }}</h4>
                        </div>
                        <div class="text-xs text-blue-700 space-y-1">
                            <p>📄 <strong>Documentos disponibles:</strong> {{ $tiposDocumento->count() }}</p>
                            @if($tipoTramite->costo)
                            <p>💰 <strong>Costo:</strong> S/ {{ number_format($tipoTramite->costo, 2) }}</p>
                            @endif
                            @if($tipoTramite->tiempo_estimado_dias)
                            <p>⏱️ <strong>Tiempo estimado:</strong> {{ $tipoTramite->tiempo_estimado_dias }} días</p>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">
                                Nombre del Workflow <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $workflow->nombre) }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Ej: Proceso de Licencias Comerciales">
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipo de Workflow (solo mostrar, no editable para workflows existentes) -->
                        <div>
                            <label for="tipo_display" class="block text-sm font-medium text-gray-700">Tipo de Workflow</label>
                            <input type="text" id="tipo_display" readonly
                                   value="{{ $isCustom ? 'Personalizado' : 'Estándar' }}"
                                   class="mt-1 block w-full border-gray-300 bg-gray-50 rounded-md shadow-sm sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">El tipo de workflow no se puede modificar después de la creación.</p>
                        </div>

                        <!-- Gerencia -->
                        <div>
                            <label for="gerencia_id" class="block text-sm font-medium text-gray-700">
                                Gerencia Responsable <span class="text-red-500">*</span>
                            </label>
                            <select name="gerencia_id" id="gerencia_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Seleccione una gerencia</option>
                                @foreach($gerencias as $gerencia)
                                    <option value="{{ $gerencia->id }}" 
                                            {{ (old('gerencia_id', $workflow->gerencia_id) == $gerencia->id) ? 'selected' : '' }}>
                                        {{ $gerencia->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gerencia_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="activo" class="flex items-center">
                                <input type="checkbox" name="activo" id="activo" value="1" 
                                       {{ old('activo', $workflow->activo) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Workflow activo</span>
                            </label>
                            @error('activo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mt-6">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Descripción detallada del workflow y su propósito...">{{ old('descripcion', $workflow->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pasos del Workflow -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Pasos del Workflow</h3>
                        <button type="button" @click="addStep()" 
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Agregar Paso
                        </button>
                    </div>

                    <div class="space-y-4" id="steps-container">
                        <template x-for="(step, index) in steps" :key="step.id || index">
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 step-item">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <!-- Drag Handle -->
                                        <div class="drag-handle p-2 rounded cursor-move hover:bg-gray-200">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2zm0-4a1 1 0 100-2 1 1 0 000 2zm0-4a1 1 0 100-2 1 1 0 000 2z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-md font-medium text-gray-900" x-text="'Paso ' + (index + 1)"></h4>
                                    </div>
                                    <button type="button" @click="removeStep(index)" 
                                            class="text-red-600 hover:text-red-800" x-show="steps.length > 1">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Campo oculto para el orden (se actualiza automáticamente con drag & drop) -->
                                <input type="hidden" :name="'steps[' + index + '][orden]'" :value="index + 1">
                                
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <!-- Nombre del Paso -->
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Nombre del Paso <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" :name="'steps[' + index + '][nombre]'" x-model="step.nombre" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               placeholder="Ej: Revisión Técnica">
                                        <p class="mt-1 text-xs text-gray-500">
                                            💡 El orden se define arrastrando los pasos hacia arriba o abajo
                                        </p>
                                    </div>

                                    <!-- Usuario Responsable -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Usuario Responsable</label>
                                        <select :name="'steps[' + index + '][usuario_responsable]'" x-model="step.usuario_responsable"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">Sin asignar</option>
                                            <!-- Aquí podrías cargar usuarios dinámicamente -->
                                        </select>
                                    </div>

                                    <!-- Tiempo Límite -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tiempo Límite (días)</label>
                                        <input type="number" :name="'steps[' + index + '][tiempo_limite_dias]'" x-model="step.tiempo_limite_dias" min="1"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Descripción del Paso -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                    <textarea :name="'steps[' + index + '][descripcion]'" x-model="step.descripcion" rows="2"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                              placeholder="Descripción detallada de este paso..."></textarea>
                                </div>
                                
                                <!-- Documentos Requeridos -->
                                <div class="mt-4 border-t pt-4">
                                    <button type="button" 
                                            @click="step.showDocuments = !step.showDocuments"
                                            class="flex items-center justify-between w-full text-sm font-medium text-gray-700">
                                        <span>📄 Documentos requeridos en este paso</span>
                                        <svg :class="step.showDocuments ? 'rotate-180' : ''" 
                                             class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    
                                    <div x-show="step.showDocuments" x-transition class="mt-3 space-y-2 max-h-60 overflow-y-auto bg-white p-3 rounded border">
                                        @if($tiposDocumento->count() > 0)
                                            @foreach($tiposDocumento as $doc)
                                            <div class="flex items-center justify-between p-2 hover:bg-blue-50 rounded">
                                                <label class="flex items-center space-x-2 flex-1 cursor-pointer">
                                                    <input type="checkbox" 
                                                           :name="'steps[' + index + '][documentos][]'" 
                                                           value="{{ $doc->id }}"
                                                           :checked="step.documentos && step.documentos.includes({{ $doc->id }})"
                                                           @change="toggleStepDocument(index, {{ $doc->id }}, $event.target.checked)"
                                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    <span class="text-sm">{{ $doc->nombre }}</span>
                                                </label>
                                                
                                                <label class="flex items-center space-x-1 ml-2">
                                                    <input type="checkbox" 
                                                           :name="'steps[' + index + '][documentos_obligatorios][{{ $doc->id }}]'"
                                                           :disabled="!step.documentos || !step.documentos.includes({{ $doc->id }})"
                                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500 text-xs">
                                                    <span class="text-xs text-red-600 font-medium">Obligatorio</span>
                                                </label>
                                            </div>
                                            @endforeach
                                        @else
                                            <p class="text-xs text-gray-500 text-center py-2">
                                                @if($tipoTramite)
                                                    No hay documentos asociados a este tipo de trámite
                                                @else
                                                    No hay documentos disponibles
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Mensaje si no hay pasos -->
                    <div x-show="steps.length === 0" class="text-center py-8 text-gray-500">
                        <p>No hay pasos definidos. Haga clic en "Agregar Paso" para comenzar.</p>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('workflows.show', $workflow) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Actualizar Workflow
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function workflowEditForm() {
    return {
        steps: {!! json_encode($workflow->steps->map(function($step, $index) {
            return [
                'id' => 'step_' . $step->id . '_' . $index,
                'nombre' => $step->nombre,
                'descripcion' => $step->descripcion ?? '',
                'orden' => $step->orden,
                'usuario_responsable' => $step->usuario_responsable_id ?? '',
                'tiempo_limite_dias' => $step->tiempo_limite_dias ?? ($step->dias_limite ?? ''),
                'showDocuments' => false,
                'documentos' => $step->documentos->pluck('tipo_documento_id')->toArray()
            ];
        })->values()) !!},
        
        sortableInstance: null,
        
        initSortable() {
            const container = document.getElementById('steps-container');
            if (!container) return;
            
            this.sortableInstance = new Sortable(container, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: (evt) => {
                    // Reordenar el array de steps
                    const movedItem = this.steps.splice(evt.oldIndex, 1)[0];
                    this.steps.splice(evt.newIndex, 0, movedItem);
                    
                    // Actualizar el orden de todos los pasos
                    this.steps.forEach((step, index) => {
                        step.orden = index + 1;
                    });
                    
                    console.log('Pasos reordenados:', this.steps.map(s => s.nombre));
                }
            });
        },
        
        addStep() {
            const newId = 'step_new_' + Date.now();
            this.steps.push({
                id: newId,
                nombre: '',
                descripcion: '',
                orden: this.steps.length + 1,
                usuario_responsable: '',
                tiempo_limite_dias: '',
                showDocuments: false,
                documentos: []
            });
        },
        
        toggleStepDocument(stepIndex, documentoId, isChecked) {
            if (!this.steps[stepIndex].documentos) {
                this.steps[stepIndex].documentos = [];
            }
            
            if (isChecked) {
                if (!this.steps[stepIndex].documentos.includes(documentoId)) {
                    this.steps[stepIndex].documentos.push(documentoId);
                }
            } else {
                const index = this.steps[stepIndex].documentos.indexOf(documentoId);
                if (index > -1) {
                    this.steps[stepIndex].documentos.splice(index, 1);
                }
            }
        },
        
        removeStep(index) {
            if (this.steps.length > 1) {
                this.steps.splice(index, 1);
                // Reordenar los pasos
                this.steps.forEach((step, i) => {
                    step.orden = i + 1;
                });
            }
        },

        init() {
            // Si no hay pasos, agregar uno por defecto
            if (this.steps.length === 0) {
                this.addStep();
            }
        }
    }
}
</script>
@endsection