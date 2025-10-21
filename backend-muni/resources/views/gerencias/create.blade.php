@extends('layouts.app')

@section('title', 'Nueva Gerencia')

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
                            <span class="ml-4 text-sm font-medium text-gray-900">Nueva Gerencia</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="mt-4">
                <h1 class="text-3xl font-bold text-gray-900">Nueva Gerencia</h1>
                <p class="mt-2 text-gray-600">Crear una nueva gerencia o subgerencia en el sistema</p>
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

        <!-- Formulario -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            <form method="POST" action="{{ route('gerencias.store') }}">
                @csrf
                
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
                                    <option value="gerencia" {{ old('tipo') == 'gerencia' ? 'selected' : '' }}>Gerencia</option>
                                    <option value="subgerencia" {{ old('tipo') == 'subgerencia' ? 'selected' : '' }}>Subgerencia</option>
                                </select>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">Las subgerencias deben estar asociadas a una gerencia padre</p>
                        </div>

                        <!-- Gerencia Padre (solo para subgerencias) -->
                        <div class="col-span-full" id="gerencia_padre_div" style="display: none;">
                            <label for="gerencia_padre_id" class="block text-sm font-medium leading-6 text-gray-900">
                                Gerencia Padre <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <select id="gerencia_padre_id" name="gerencia_padre_id" 
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar gerencia padre</option>
                                    @if(isset($gerenciasPadre))
                                        @foreach($gerenciasPadre as $gerenciaPadre)
                                            <option value="{{ $gerenciaPadre->id }}" {{ old('gerencia_padre_id') == $gerenciaPadre->id ? 'selected' : '' }}>
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
                                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" 
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                       placeholder="Ej: Gerencia de Desarrollo Urbano">
                            </div>
                        </div>

                        <!-- Código -->
                        <div class="sm:col-span-3">
                            <label for="codigo" class="block text-sm font-medium leading-6 text-gray-900">
                                Código <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input type="text" name="codigo" id="codigo" value="{{ old('codigo') }}" 
                                       class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                       placeholder="Ej: GDU-001"
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
                                    <option value="1" {{ old('activo', '1') == '1' ? 'selected' : '' }}>Activa</option>
                                    <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>Inactiva</option>
                                </select>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="col-span-full">
                            <label for="descripcion" class="block text-sm font-medium leading-6 text-gray-900">
                                Descripción
                            </label>
                            <div class="mt-2">
                                <textarea id="descripcion" name="descripcion" rows="3" 
                                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                          placeholder="Descripción opcional de las funciones y responsabilidades">{{ old('descripcion') }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">Breve descripción de las funciones de la gerencia (opcional)</p>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                    <a href="{{ route('gerencias.index') }}" 
                       class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Crear Gerencia
                    </button>
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
    toggleGerenciaPadre();
    
    // Convertir código a mayúsculas
    document.getElementById('codigo').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>
@endsection