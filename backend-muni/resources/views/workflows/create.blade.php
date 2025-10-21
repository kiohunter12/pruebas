@extends('layouts.app')

@section('title', 'Crear Flujo de Trámite')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="tramiteFlowForm()">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Nuevo Flujo</h1>
            <p class="mt-2 text-gray-600">Configure el flujo de trabajo para un trámite específico</p>
            
            {{-- Mostrar información adicional según rol --}}
            @role('superadministrador|administrador')
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Como administrador, puede crear flujos para cualquier gerencia del sistema.
                    </p>
                </div>
            @endrole
            
            @role('jefe_gerencia')
                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Como jefe de gerencia, puede crear flujos para su gerencia: {{ auth()->user()->gerencia->nombre ?? 'Sin gerencia asignada' }}
                    </p>
                </div>
            @endrole
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

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('workflows.store') }}" class="space-y-8" @submit="onFormSubmit">
            @csrf
            
            <!-- Formulario Principal -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-6">Seleccionar Tipo de Trámite</h2>
                
                <!-- Selector de Tipo de Trámite -->
                <div class="mb-6">
                    <label for="tipo_tramite_id" class="block text-sm font-medium text-gray-700 mb-3">
                        Tipo de Trámite <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_tramite_id" id="tipo_tramite_id" required x-model="selectedTipoTramite"
                            @change="loadGerenciasForTramite()"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione un tipo de trámite</option>
                        @foreach($tiposTramite as $tipoTramite)
                            <option value="{{ $tipoTramite->id }}" data-nombre="{{ $tipoTramite->nombre }}">
                                {{ $tipoTramite->nombre }} - {{ $tipoTramite->gerencia->nombre ?? 'Sin gerencia' }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_tramite_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombre del Flujo (se actualiza automáticamente) -->
                <div class="mb-6" x-show="selectedTipoTramite">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Nombre del Flujo
                    </label>
                    <div class="p-3 bg-gray-50 border border-gray-300 rounded-lg">
                        <span class="font-medium text-gray-800" x-text="'Flujo para ' + tramiteName"></span>
                    </div>
                </div>
            </div>

            <!-- Seleccionar Gerencias -->
            <div class="bg-white shadow rounded-lg p-6" x-show="selectedTipoTramite && gerenciasDisponibles.length > 0">
                <h2 class="text-xl font-semibold mb-6">Gerencias Disponibles para este Trámite</h2>
                <p class="text-sm text-gray-600 mb-4">
                    Configure el flujo de trabajo seleccionando las gerencias que participarán en el proceso del trámite.
                    Cada gerencia tendrá automáticamente asignado su responsable.
                </p>
                
                <!-- Gerencias Filtradas -->
                <div class="space-y-4">
                    <template x-for="gerencia in gerenciasDisponibles" :key="gerencia.id">
                        <div class="border border-gray-200 rounded-lg">
                            <!-- Gerencia Principal -->
                            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-t-lg">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <div>
                                        <span class="font-medium text-gray-800" x-text="gerencia.nombre"></span>
                                        <p class="text-xs text-gray-600" x-text="gerencia.descripcion"></p>
                                        <div class="flex items-center space-x-2 mt-1" x-show="gerencia.responsable">
                                            <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="text-xs text-gray-600" x-text="gerencia.responsable?.name || 'Sin responsable'"></span>
                                        </div>
                                    </div>
                                    <span class="text-sm text-blue-600" x-text="'(' + getSubgerenciasCount(gerencia) + ' subgerencias)'"></span>
                                </div>
                                <button type="button" @click="elegirGerencia(gerencia)" 
                                        :disabled="isGerenciaSelected(gerencia.id)"
                                        :class="isGerenciaSelected(gerencia.id) ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600'"
                                        class="px-4 py-2 text-white rounded-lg font-medium transition-colors">
                                    <span x-text="isGerenciaSelected(gerencia.id) ? 'Elegido' : 'Elegir'"></span>
                                </button>
                            </div>
                            
                            <!-- Subgerencias -->
                            <div class="bg-gray-50 rounded-b-lg" x-show="gerencia.subgerencias && gerencia.subgerencias.length > 0">
                                <template x-for="subgerencia in gerencia.subgerencias" :key="subgerencia.id">
                                    <div class="flex items-center justify-between p-3 border-t border-gray-200">
                                        <div class="flex items-center space-x-3 pl-8">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            <div>
                                                <span class="text-gray-700" x-text="subgerencia.nombre"></span>
                                                <div class="flex items-center space-x-2 mt-1" x-show="subgerencia.responsable">
                                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span class="text-xs text-gray-500" x-text="subgerencia.responsable?.name || 'Sin responsable'"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" @click="elegirGerencia(subgerencia)" 
                                                :disabled="isGerenciaSelected(subgerencia.id)"
                                                :class="isGerenciaSelected(subgerencia.id) ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600'"
                                                class="px-3 py-1 text-white rounded text-sm font-medium transition-colors">
                                            <span x-text="isGerenciaSelected(subgerencia.id) ? 'Elegido' : 'Elegir'"></span>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Flujo Seleccionado -->
            <div class="grid grid-cols-1 gap-8">
                <!-- Áreas con Documentos -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Pasos del Flujo</h3>
                    <div class="space-y-4">
                        <template x-for="(area, index) in areasSeleccionadas" :key="index">
                            <div class="border rounded-lg">
                                <!-- Header del Área -->
                                <div class="flex items-center justify-between p-3 rounded-t-lg"
                                     :class="area.gerencia_padre_id ? 'bg-green-50 border-l-4 border-green-400' : 'bg-blue-50 border-l-4 border-blue-400'">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <span class="font-bold text-gray-600" x-text="(index + 1) + '.'"></span>
                                        <div class="flex items-center space-x-2">
                                            <svg :class="area.gerencia_padre_id ? 'text-green-600' : 'text-blue-600'" 
                                                 class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      :d="area.gerencia_padre_id ? 'M9 5l7 7-7 7' : 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'"></path>
                                            </svg>
                                            <div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-medium" x-text="area.nombre"></span>
                                                    <span x-show="area.gerencia_padre_id" 
                                                          class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                                        Subgerencia
                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-600 mt-1">
                                                    <span class="font-medium" x-text="getResponsableNombre(area)"></span>
                                                    <span class="mx-1">·</span>
                                                    <span x-text="area.cargo_responsable"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="removerArea(index)" 
                                            class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Tiempo límite -->
                                <div class="p-3 bg-white border-t">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        ⏱️ Tiempo límite (días)
                                    </label>
                                    <input type="number" 
                                           :name="'steps[' + index + '][tiempo_limite_dias]'" 
                                           x-model="area.tiempo_limite_dias"
                                           min="1"
                                           class="w-full sm:w-32 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                           placeholder="Ej: 3">
                                    <p class="mt-1 text-xs text-gray-500">Días hábiles para completar este paso</p>
                                </div>
                                
                                <!-- Selector de documentos -->
                                <div class="p-3 bg-gray-50 border-t">
                                    <button type="button" 
                                            @click="area.showDocuments = !area.showDocuments"
                                            class="flex items-center justify-between w-full text-sm font-medium text-gray-700">
                                        <span>📄 Documentos requeridos</span>
                                        <svg :class="area.showDocuments ? 'rotate-180' : ''" 
                                             class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    
                                    <div x-show="area.showDocuments" x-transition class="mt-3 space-y-2 max-h-60 overflow-y-auto">
                                        <template x-for="doc in tiposDocumento" :key="doc.id">
                                            <div class="flex items-center justify-between p-2 bg-white border rounded hover:bg-blue-50">
                                                <label class="flex items-center space-x-2 flex-1 cursor-pointer">
                                                    <input type="checkbox" 
                                                           :name="'steps[' + index + '][documentos][]'" 
                                                           :value="doc.id"
                                                           @change="updateAreaDocuments(index, doc.id, $event.target.checked)"
                                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    <span class="text-sm" x-text="doc.nombre"></span>
                                                </label>
                                                
                                                <label class="flex items-center space-x-1 ml-2">
                                                    <input type="checkbox" 
                                                           :name="'steps[' + index + '][documentos_obligatorios][' + doc.id + ']'"
                                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500 text-xs">
                                                    <span class="text-xs text-red-600 font-medium">Obligatorio</span>
                                                </label>
                                            </div>
                                        </template>
                                        
                                        <p x-show="!tiposDocumento || tiposDocumento.length === 0" 
                                           class="text-xs text-gray-500 text-center py-2">
                                            No hay documentos disponibles
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="areasSeleccionadas.length === 0" class="text-gray-500 text-center py-8">
                            No hay áreas seleccionadas
                        </div>
                    </div>
                </div>

                <!-- Responsables -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Responsable</h3>
                    <div class="space-y-2">
                        <template x-for="(area, index) in areasSeleccionadas" :key="index">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="font-bold text-gray-600" x-text="(index + 1) + '.'"></span>
                                <div class="flex-1 mx-3 p-2 bg-blue-50 border border-blue-200 rounded">
                                    <span class="font-medium text-blue-800" x-text="getResponsableNombre(area)"></span>
                                    <br>
                                    <span class="text-sm text-blue-600" x-text="area.cargo_responsable"></span>
                                    <input type="hidden" :name="'responsables[' + index + ']'" :value="area.responsable?.id">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Campos ocultos para enviar datos -->
            <template x-for="(area, index) in areasSeleccionadas" :key="index">
                <div>
                    <input type="hidden" :name="'steps[' + index + '][nombre]'" :value="area.nombre">
                    <input type="hidden" :name="'steps[' + index + '][gerencia_id]'" :value="area.id">
                    <input type="hidden" :name="'steps[' + index + '][orden]'" :value="index + 1">
                    <!-- tiempo_limite_dias ya se envía desde el input visible en cada paso -->
                </div>
            </template>

            <!-- Los datos se envían a través del tipo_tramite_id y los steps -->

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('workflows.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed"
                        :disabled="!selectedTipoTramite || areasSeleccionadas.length === 0">
                    Crear Flujo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function tramiteFlowForm() {
    return {
        selectedTipoTramite: '',
        tramiteName: '',
        tiposTramite: @json($tiposTramite),
        gerenciasPrincipales: @json($gerenciasPrincipales),
        todasGerencias: @json($todasGerencias),
        tiposDocumento: [], // Se cargarán dinámicamente desde el tipo de trámite seleccionado
        gerenciasDisponibles: [], // Gerencias filtradas por tipo de trámite
        areasSeleccionadas: [],
        usuarios: @json($usuarios),
        selectedTramiteInfo: null,
        
        // Cargar gerencias disponibles para el trámite seleccionado
        loadGerenciasForTramite() {
            if (!this.selectedTipoTramite) {
                this.gerenciasDisponibles = [];
                this.selectedTramiteInfo = null;
                this.areasSeleccionadas = [];
                this.tiposDocumento = []; // Limpiar documentos cuando no hay trámite
                return;
            }

            // Buscar el tipo de trámite seleccionado
            const tipoTramite = this.tiposTramite.find(t => t.id == this.selectedTipoTramite);
            if (!tipoTramite) return;

            // Cargar los documentos asociados a este tipo de trámite
            // Laravel devuelve las relaciones en camelCase: tiposDocumentos
            this.tiposDocumento = tipoTramite.tipos_documentos || tipoTramite.tiposDocumentos || [];
            console.log('Documentos cargados para el trámite:', this.tiposDocumento);

            // Actualizar información del trámite
            this.selectedTramiteInfo = {
                nombre: tipoTramite.nombre,
                costo: tipoTramite.costo,
                tiempo: tipoTramite.tiempo_estimado_dias,
                gerencia: tipoTramite.gerencia ? tipoTramite.gerencia.nombre : 'No asignada'
            };
            this.tramiteName = tipoTramite.nombre;

            // Si el trámite tiene una gerencia específica, mostrar solo esa CON SUS SUBGERENCIAS
            if (tipoTramite.gerencia_id) {
                // Buscar primero en gerencias principales
                let gerenciaEspecifica = this.gerenciasPrincipales.find(g => g.id == tipoTramite.gerencia_id);
                
                // Si no la encuentra, buscar en todas las gerencias (puede ser una subgerencia)
                if (!gerenciaEspecifica) {
                    gerenciaEspecifica = this.todasGerencias.find(g => g.id == tipoTramite.gerencia_id);
                    
                    // Si es una subgerencia, obtener su gerencia padre
                    if (gerenciaEspecifica && gerenciaEspecifica.gerencia_padre_id) {
                        const gerenciaPadre = this.gerenciasPrincipales.find(g => g.id == gerenciaEspecifica.gerencia_padre_id);
                        this.gerenciasDisponibles = gerenciaPadre ? [gerenciaPadre] : [];
                    }
                } else {
                    // Es una gerencia principal, incluir con sus subgerencias
                    this.gerenciasDisponibles = [gerenciaEspecifica];
                }
                
                console.log('Gerencia específica encontrada:', gerenciaEspecifica);
                console.log('Gerencias disponibles:', this.gerenciasDisponibles);
            } else {
                // Si no tiene gerencia específica, mostrar todas
                this.gerenciasDisponibles = this.gerenciasPrincipales;
            }

            // Limpiar selecciones anteriores
            this.areasSeleccionadas = [];
        },
        
        elegirGerencia(gerencia) {
            console.log('=== ELIGIENDO GERENCIA ===');
            console.log('Gerencia:', gerencia);
            console.log('Responsable directo:', gerencia.responsable);
            console.log('Responsable ID:', gerencia.responsable_id);
            console.log('Usuarios de la gerencia:', gerencia.usuarios);
            
            if (!this.isGerenciaSelected(gerencia.id)) {
                // Buscar responsable en este orden:
                // 1. Responsable directo (campo responsable_id)
                // 2. Primer usuario asignado a esta gerencia
                // 3. Buscar en lista global de usuarios por gerencia_id
                let responsable = gerencia.responsable;
                
                // Si no tiene responsable directo, tomar el primer usuario de la gerencia
                if (!responsable && gerencia.usuarios && gerencia.usuarios.length > 0) {
                    responsable = gerencia.usuarios[0];
                    console.log('Responsable tomado del primer usuario de la gerencia:', responsable);
                }
                
                // Si aún no hay responsable, buscar en la lista global de usuarios por gerencia_id
                if (!responsable) {
                    responsable = this.usuarios.find(u => u.gerencia_id == gerencia.id);
                    console.log('Responsable encontrado en lista global por gerencia_id:', responsable);
                }
                
                this.areasSeleccionadas.push({
                    id: gerencia.id,
                    nombre: gerencia.nombre,
                    gerencia_padre_id: gerencia.gerencia_padre_id || null,
                    responsable: responsable || null,
                    cargo_responsable: responsable ? this.getRolNombre(responsable.rol) : 'Sin asignar',
                    tiempo_limite_dias: 3, // Valor por defecto: 3 días
                    showDocuments: false, // Control para mostrar/ocultar documentos
                    documentos: [] // Documentos seleccionados para este paso
                });
                
                console.log('Área agregada:', this.areasSeleccionadas[this.areasSeleccionadas.length - 1]);
            }
        },
        
        updateAreaDocuments(index, documentoId, isChecked) {
            if (!this.areasSeleccionadas[index].documentos) {
                this.areasSeleccionadas[index].documentos = [];
            }
            
            if (isChecked) {
                if (!this.areasSeleccionadas[index].documentos.includes(documentoId)) {
                    this.areasSeleccionadas[index].documentos.push(documentoId);
                }
            } else {
                const docIndex = this.areasSeleccionadas[index].documentos.indexOf(documentoId);
                if (docIndex > -1) {
                    this.areasSeleccionadas[index].documentos.splice(docIndex, 1);
                }
            }
            
            console.log('Documentos actualizados para área', index, ':', this.areasSeleccionadas[index].documentos);
        },
        
        removerArea(index) {
            this.areasSeleccionadas.splice(index, 1);
        },
        
        isGerenciaSelected(gerenciaId) {
            return this.areasSeleccionadas.some(area => area.id === gerenciaId);
        },
        
        updateTramiteName() {
            const select = document.getElementById('tipo_tramite_id');
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.dataset.nombre) {
                this.tramiteName = selectedOption.dataset.nombre;
            }
        },
        
        getSubgerenciasCount(gerencia) {
            return gerencia.subgerencias ? gerencia.subgerencias.length : 0;
        },
        
        getResponsableNombre(area) {
            if (area.responsable && area.responsable.name) {
                return area.responsable.name;
            }
            return 'Sin responsable asignado';
        },
        
        getRolNombre(rol) {
            const roles = {
                'superadministrador': 'Super Administrador',
                'administrador': 'Administrador',
                'jefe_gerencia': 'Jefe de Gerencia',
                'gerente': 'Gerente',
                'subgerente': 'Subgerente',
                'supervisor': 'Supervisor',
                'ciudadano': 'Ciudadano',
                'sin_rol': 'Sin rol asignado'
            };
            return roles[rol] || rol;
        },
        
        onFormSubmit(event) {
            console.log('=== FORMULARIO ENVIÁNDOSE ===');
            console.log('Tipo de Trámite ID:', this.selectedTipoTramite);
            console.log('Áreas Seleccionadas:', this.areasSeleccionadas);
            console.log('Cantidad de pasos:', this.areasSeleccionadas.length);
            
            if (!this.selectedTipoTramite) {
                event.preventDefault();
                alert('Por favor seleccione un tipo de trámite');
                return false;
            }
            
            if (this.areasSeleccionadas.length === 0) {
                event.preventDefault();
                alert('Por favor seleccione al menos una gerencia para el flujo');
                return false;
            }
            
            console.log('Formulario válido, enviando...');
            // El formulario se enviará normalmente
        }
    }
}
</script>
@endsection