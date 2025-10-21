@extends('layouts.app')

@section('title', 'Crear Tipo de Trámite')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Crear Tipo de Trámite</h1>
                    <p class="mt-2 text-gray-600">Complete la información del nuevo tipo de trámite</p>
                </div>
                <a href="{{ route('tipos-tramite.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <!-- Mensajes de error -->
        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <div class="bg-white shadow-sm rounded-lg">
            <form method="POST" action="{{ route('tipos-tramite.store') }}" class="space-y-6 p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div class="md:col-span-2">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">
                            Nombre del Trámite <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                               required>
                    </div>

                    <!-- Código -->
                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700">
                            Código
                        </label>
                        <input type="text" name="codigo" id="codigo" value="{{ old('codigo') }}"
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                               placeholder="Se generará automáticamente si se deja vacío">
                    </div>

                    <!-- Gerencia -->
                    <div>
                        <label for="gerencia_id" class="block text-sm font-medium text-gray-700">
                            Gerencia Responsable <span class="text-red-500">*</span>
                        </label>
                        <select name="gerencia_id" id="gerencia_id" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Seleccione una gerencia</option>
                            @foreach($gerencias as $gerencia)
                                <option value="{{ $gerencia->id }}" {{ old('gerencia_id') == $gerencia->id ? 'selected' : '' }}>
                                    {{ $gerencia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Costo -->
                    <div>
                        <label for="costo" class="block text-sm font-medium text-gray-700">
                            Costo (S/) <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">S/</span>
                            </div>
                            <input type="number" name="costo" id="costo" step="0.01" min="0" value="{{ old('costo', '0.00') }}"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 rounded-md"
                                   required>
                        </div>
                    </div>

                    <!-- Tiempo Estimado -->
                    <div>
                        <label for="tiempo_estimado_dias" class="block text-sm font-medium text-gray-700">
                            Tiempo Estimado (días) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="tiempo_estimado_dias" id="tiempo_estimado_dias" min="1" max="365" value="{{ old('tiempo_estimado_dias', '5') }}"
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                               required>
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">
                        Descripción
                    </label>
                    <textarea name="descripcion" id="descripcion" rows="4"
                              class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                              placeholder="Descripción detallada del trámite">{{ old('descripcion') }}</textarea>
                </div>

                <!-- Documentos Requeridos -->
                @if(isset($tiposDocumento) && $tiposDocumento->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Documentos Requeridos
                    </label>
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                        @foreach($tiposDocumento as $tipoDoc)
                            <div class="flex items-center">
                                <input type="checkbox" name="documentos[]" id="doc_{{ $tipoDoc->id }}" 
                                       value="{{ $tipoDoc->id }}"
                                       {{ in_array($tipoDoc->id, old('documentos', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="doc_{{ $tipoDoc->id }}" class="ml-2 text-sm text-gray-700">
                                    {{ $tipoDoc->nombre }}
                                    @if($tipoDoc->descripcion)
                                        <span class="text-gray-500 text-xs">- {{ $tipoDoc->descripcion }}</span>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Opciones -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="requiere_pago" id="requiere_pago" value="1"
                               {{ old('requiere_pago') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="requiere_pago" class="ml-2 text-sm text-gray-700">
                            Requiere pago
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="activo" id="activo" value="1"
                               {{ old('activo', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="activo" class="ml-2 text-sm text-gray-700">
                            Activo
                        </label>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('tipos-tramite.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Crear Tipo de Trámite
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection