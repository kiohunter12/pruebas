@extends('layouts.app')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Header del perfil -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="h-16 w-16 bg-gray-300 rounded-full flex items-center justify-center mr-4">
                            <span class="text-xl font-medium text-gray-700">
                                {{ strtoupper(substr($usuario->name, 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $usuario->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $usuario->email }}</p>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($usuario->estado === 'activo') bg-green-100 text-green-800
                                    @elseif($usuario->estado === 'inactivo') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($usuario->estado) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        @can('update', $usuario)
                            <a href="{{ route('usuarios.edit', $usuario) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Editar
                            </a>
                        @endcan
                        <a href="{{ route('usuarios.index') }}" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            Volver a la lista
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información del usuario -->
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre completo</dt>
                                <dd class="text-sm text-gray-900">{{ $usuario->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Correo electrónico</dt>
                                <dd class="text-sm text-gray-900">{{ $usuario->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">DNI</dt>
                                <dd class="text-sm text-gray-900">{{ $usuario->dni ?? 'No especificado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="text-sm text-gray-900">{{ $usuario->telefono ?? 'No especificado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($usuario->estado) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Información Organizacional</h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Gerencia asignada</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $usuario->gerencia->nombre ?? 'Sin gerencia asignada' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de registro</dt>
                                <dd class="text-sm text-gray-900">{{ $usuario->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última actualización</dt>
                                <dd class="text-sm text-gray-900">{{ $usuario->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            @if($usuario->email_verified_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email verificado</dt>
                                    <dd class="text-sm text-green-600">
                                        <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Verificado el {{ $usuario->email_verified_at->format('d/m/Y') }}
                                    </dd>
                                </div>
                            @else
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email verificado</dt>
                                    <dd class="text-sm text-red-600">
                                        <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        No verificado
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Roles y permisos -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Roles y Permisos</h4>
                
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Roles -->
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 mb-3">Roles asignados</h5>
                        @forelse($usuario->roles as $role)
                            <div class="mb-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No tiene roles asignados</p>
                        @endforelse
                    </div>

                    <!-- Permisos -->
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 mb-3">Permisos efectivos</h5>
                        <div class="max-h-48 overflow-y-auto">
                            @forelse($usuario->getAllPermissions() as $permission)
                                <div class="mb-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $permission->name }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No tiene permisos asignados</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividad reciente (placeholder) -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Actividad Reciente</h4>
                <div class="space-y-3">
                    <!-- Placeholder para actividad futura -->
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Registro en el sistema: {{ $usuario->created_at->diffForHumans() }}
                    </div>
                    @if($usuario->updated_at != $usuario->created_at)
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Última actualización: {{ $usuario->updated_at->diffForHumans() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection