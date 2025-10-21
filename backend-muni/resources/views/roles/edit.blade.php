@extends('layouts.app')

@section('title', 'Editar Rol: ' . $role->name)

@section('content')
<div class="bg-white shadow">
    <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8">
        <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
            <div class="min-w-0 flex-1">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <div>
                                <a href="{{ route('roles.index') }}" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="sr-only">Roles</span>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <a href="{{ route('roles.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Roles</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">Editar: {{ $role->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Editar Rol: {{ ucfirst($role->name) }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Modifica la información del rol y sus permisos
                </p>
            </div>
        </div>
    </div>
</div>

<div class="py-10">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        @if($errors->any())
            <div class="mb-6 rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Se encontraron errores en el formulario:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('roles.update', $role) }}" class="space-y-8">
            @csrf
            @method('PUT')
            
            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Información del Rol</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Información básica del rol.
                        </p>
                        @if(in_array($role->name, ['super_admin', 'admin']))
                            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            Este es un rol del sistema. Algunos campos pueden estar restringidos.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="mt-5 space-y-6 md:col-span-2 md:mt-0">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Rol *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                                       {{ in_array($role->name, ['super_admin', 'admin']) ? 'readonly' : '' }}
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm {{ in_array($role->name, ['super_admin', 'admin']) ? 'bg-gray-100' : '' }}"
                                       placeholder="Ej: editor, moderador, supervisor">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @if(!in_array($role->name, ['super_admin', 'admin']))
                                    <p class="mt-2 text-sm text-gray-500">Solo letras minúsculas, números y guiones bajos.</p>
                                @endif
                            </div>

                            <div>
                                <label for="display_name" class="block text-sm font-medium text-gray-700">Nombre para Mostrar *</label>
                                <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $role->display_name ?? ucfirst($role->name)) }}" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm"
                                       placeholder="Ej: Editor de Contenido, Moderador, Supervisor">
                                @error('display_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea id="description" name="description" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm"
                                          placeholder="Descripción del rol y sus responsabilidades...">{{ old('description', $role->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Permisos</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Selecciona los permisos que tendrá este rol.
                        </p>
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <p class="text-sm text-blue-700">
                                <strong>{{ $role->permissions->count() }}</strong> permisos actualmente asignados
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:col-span-2 md:mt-0">
                        @if(isset($permissions) && $permissions->count() > 0)
                            <div class="space-y-6">
                                @foreach($permissions as $module => $modulePermissions)
                                    <div class="border border-gray-200 rounded-lg">
                                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    {{ $moduleNames[$module] ?? ucfirst(str_replace('_', ' ', $module)) }}
                                                    <span class="ml-2 text-xs text-gray-500">
                                                        ({{ $modulePermissions->intersect($role->permissions)->count() }}/{{ $modulePermissions->count() }})
                                                    </span>
                                                </h4>
                                                <div class="flex space-x-2">
                                                    <button type="button" 
                                                            onclick="selectAllInModule('{{ $module }}')"
                                                            class="text-xs text-municipal-600 hover:text-municipal-500">
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
                                                                   {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}
                                                                   class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded">
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <span class="font-medium text-gray-700">{{ $permissionNames[$permission->name] ?? $permission->name }}</span>
                                                            @if($permission->description ?? false)
                                                                <p class="text-gray-500">{{ $permission->description }}</p>
                                                            @endif
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
                </div>
            </div>

            <div class="flex justify-between">
                <div>
                    @if(!in_array($role->name, ['super_admin', 'admin']))
                        <button type="button" 
                                onclick="confirmDelete()"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar Rol
                        </button>
                    @endif
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('roles.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-municipal-600 hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Actualizar Rol
                    </button>
                </div>
            </div>
        </form>

        @if(!in_array($role->name, ['super_admin', 'admin']))
            <form id="delete-form" method="POST" action="{{ route('roles.destroy', $role) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</div>

<script>
function selectAllInModule(module) {
    const checkboxes = document.querySelectorAll(`input[data-module="${module}"]`);
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function deselectAllInModule(module) {
    const checkboxes = document.querySelectorAll(`input[data-module="${module}"]`);
    checkboxes.forEach(checkbox => checkbox.checked = false);
}

function confirmDelete() {
    if (confirm('¿Estás seguro de que quieres eliminar este rol? Esta acción no se puede deshacer.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection