@extends('layouts.app')

@section('title', 'Crear Nuevo Rol')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Rol</h1>
            <p class="mt-2 text-gray-600">Crea un nuevo rol y asigna los permisos correspondientes</p>
        </div>

        <!-- Mensajes de error -->
        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <h3 class="font-medium">Se encontraron errores en el formulario:</h3>
                <ul class="list-disc pl-5 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('roles.store') }}" class="space-y-8">
            @csrf
            
            <!-- Información básica -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Rol</h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Rol *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Ej: editor, moderador, supervisor">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">Solo letras minúsculas, números y guiones bajos.</p>
                    </div>

                    <div>
                        <label for="display_name" class="block text-sm font-medium text-gray-700">Nombre para Mostrar *</label>
                        <input type="text" name="display_name" id="display_name" value="{{ old('display_name') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Ej: Editor de Contenido, Moderador, Supervisor">
                        @error('display_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Descripción opcional del rol">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Permisos -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Permisos</h3>
                
                @if(isset($permissions) && $permissions->count() > 0)
                    <div class="space-y-6">
                        @foreach($permissions as $module => $modulePermissions)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-900">
                                            {{ $moduleNames[$module] ?? ucfirst(str_replace('_', ' ', $module)) }}
                                        </h4>
                                        <div class="flex space-x-2">
                                            <button type="button" 
                                                    onclick="selectAllInModule('{{ $module }}')"
                                                    class="text-xs text-blue-600 hover:text-blue-500">
                                                Seleccionar Todos
                                            </button>
                                            <button type="button" 
                                                    onclick="deselectAllInModule('{{ $module }}')"
                                                    class="text-xs text-gray-500 hover:text-gray-400">
                                                Deseleccionar Todos
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                        @foreach($modulePermissions as $permission)
                                            <label class="relative flex items-start cursor-pointer">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}"
                                                           data-module="{{ $module }}"
                                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <span class="font-medium text-gray-700">{{ $permissionNames[$permission->name] ?? $permission->name }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay permisos disponibles</h3>
                        <p class="mt-1 text-sm text-gray-500">Primero debes crear algunos permisos.</p>
                    </div>
                @endif
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('roles.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Crear Rol
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function selectAllInModule(module) {
    const checkboxes = document.querySelectorAll(`input[data-module="${module}"]`);
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function deselectAllInModule(module) {
    const checkboxes = document.querySelectorAll(`input[data-module="${module}"]`);
    checkboxes.forEach(checkbox => checkbox.checked = false);
}
</script>
@endpush
@endsection