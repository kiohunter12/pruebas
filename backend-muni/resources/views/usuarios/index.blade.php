@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Gestión de Usuarios</h3>
                <a href="{{ route('usuarios.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nuevo Usuario
                </a>
            </div>

            <!-- Filtros -->
            <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar por nombre, email o DNI" 
                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2" />
                
                <select name="role" class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2">
                    <option value="">Todos los roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" @selected(request('role') == $role->name)>
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>

                <select name="gerencia_id" class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2">
                    <option value="">Todas las gerencias</option>
                    @foreach($gerencias as $gerencia)
                        <option value="{{ $gerencia->id }}" @selected(request('gerencia_id') == $gerencia->id)>
                            {{ $gerencia->nombre }}
                        </option>
                    @endforeach
                </select>

                <select name="estado" class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2">
                    <option value="">Todos los estados</option>
                    <option value="activo" @selected(request('estado') == 'activo')>Activo</option>
                    <option value="inactivo" @selected(request('estado') == 'inactivo')>Inactivo</option>
                    <option value="suspendido" @selected(request('estado') == 'suspendido')>Suspendido</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Filtrar</button>
                    <a href="{{ route('usuarios.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Limpiar</a>
                </div>
            </form>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <div class="text-sm text-blue-600">Total de usuarios</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $stats['total'] }}</div>
                </div>
                <div class="p-4 bg-green-50 rounded-lg">
                    <div class="text-sm text-green-600">Usuarios activos</div>
                    <div class="text-2xl font-bold text-green-900">{{ $stats['activos'] }}</div>
                </div>
                <div class="p-4 bg-red-50 rounded-lg">
                    <div class="text-sm text-red-600">Usuarios inactivos</div>
                    <div class="text-2xl font-bold text-red-900">{{ $stats['inactivos'] }}</div>
                </div>
                <div class="p-4 bg-yellow-50 rounded-lg">
                    <div class="text-sm text-yellow-600">Con roles asignados</div>
                    <div class="text-2xl font-bold text-yellow-900">{{ $stats['con_roles'] }}</div>
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gerencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registro</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($usuarios as $usuario)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ strtoupper(substr($usuario->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $usuario->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $usuario->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $usuario->dni ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $usuario->gerencia->nombre ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @forelse($usuario->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-400">Sin roles</span>
                                    @endforelse
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($usuario->estado === 'activo') bg-green-100 text-green-800
                                        @elseif($usuario->estado === 'inactivo') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $usuario->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('usuarios.show', $usuario) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('usuarios.edit', $usuario) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @if($usuario->id !== auth()->id() && !$usuario->hasRole('super_admin'))
                                            <form method="POST" action="{{ route('usuarios.toggle-status', $usuario) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-yellow-600 hover:text-yellow-900" 
                                                        title="{{ $usuario->estado === 'activo' ? 'Desactivar' : 'Activar' }}"
                                                        onclick="return confirm('¿Estás seguro de cambiar el estado de este usuario?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900" 
                                                        title="Eliminar"
                                                        onclick="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No se encontraron usuarios.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $usuarios->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" 
         x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" 
         x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
        <span>{{ session('error') }}</span>
    </div>
@endif
@endsection
