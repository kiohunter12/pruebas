@extends('layouts.app')

@section('title', 'Editar Gerencia')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('gerencias.index') }}" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Gerencias</span>
                            <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 10h-1v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('gerencias.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Gerencias</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('gerencias.show', $gerencia) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ $gerencia->nombre }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-900">Editar</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="mt-4">
                <h1 class="text-3xl font-bold text-gray-900">Editar Gerencia</h1>
                <p class="mt-2 text-gray-600">Actualizar información de la gerencia: {{ $gerencia->nombre }}</p>
            </div>
        </div>

        <!-- Mensajes de error -->
        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mensajes de éxito -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            <form method="POST" action="{{ route('gerencias.update', $gerencia) }}">
                @csrf
                @method('PUT')
                
                <div class="px-4 py-6 sm:p-8">
                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        
                        <!-- Tipo de Gerencia -->
                        <div class="col-span-full">
                            <label for="tipo" class="block text-sm font-medium leading-6 text-gray-900">
                                Tipo <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <select id="tipo" name="tipo" 
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                        onchange="toggleGerenciaPadre()">
                                    <option value="">Seleccionar tipo</option>
                                    <option value="gerencia" {{ (old('tipo') ?? $gerencia->tipo) == 'gerencia' ? 'selected' : '' }}>Gerencia</option>
                                    <option value="subgerencia" {{ (old('tipo') ?? $gerencia->tipo) == 'subgerencia' ? 'selected' : '' }}>Subgerencia</option>
                                </select>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">Las subgerencias deben estar asociadas a una gerencia padre</p>
                            
                            @if($gerencia->subgerencias && $gerencia->subgerencias->count() > 0)
                                <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">Advertencia</h3>
                                            <p class="mt-1 text-sm text-yellow-700">
                                                Esta gerencia tiene {{ $gerencia->subgerencias->count() }} subgerencia(s) asociada(s). 
                                                Cambiar el tipo puede afectar la estructura organizacional.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Gerencia Padre (solo para subgerencias) -->
                        <div class="col-span-full" id="gerencia_padre_div" 
                             style="display: {{ (old('tipo') ?? $gerencia->tipo) == 'subgerencia' ? 'block' : 'none' }};">
                            <label for="gerencia_padre_id" class="block text-sm font-medium leading-6 text-gray-900">
                                Gerencia Padre <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <select id="gerencia_padre_id" name="gerencia_padre_id" 
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        {{ (old('tipo') ?? $gerencia->tipo) == 'subgerencia' ? 'required' : '' }}>
                                    <option value="">Seleccionar gerencia padre</option>
                                    @if(isset($gerenciasPadre))
                                        @foreach($gerenciasPadre as $gerenciaPadre)
                                            <option value="{{ $gerenciaPadre->id }}" 
                                                    {{ (old('gerencia_padre_id') ?? $gerencia->gerencia_padre_id) == $gerenciaPadre->id ? 'selected' : '' }}>
                                                {{ $gerenciaPadre->nombre }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div class="col-span-full">
                            <label for="nombre" class="block text-sm font-medium leading-6 text-gray-900">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') ?? $gerencia->nombre }}" 
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                       placeholder="Ej: Gerencia de Desarrollo Urbano" required>
                            </div>
                        </div>

                        <!-- Código -->
                        <div class="sm:col-span-3">
                            <label for="codigo" class="block text-sm font-medium leading-6 text-gray-900">
                                Código <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input type="text" name="codigo" id="codigo" value="{{ old('codigo') ?? $gerencia->codigo }}" 
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                       placeholder="Ej: GDU-001" required
                                       style="text-transform: uppercase;">
                            </div>
                            <p class="mt-2 text-sm text-gray-600">Código único de identificación (se convertirá a mayúsculas)</p>
                        </div>

                        <!-- Estado -->
                        <div class="sm:col-span-3">
                            <label for="activo" class="block text-sm font-medium leading-6 text-gray-900">
                                Estado
                            </label>
                            <div class="mt-2">
                                <select id="activo" name="activo" 
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                                    <option value="1" {{ (old('activo') ?? $gerencia->activo) == '1' ? 'selected' : '' }}>Activa</option>
                                    <option value="0" {{ (old('activo') ?? $gerencia->activo) == '0' ? 'selected' : '' }}>Inactiva</option>
                                </select>
                            </div>
                            
                            @if($gerencia->users && $gerencia->users->count() > 0)
                                <p class="mt-2 text-sm text-yellow-600">
                                    <svg class="inline h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Tiene {{ $gerencia->users->count() }} usuario(s) asignado(s)
                                </p>
                            @endif
                        </div>

                        <!-- Descripción -->
                        <div class="col-span-full">
                            <label for="descripcion" class="block text-sm font-medium leading-6 text-gray-900">
                                Descripción
                            </label>
                            <div class="mt-2">
                                <textarea id="descripcion" name="descripcion" rows="3" 
                                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                          placeholder="Descripción opcional de las funciones y responsabilidades">{{ old('descripcion') ?? $gerencia->descripcion }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">Breve descripción de las funciones de la gerencia (opcional)</p>
                        </div>

                        <!-- Información adicional -->
                        <div class="col-span-full">
                            <div class="rounded-md bg-blue-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Información de la gerencia</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li>Fecha de creación: {{ $gerencia->created_at->format('d/m/Y H:i') }}</li>
                                                <li>Última actualización: {{ $gerencia->updated_at->format('d/m/Y H:i') }}</li>
                                                @if($gerencia->users)
                                                    <li>Usuarios asignados: {{ $gerencia->users->count() }}</li>
                                                @endif
                                                @if($gerencia->subgerencias)
                                                    <li>Subgerencias: {{ $gerencia->subgerencias->count() }}</li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-between border-t border-gray-900/10 px-4 py-4 sm:px-8">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('gerencias.show', $gerencia) }}" 
                           class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">
                            Ver Detalles
                        </a>
                        <a href="{{ route('gerencias.index') }}" 
                           class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">
                            Volver al Listado
                        </a>
                    </div>
                    <div class="flex items-center gap-x-6">
                        <button type="button" onclick="window.history.back()" 
                                class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            Actualizar Gerencia
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleGerenciaPadre() {
    const tipo = document.getElementById('tipo').value;
    const gerenciaPadreDiv = document.getElementById('gerencia_padre_div');
    const gerenciaPadreSelect = document.getElementById('gerencia_padre_id');
    
    if (tipo === 'subgerencia') {
        gerenciaPadreDiv.style.display = 'block';
        gerenciaPadreSelect.setAttribute('required', 'required');
    } else {
        gerenciaPadreDiv.style.display = 'none';
        gerenciaPadreSelect.removeAttribute('required');
        gerenciaPadreSelect.value = '';
    }
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Convertir código a mayúsculas
    document.getElementById('codigo').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
@endsection