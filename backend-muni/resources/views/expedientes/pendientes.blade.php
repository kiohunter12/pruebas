@extends('layouts.app')

@section('title', 'Expedientes Pendientes')

@section('content')
<div class="bg-white shadow">
    <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8">
        <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
            <div class="min-w-0 flex-1">
                <div class="flex items-center">
                    <div class="hidden sm:block">
                        <nav class="flex space-x-4">
                            <a href="{{ route('expedientes.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                Todos
                            </a>
                            <span class="text-municipal-600 text-sm font-medium border-b-2 border-municipal-500 pb-1">
                                Pendientes
                            </span>
                            <a href="{{ route('expedientes.proceso') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                En Proceso
                            </a>
                            <a href="{{ route('expedientes.finalizados') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                Finalizados
                            </a>
                        </nav>
                    </div>
                </div>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Expedientes Pendientes
                </h2>
            </div>
            <div class="mt-6 flex space-x-3 md:ml-4 md:mt-0">
                <a href="{{ route('expedientes.create') }}" class="inline-flex items-center rounded-md bg-municipal-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-municipal-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-municipal-600">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nuevo Expediente
                </a>
            </div>
        </div>
    </div>
</div>

<div class="py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Filtros -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="{{ route('expedientes.pendientes') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Número de expediente, solicitante..."
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-municipal-500 focus:ring-municipal-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="gerencia_id" class="block text-sm font-medium text-gray-700">Gerencia</label>
                        <select name="gerencia_id" id="gerencia_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-municipal-500 focus:ring-municipal-500 sm:text-sm">
                            <option value="">Todas las gerencias</option>
                            <!-- Aquí se cargarían las gerencias desde el controller -->
                        </select>
                    </div>
                    
                    <div>
                        <label for="fecha_desde" class="block text-sm font-medium text-gray-700">Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-municipal-500 focus:ring-municipal-500 sm:text-sm">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-municipal-600 hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Pendientes</dt>
                                <dd class="text-lg font-medium text-gray-900">0</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Vencidos</dt>
                                <dd class="text-lg font-medium text-gray-900">0</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Por Revisar</dt>
                                <dd class="text-lg font-medium text-gray-900">0</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Próximos a Vencer</dt>
                                <dd class="text-lg font-medium text-gray-900">0</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de expedientes pendientes -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Listado de Expedientes Pendientes</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Expedientes que requieren atención o están en espera de procesamiento.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Expediente
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Solicitante
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipo de Trámite
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Ingreso
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Días Pendiente
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                No hay expedientes pendientes en este momento.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection