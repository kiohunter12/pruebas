@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">Crear Nuevo Usuario</h3>
                <a href="{{ route('usuarios.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                    Volver a la lista
                </a>
            </div>

            <form method="POST" action="{{ route('usuarios.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Información básica -->
                    <div class="col-span-2">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Información Personal</h4>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
                        <input type="text" name="dni" id="dni" value="{{ old('dni') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('dni')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('telefono')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="col-span-2 mt-6">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Credenciales de acceso</h4>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input type="password" name="password" id="password" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <!-- Asignaciones organizacionales -->
                    <div class="col-span-2 mt-6">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Asignaciones organizacionales</h4>
                    </div>

                    <div>
                        <label for="gerencia_id" class="block text-sm font-medium text-gray-700">Gerencia</label>
                        <select name="gerencia_id" id="gerencia_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Sin gerencia asignada</option>
                            @foreach($gerencias as $gerencia)
                                <option value="{{ $gerencia->id }}" @selected(old('gerencia_id') == $gerencia->id)>
                                    {{ $gerencia->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('gerencia_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" id="estado" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="activo" @selected(old('estado') == 'activo' || old('estado') === null)>Activo</option>
                            <option value="inactivo" @selected(old('estado') == 'inactivo')>Inactivo</option>
                            <option value="suspendido" @selected(old('estado') == 'suspendido')>Suspendido</option>
                        </select>
                        @error('estado')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Roles -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Roles del sistema</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($roles as $role)
                                <div class="flex items-center">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                           id="role_{{ $role->id }}"
                                           @checked(in_array($role->name, old('roles', [])))
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="role_{{ $role->id }}" class="ml-2 block text-sm text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('roles')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('usuarios.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection