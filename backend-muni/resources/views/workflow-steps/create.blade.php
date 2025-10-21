@extends('layouts.app')

@section('title', 'Nuevo Paso - ' . $workflow->nombre)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('workflows.index') }}" class="text-gray-600 hover:text-blue-600">Workflows</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('workflows.show', $workflow) }}" class="text-gray-600 hover:text-blue-600">{{ $workflow->nombre }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('workflow-steps.index', $workflow) }}" class="text-gray-600 hover:text-blue-600">Pasos</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-500">Nuevo</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Nuevo Paso</h1>
            <p class="mt-2 text-gray-600">Agregar un nuevo paso al workflow</p>
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

        <form method="POST" action="{{ route('workflow-steps.store', $workflow) }}" class="space-y-8">
            @csrf
            
            <!-- Información Básica -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Información del Paso</h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nombre -->
                        <div class="sm:col-span-2">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">
                                Nombre del Paso <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Ej: Revisión de Documentos">
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Orden -->
                        <div>
                            <label for="orden" class="block text-sm font-medium text-gray-700">
                                Orden <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="orden" id="orden" value="{{ old('orden', $nextOrder) }}" required min="1"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('orden')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tiempo Límite -->
                        <div>
                            <label for="tiempo_limite_dias" class="block text-sm font-medium text-gray-700">
                                Tiempo Límite (días)
                            </label>
                            <input type="number" name="tiempo_limite_dias" id="tiempo_limite_dias" value="{{ old('tiempo_limite_dias', 3) }}" min="1"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('tiempo_limite_dias')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gerencia -->
                        <div>
                            <label for="gerencia_id" class="block text-sm font-medium text-gray-700">
                                Gerencia Responsable
                            </label>
                            <select name="gerencia_id" id="gerencia_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Seleccione una gerencia</option>
                                @foreach($gerencias as $gerencia)
                                    <option value="{{ $gerencia->id }}" {{ old('gerencia_id') == $gerencia->id ? 'selected' : '' }}>
                                        {{ $gerencia->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gerencia_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Usuario Responsable -->
                        <div>
                            <label for="usuario_responsable_id" class="block text-sm font-medium text-gray-700">
                                Usuario Responsable
                            </label>
                            <select name="usuario_responsable_id" id="usuario_responsable_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Seleccione un usuario</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('usuario_responsable_id') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }} ({{ $usuario->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('usuario_responsable_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="sm:col-span-2">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                      placeholder="Descripción detallada del paso...">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Checkboxes -->
                        <div class="sm:col-span-2 space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="requiere_aprobacion" id="requiere_aprobacion" value="1" 
                                       {{ old('requiere_aprobacion') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <label for="requiere_aprobacion" class="ml-2 text-sm text-gray-700">Requiere aprobación</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="es_final" id="es_final" value="1" 
                                       {{ old('es_final') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <label for="es_final" class="ml-2 text-sm text-gray-700">Es paso final</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('workflow-steps.index', $workflow) }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Crear Paso
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
