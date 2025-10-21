@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">Editar Usuario: {{ $usuario->name }}</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('usuarios.show', $usuario) }}" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                        Ver perfil
                    </a>
                    <a href="{{ route('usuarios.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                        Volver a la lista
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Información básica -->
                    <div class="col-span-2">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Información Personal</h4>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
                        <input type="text" name="dni" id="dni" value="{{ old('dni', $usuario->dni) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('dni')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $usuario->telefono) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('telefono')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contraseña (opcional en edición) -->
                    <div class="col-span-2 mt-6">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Cambiar contraseña (opcional)</h4>
                        <p class="text-sm text-gray-500 mb-4">Deja estos campos vacíos si no deseas cambiar la contraseña.</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                        <input type="password" name="password" id="password"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
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
                                <option value="{{ $gerencia->id }}" @selected(old('gerencia_id', $usuario->gerencia_id) == $gerencia->id)>
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
                            <option value="activo" @selected(old('estado', $usuario->estado) == 'activo')>Activo</option>
                            <option value="inactivo" @selected(old('estado', $usuario->estado) == 'inactivo')>Inactivo</option>
                            <option value="suspendido" @selected(old('estado', $usuario->estado) == 'suspendido')>Suspendido</option>
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
                                           @checked(in_array($role->name, old('roles', $usuario->roles->pluck('name')->toArray())))
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
                        Actualizar Usuario
                    </button>
                </div>
            </form>

            @if($usuario->id !== auth()->id() && !$usuario->hasRole('super_admin'))
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Acciones peligrosas</h4>
                    <div class="flex space-x-4">
                        <form method="POST" action="{{ route('usuarios.toggle-status', $usuario) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700"
                                    onclick="return confirm('¿Estás seguro de cambiar el estado de este usuario?')">
                                {{ $usuario->estado === 'activo' ? 'Desactivar usuario' : 'Activar usuario' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                    onclick="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                                Eliminar usuario
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection