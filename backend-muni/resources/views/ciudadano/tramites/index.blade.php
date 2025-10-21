@extends('layouts.app')

@section('title', 'Solicitar Trámite')

@section('content')
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
</style>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-file-alt mr-2"></i>Solicitar Nuevo Trámite
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
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Nuevo Trámite</span>
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
            
            <!-- Alertas de Éxito/Error -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow animate-fade-in" id="alert-success">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                <strong>¡Éxito!</strong> {{ session('success') }}
                            </p>
                            @if(session('expediente_numero'))
                                <p class="text-sm text-green-700 mt-1">
                                    Número de expediente: <strong class="font-bold">{{ session('expediente_numero') }}</strong>
                                </p>
                            @endif
                        </div>
                        <div class="ml-auto pl-3">
                            <button type="button" onclick="document.getElementById('alert-success').remove()" class="text-green-400 hover:text-green-600">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow animate-fade-in" id="alert-error">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                <strong>¡Error!</strong> {{ session('error') }}
                            </p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button type="button" onclick="document.getElementById('alert-error').remove()" class="text-red-400 hover:text-red-600">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow animate-fade-in" id="alert-errors">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-red-800">
                                Se encontraron los siguientes errores:
                            </h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="ml-auto pl-3">
                            <button type="button" onclick="document.getElementById('alert-errors').remove()" class="text-red-400 hover:text-red-600">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            
            <form id="tramite-form" action="{{ route('ciudadano.tramites.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Paso 1: Selector de Trámite -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-bold mr-3">1</span>
                            <h3 class="text-lg font-medium text-gray-900">Seleccionar Tipo de Trámite</h3>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <!-- Buscador -->
                        <div class="mb-4">
                            <label for="tramite-search" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search mr-2 text-blue-600"></i>Buscar trámite
                            </label>
                            <input type="text" 
                                   id="tramite-search" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="🔍 Escriba el nombre del trámite para buscar..."
                                   autocomplete="off">
                        </div>
                        
                        <!-- Selector -->
                        <div>
                            <label for="tramite-select" class="block text-sm font-medium text-gray-700 mb-2">
                                O seleccione de la lista
                            </label>
                            <select id="tramite-select" 
                                    name="tipo_tramite_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                    data-tramites='@json($tiposTramite)'
                                    required>
                                <option value="">-- Seleccione un trámite --</option>
                                @foreach($tiposTramite as $tramite)
                                    <option value="{{ $tramite->id }}" 
                                            data-nombre="{{ strtolower($tramite->nombre) }}"
                                            data-descripcion="{{ strtolower($tramite->descripcion ?? '') }}">
                                        {{ $tramite->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Información del Trámite Seleccionado -->
                <div id="tramite-info" style="display: none;">
                    
                    <!-- Paso 2: Información del Trámite -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-white text-blue-600 font-bold mr-3">2</span>
                                    <h3 class="text-lg font-medium text-white">Información del Trámite</h3>
                                </div>
                                <button type="button" 
                                        onclick="cambiarTramite()" 
                                        class="px-3 py-1 bg-white text-blue-600 rounded-lg hover:bg-gray-100 transition text-sm font-medium">
                                    <i class="fas fa-exchange-alt mr-1"></i>Cambiar
                                </button>
                            </div>
                        </div>
                        <div class="px-6 py-4">
                            <div class="mb-4">
                                <h4 class="text-xl font-bold text-gray-900 mb-2" id="tramite-nombre"></h4>
                                <p class="text-gray-600" id="tramite-descripcion"></p>
                            </div>
                            
                            <!-- Stats Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div class="bg-blue-50 p-4 rounded-lg text-center">
                                    <i class="fas fa-building text-blue-600 text-2xl mb-2"></i>
                                    <div class="text-xs text-gray-500">Gerencia</div>
                                    <div class="font-bold text-gray-900 text-sm mt-1" id="tramite-gerencia"></div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg text-center">
                                    <i class="fas fa-clock text-green-600 text-2xl mb-2"></i>
                                    <div class="text-xs text-gray-500">Tiempo</div>
                                    <div class="font-bold text-green-600 text-sm mt-1" id="tramite-tiempo"></div>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded-lg text-center">
                                    <i class="fas fa-money-bill-wave text-yellow-600 text-2xl mb-2"></i>
                                    <div class="text-xs text-gray-500">Costo</div>
                                    <div class="font-bold text-yellow-600 text-sm mt-1" id="tramite-costo"></div>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg text-center">
                                    <i class="fas fa-credit-card text-purple-600 text-2xl mb-2"></i>
                                    <div class="text-xs text-gray-500">Pago</div>
                                    <div class="font-bold text-purple-600 text-sm mt-1" id="tramite-pago"></div>
                                </div>
                            </div>

                            <!-- Alerta de Pago Previo -->
                            <div id="pago-previo-alert" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4" style="display: none;">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            <strong>Importante:</strong> Este trámite requiere realizar el pago antes de continuar con el proceso.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Documentos Requeridos -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-medium text-gray-900 mb-3">
                                    <i class="fas fa-paperclip mr-2 text-blue-600"></i>
                                    Documentos Requeridos
                                </h5>
                                <div id="documentos-lista">
                                    <div class="flex items-center justify-center py-4 text-gray-500">
                                        <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Cargando documentos requeridos...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 3: Datos de la Solicitud -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-bold mr-3">3</span>
                                <h3 class="text-lg font-medium text-gray-900">Datos de la Solicitud</h3>
                            </div>
                        </div>
                        <div class="px-6 py-4">
                            <div class="space-y-4">
                                <div>
                                    <label for="asunto" class="block text-sm font-medium text-gray-700 mb-2">
                                        Asunto <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="asunto" 
                                           name="asunto"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                           placeholder="Ingrese el asunto de su trámite"
                                           maxlength="500"
                                           required>
                                    <p class="mt-1 text-xs text-gray-500">Máximo 500 caracteres</p>
                                </div>

                                <div>
                                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Descripción <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="descripcion" 
                                              name="descripcion"
                                              rows="4"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                              placeholder="Describa detalladamente su solicitud..."
                                              required></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Proporcione todos los detalles relevantes para su trámite</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 4: Adjuntar Documentos -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white font-bold mr-3">4</span>
                                <h3 class="text-lg font-medium text-gray-900">Adjuntar Documentos</h3>
                            </div>
                        </div>
                        <div class="px-6 py-4">
                            <!-- Contenedor dinámico de documentos -->
                            <div id="documentos-inputs-container">
                                <!-- Se llenará dinámicamente con JavaScript -->
                            </div>
                            
                            <!-- Mensaje si no hay documentos requeridos -->
                            <div id="no-documentos-message" style="display: none;">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p class="text-sm text-blue-800 mb-3">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>Este trámite no tiene documentos específicos requeridos.</strong><br>
                                        Puede adjuntar documentos adicionales si lo considera necesario.
                                    </p>
                                    
                                    <div class="border-2 border-dashed border-blue-300 rounded-lg p-4 text-center hover:border-blue-500 transition">
                                        <input type="file" 
                                               id="documentos-opcionales" 
                                               name="documentos_opcionales[]" 
                                               multiple 
                                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                               class="hidden"
                                               onchange="handleOptionalFiles(this)">
                                        <label for="documentos-opcionales" class="cursor-pointer">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-blue-500 mb-2"></i>
                                            <p class="text-sm font-medium text-gray-700 mb-1">
                                                Haga clic para adjuntar documentos opcionales
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                PDF, JPG, PNG, DOC, DOCX - Máx. 10MB por archivo
                                            </p>
                                        </label>
                                    </div>
                                    
                                    <div id="archivos-opcionales-lista" class="mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Revise toda la información antes de enviar
                                </div>
                                <div class="flex space-x-3">
                                    <button type="button" 
                                            onclick="window.location.href='{{ route('dashboard') }}'"
                                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                                        <i class="fas fa-times mr-2"></i>Cancelar
                                    </button>
                                    <button type="submit" 
                                            id="btn-enviar"
                                            class="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition shadow-lg">
                                        <i class="fas fa-paper-plane mr-2"></i>Enviar Solicitud
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje Inicial -->
                <div id="mensaje-inicial" class="bg-white shadow rounded-lg">
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Seleccione un tipo de trámite para comenzar</h3>
                        <p class="text-gray-500">
                            Use el buscador o el selector para elegir el trámite que desea realizar.<br>
                            Luego podrá ver los requisitos y completar su solicitud.
                        </p>
                    </div>
                </div>

            </form>

            <!-- Información de Ayuda -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Información importante
                </h4>
                <ul class="text-sm text-blue-800 space-y-1 ml-5 list-disc">
                    <li>Formatos aceptados: PDF, imágenes (JPG, PNG) o documentos Word (DOC, DOCX)</li>
                    <li>Tamaño máximo por documento: 10 MB</li>
                    <li>Podrá hacer seguimiento del trámite en "Mis Trámites"</li>
                    <li>Recibirá notificaciones sobre el estado de su solicitud</li>
                </ul>
            </div>

        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectTramite = document.getElementById('tramite-select');
    const searchTramite = document.getElementById('tramite-search');
    const tramiteInfo = document.getElementById('tramite-info');
    const mensajeInicial = document.getElementById('mensaje-inicial');
    const tramiteForm = document.getElementById('tramite-form');
    
    const tramitesData = JSON.parse(selectTramite.dataset.tramites);

    // Funcionalidad de búsqueda
    searchTramite.addEventListener('input', function(e) {
        const searchText = e.target.value.toLowerCase().trim();
        const options = selectTramite.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') return;
            
            const nombre = option.getAttribute('data-nombre') || '';
            const descripcion = option.getAttribute('data-descripcion') || '';
            
            if (nombre.includes(searchText) || descripcion.includes(searchText)) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
        
        const visibleOptions = Array.from(options).filter(opt => 
            opt.value !== '' && opt.style.display !== 'none'
        );
        
        if (visibleOptions.length === 1) {
            selectTramite.value = visibleOptions[0].value;
            selectTramite.dispatchEvent(new Event('change'));
        }
    });

    // Cambio de selección
    selectTramite.addEventListener('change', function() {
        const tramiteId = this.value;
        
        if (!tramiteId) {
            ocultarInfo();
            return;
        }

        const tramite = tramitesData.find(t => t.id == tramiteId);
        
        if (tramite) {
            mostrarInfoTramite(tramite);
            cargarDocumentosRequeridos(tramiteId);
        }
    });

    function mostrarInfoTramite(tramite) {
        document.getElementById('tramite-nombre').textContent = tramite.nombre;
        document.getElementById('tramite-descripcion').textContent = 
            tramite.descripcion || 'Sin descripción disponible';
        document.getElementById('tramite-gerencia').textContent = 
            tramite.gerencia ? tramite.gerencia.nombre : 'N/A';
        document.getElementById('tramite-tiempo').textContent = 
            tramite.tiempo_estimado_dias + ' días';
        document.getElementById('tramite-costo').textContent = 
            'S/. ' + parseFloat(tramite.costo).toFixed(2);
        
        const pagoBadge = document.getElementById('tramite-pago');
        const pagoAlert = document.getElementById('pago-previo-alert');
        
        if (tramite.requiere_pago) {
            pagoBadge.textContent = 'Previo';
            pagoAlert.style.display = 'block';
        } else {
            pagoBadge.textContent = 'No requiere';
            pagoAlert.style.display = 'none';
        }

        mensajeInicial.style.display = 'none';
        tramiteInfo.style.display = 'block';
        
        setTimeout(() => {
            tramiteInfo.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    }

    function ocultarInfo() {
        tramiteInfo.style.display = 'none';
        mensajeInicial.style.display = 'block';
        searchTramite.value = '';
        
        const options = selectTramite.querySelectorAll('option');
        options.forEach(option => {
            option.style.display = '';
        });
    }

    function cargarDocumentosRequeridos(tramiteId) {
        const documentosLista = document.getElementById('documentos-lista');
        const documentosInputsContainer = document.getElementById('documentos-inputs-container');
        const noDocumentosMessage = document.getElementById('no-documentos-message');
        
        // Buscar el trámite en los datos
        const tramite = tramitesData.find(t => t.id == tramiteId);
        
        console.log('Trámite seleccionado:', tramite); // DEBUG
        console.log('Documentos del trámite:', tramite?.documentos); // DEBUG
        
        if (!tramite) {
            documentosLista.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm text-red-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Error al cargar la información del trámite.
                    </p>
                </div>
            `;
            return;
        }
        
        // Verificar si tiene documentos
        if (tramite.documentos && tramite.documentos.length > 0) {
            // Mostrar lista informativa de documentos
            let htmlLista = '<div class="space-y-2">';
            tramite.documentos.forEach((doc, index) => {
                const requerido = doc.pivot?.requerido || false;
                htmlLista += `
                    <div class="flex items-start p-3 bg-white border border-gray-200 rounded-lg">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 font-bold text-sm mr-3 mt-0.5">${index + 1}</span>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <h6 class="font-medium text-gray-900">${doc.nombre}</h6>
                                ${requerido ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Obligatorio</span>' : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Opcional</span>'}
                            </div>
                            ${doc.descripcion ? `<p class="text-sm text-gray-500 mt-1">${doc.descripcion}</p>` : ''}
                            ${doc.requiere_firma ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1"><i class="fas fa-signature mr-1"></i>Requiere firma</span>' : ''}
                        </div>
                        <i class="fas fa-file-pdf text-red-500 text-xl ml-2"></i>
                    </div>
                `;
            });
            htmlLista += '</div>';
            documentosLista.innerHTML = htmlLista;
            
            // Crear inputs individuales para cada documento
            let htmlInputs = '<div class="space-y-4">';
            tramite.documentos.forEach((doc, index) => {
                const requerido = doc.pivot?.requerido || false;
                const requiredAttr = requerido ? 'required' : '';
                
                htmlInputs += `
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h6 class="font-medium text-gray-900">
                                        <span class="text-blue-600 mr-2">${index + 1}.</span>${doc.nombre}
                                    </h6>
                                    ${requerido ? 
                                        '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Obligatorio</span>' : 
                                        '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Opcional</span>'
                                    }
                                </div>
                                ${doc.descripcion ? `<p class="text-xs text-gray-500">${doc.descripcion}</p>` : ''}
                            </div>
                        </div>
                        
                        <input type="hidden" name="documentos[${index}][tipo_documento_id]" value="${doc.id}">
                        <input type="hidden" name="documentos[${index}][nombre]" value="${doc.nombre}">
                        <input type="hidden" name="documentos[${index}][requerido]" value="${requerido ? '1' : '0'}">
                        
                        <div class="relative">
                            <input type="file" 
                                   id="doc-${index}" 
                                   name="documentos[${index}][archivo]" 
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm"
                                   ${requiredAttr}
                                   onchange="showFilePreview(this, ${index})"
                                   data-doc-name="${doc.nombre}">
                        </div>
                        
                        <div id="preview-${index}" class="mt-2" style="display: none;">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-2 flex items-center justify-between">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    <span class="text-gray-700 file-name-preview"></span>
                                    <span class="text-gray-500 text-xs ml-2 file-size-preview"></span>
                                </div>
                                <button type="button" onclick="clearFile(${index})" class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        ${doc.requiere_firma ? 
                            '<p class="text-xs text-yellow-600 mt-2"><i class="fas fa-signature mr-1"></i>Este documento requiere firma</p>' : 
                            ''
                        }
                    </div>
                `;
            });
            htmlInputs += '</div>';
            documentosInputsContainer.innerHTML = htmlInputs;
            documentosInputsContainer.style.display = 'block';
            noDocumentosMessage.style.display = 'none';
            
        } else {
            // Sin documentos específicos
            documentosLista.innerHTML = `
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Sin requisitos específicos.</strong> Podrá adjuntar los documentos que considere necesarios.
                    </p>
                </div>
            `;
            documentosInputsContainer.style.display = 'none';
            noDocumentosMessage.style.display = 'block';
        }
    }

    // Función para mostrar preview de archivo individual
    window.showFilePreview = function(input, index) {
        const preview = document.getElementById('preview-' + index);
        const fileNamePreview = preview.querySelector('.file-name-preview');
        const fileSizePreview = preview.querySelector('.file-size-preview');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const size = (file.size / 1024 / 1024).toFixed(2);
            
            // Validar tamaño
            if (file.size > 10 * 1024 * 1024) {
                alert('El archivo "' + file.name + '" excede el tamaño máximo de 10MB');
                input.value = '';
                preview.style.display = 'none';
                return;
            }
            
            fileNamePreview.textContent = file.name;
            fileSizePreview.textContent = '(' + size + ' MB)';
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    };

    // Función para limpiar archivo individual
    window.clearFile = function(index) {
        const input = document.getElementById('doc-' + index);
        const preview = document.getElementById('preview-' + index);
        
        if (input) {
            input.value = '';
            preview.style.display = 'none';
        }
    };

    // Función para manejar archivos opcionales
    window.handleOptionalFiles = function(input) {
        const container = document.getElementById('archivos-opcionales-lista');
        container.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            let html = '<div class="space-y-2 mt-2">';
            Array.from(input.files).forEach((file, index) => {
                const size = (file.size / 1024 / 1024).toFixed(2);
                
                // Validar tamaño
                if (file.size > 10 * 1024 * 1024) {
                    alert('El archivo "' + file.name + '" excede el tamaño máximo de 10MB');
                    return;
                }
                
                html += `
                    <div class="flex items-center justify-between p-2 bg-white border border-gray-200 rounded">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-file text-blue-500 mr-2"></i>
                            <span class="text-gray-700">${file.name}</span>
                            <span class="text-gray-500 text-xs ml-2">(${size} MB)</span>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
        }
    };

    // Validación del formulario
    tramiteForm.addEventListener('submit', function(e) {
        // Validar que los documentos obligatorios estén adjuntos
        const requiredInputs = document.querySelectorAll('input[type="file"][required]');
        let allFilled = true;
        let missingDocs = [];
        
        requiredInputs.forEach(function(input) {
            if (!input.files || input.files.length === 0) {
                allFilled = false;
                const docName = input.getAttribute('data-doc-name');
                missingDocs.push(docName);
                input.classList.add('border-red-500');
            } else {
                input.classList.remove('border-red-500');
            }
        });
        
        if (!allFilled) {
            e.preventDefault();
            alert('Por favor, adjunte los siguientes documentos obligatorios:\n\n• ' + missingDocs.join('\n• '));
            return false;
        }
        
        const btnEnviar = document.getElementById('btn-enviar');
        btnEnviar.disabled = true;
        btnEnviar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
    });

    window.cambiarTramite = function() {
        selectTramite.value = '';
        ocultarInfo();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };
    
    // Auto-ocultar alertas después de 10 segundos
    setTimeout(function() {
        const alertSuccess = document.getElementById('alert-success');
        const alertError = document.getElementById('alert-error');
        const alertErrors = document.getElementById('alert-errors');
        
        if (alertSuccess) {
            alertSuccess.style.transition = 'opacity 0.5s';
            alertSuccess.style.opacity = '0';
            setTimeout(() => alertSuccess.remove(), 500);
        }
        
        if (alertError) {
            alertError.style.transition = 'opacity 0.5s';
            alertError.style.opacity = '0';
            setTimeout(() => alertError.remove(), 500);
        }
        
        if (alertErrors) {
            alertErrors.style.transition = 'opacity 0.5s';
            alertErrors.style.opacity = '0';
            setTimeout(() => alertErrors.remove(), 500);
        }
    }, 10000); // 10 segundos
});
</script>
@endsection
