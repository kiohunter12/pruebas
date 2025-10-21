@extends('layouts.app')

@section('title', 'Gestión de Gerencias')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestión de Gerencias</h1>
                <p class="mt-2 text-gray-600">Administra las gerencias y subgerencias del sistema</p>
            </div>
            @can('gestionar_gerencias')
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('gerencias.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Nueva Gerencia
                </a>
            </div>
            @endcan
        </div>

        <!-- Mensajes -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Stats -->
        @if(isset($stats))
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Gerencias</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_gerencias'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Activas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['gerencias_activas'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4l-2 16h14L17 4M9 9v8m6-8v8" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Subgerencias</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['subgerencias_total'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Usuarios Asignados</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_usuarios_asignados'] ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Filtros de Búsqueda -->
        <div class="bg-white shadow rounded-lg mb-6 p-6">
            <form method="GET" action="{{ route('gerencias.index') }}" class="space-y-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtros de Búsqueda
                    </h3>
                    @if(request()->hasAny(['search', 'tipo', 'estado', 'gerencia_padre']))
                        <a href="{{ route('gerencias.index') }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpiar Filtros
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Búsqueda por nombre o código -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                            Buscar
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   id="search" 
                                   value="{{ request('search') }}"
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                   placeholder="Nombre o código...">
                        </div>
                    </div>

                    <!-- Filtro por tipo -->
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">
                            Tipo
                        </label>
                        <select name="tipo" 
                                id="tipo" 
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Todos</option>
                            <option value="gerencia" {{ request('tipo') === 'gerencia' ? 'selected' : '' }}>
                                Gerencias
                            </option>
                            <option value="subgerencia" {{ request('tipo') === 'subgerencia' ? 'selected' : '' }}>
                                Subgerencias
                            </option>
                        </select>
                    </div>

                    <!-- Filtro por estado -->
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">
                            Estado
                        </label>
                        <select name="estado" 
                                id="estado" 
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Todos</option>
                            <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>
                                Activas
                            </option>
                            <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>
                                Inactivas
                            </option>
                        </select>
                    </div>

                    <!-- Filtro por gerencia padre (solo para subgerencias) -->
                    <div>
                        <label for="gerencia_padre" class="block text-sm font-medium text-gray-700 mb-1">
                            Gerencia Padre
                        </label>
                        <select name="gerencia_padre" 
                                id="gerencia_padre" 
                                class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Todas las gerencias</option>
                            @foreach(\App\Models\Gerencia::where('tipo', 'gerencia')->where('activo', true)->orderBy('nombre')->get() as $gp)
                                <option value="{{ $gp->id }}" {{ request('gerencia_padre') == $gp->id ? 'selected' : '' }}>
                                    {{ $gp->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center space-x-3 pt-2">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Buscar
                    </button>
                    
                    @if(request()->hasAny(['search', 'tipo', 'estado', 'gerencia_padre']))
                        <span class="text-sm text-gray-600">
                            Mostrando resultados filtrados
                        </span>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabla de Gerencias -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Lista de Gerencias</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            @if(request()->hasAny(['search', 'tipo', 'estado', 'gerencia_padre']))
                                Mostrando {{ $gerencias->total() }} resultado(s) de {{ $gerencias->total() }} filtrado(s)
                            @else
                                Gerencias principales y subgerencias del sistema ({{ $gerencias->total() }} total)
                            @endif
                        </p>
                    </div>
                    @if($gerencias->total() > 0)
                        <div class="text-sm text-gray-500">
                            Página {{ $gerencias->currentPage() }} de {{ $gerencias->lastPage() }}
                        </div>
                    @endif
                </div>
            </div>

            @if($gerencias->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($gerencias as $gerencia)
                        <li class="px-4 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0 flex-1">
                                    <!-- Icono de tipo -->
                                    <div class="flex-shrink-0 mr-4">
                                        @if($gerencia->tipo === 'gerencia')
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2M7 4h10M7 4l-2 16h14L17 4" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center">
                                            <h4 class="text-sm font-medium text-gray-900 truncate">
                                                {{ $gerencia->nombre }}
                                            </h4>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $gerencia->tipo === 'gerencia' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ ucfirst($gerencia->tipo) }}
                                            </span>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $gerencia->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $gerencia->activo ? 'Activa' : 'Inactiva' }}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <p class="text-sm text-gray-600">
                                                Código: <span class="font-medium">{{ $gerencia->codigo }}</span>
                                                @if($gerencia->gerencia_padre_id && $gerencia->gerenciaPadre)
                                                    | Pertenece a: <span class="font-medium">{{ $gerencia->gerenciaPadre->nombre }}</span>
                                                @endif
                                            </p>
                                        </div>
                                        @if($gerencia->descripcion)
                                            <p class="mt-1 text-sm text-gray-500">{{ $gerencia->descripcion }}</p>
                                        @endif
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                            </svg>
                                            {{ $gerencia->users_count ?? 0 }} usuario(s) asignado(s)
                                        </div>
                                    </div>
                                </div>

                                <!-- Acciones -->
                                <div class="flex items-center space-x-2">
                                    <!-- Ver -->
                                    <a href="{{ route('gerencias.show', $gerencia) }}" 
                                       class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-gray-500 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                       title="Ver detalles">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    @can('editar_gerencias')
                                    <!-- Editar -->
                                    <a href="{{ route('gerencias.edit', $gerencia) }}" 
                                       class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                       title="Editar">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <!-- Toggle Status -->
                                    <form method="POST" action="{{ route('gerencias.toggle-status', $gerencia) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-sm font-medium {{ $gerencia->activo ? 'text-red-600 bg-red-50 hover:bg-red-100' : 'text-green-600 bg-green-50 hover:bg-green-100' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                title="{{ $gerencia->activo ? 'Desactivar' : 'Activar' }}"
                                                onclick="return confirm('¿Está seguro de {{ $gerencia->activo ? 'desactivar' : 'activar' }} esta gerencia?')">
                                            @if($gerencia->activo)
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                </svg>
                                            @else
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    @endcan

                                    @can('eliminar_gerencias')
                                    <!-- Eliminar -->
                                    <form method="POST" action="{{ route('gerencias.destroy', $gerencia) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                title="Eliminar"
                                                onclick="return confirm('¿Está seguro de eliminar esta gerencia? Esta acción no se puede deshacer.')">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <!-- Paginación -->
                @if(method_exists($gerencias, 'links'))
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $gerencias->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay gerencias</h3>
                    <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva gerencia.</p>
                    @can('gestionar_gerencias')
                    <div class="mt-6">
                        <a href="{{ route('gerencias.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nueva Gerencia
                        </a>
                    </div>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
