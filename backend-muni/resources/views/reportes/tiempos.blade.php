@extends('layouts.app')

@section('title', 'Reportes de Tiempos de Respuesta')

@section('content')
<div class="bg-white shadow">
    <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8">
        <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
            <div class="min-w-0 flex-1">
                <div class="flex items-center">
                    <div class="hidden sm:block">
                        <nav class="flex space-x-4">
                            <a href="{{ route('reportes.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="{{ route('reportes.expedientes') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                Expedientes
                            </a>
                            <a href="{{ route('reportes.tramites') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                Trámites
                            </a>
                            <span class="text-municipal-600 text-sm font-medium border-b-2 border-municipal-500 pb-1">
                                Tiempos
                            </span>
                        </nav>
                    </div>
                </div>
                <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Análisis de Tiempos de Respuesta
                </h2>
            </div>
            <div class="mt-6 flex space-x-3 md:ml-4 md:mt-0">
                <button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Exportar PDF
                </button>
                <button type="button" class="inline-flex items-center rounded-md bg-municipal-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-municipal-500">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                    Exportar Excel
                </button>
            </div>
        </div>
    </div>
</div>

<div class="py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Filtros -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Filtros de Análisis</h3>
                <form method="GET" action="{{ route('reportes.tiempos') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="fecha_desde" class="block text-sm font-medium text-gray-700">Fecha Desde</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde', now()->subMonth()->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-municipal-500 focus:ring-municipal-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="fecha_hasta" class="block text-sm font-medium text-gray-700">Fecha Hasta</label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta', now()->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-municipal-500 focus:ring-municipal-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="tipo_tramite" class="block text-sm font-medium text-gray-700">Tipo de Trámite</label>
                        <select name="tipo_tramite" id="tipo_tramite" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-municipal-500 focus:ring-municipal-500 sm:text-sm">
                            <option value="">Todos los tipos</option>
                            <!-- Aquí se cargarían los tipos de trámite -->
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-municipal-600 hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                            Analizar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Métricas de tiempo -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Tiempo Promedio</dt>
                                <dd class="text-lg font-medium text-gray-900">0 días</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Más Rápido</dt>
                                <dd class="text-lg font-medium text-gray-900">0 días</dd>
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
                                <dt class="text-sm font-medium text-gray-500 truncate">Más Lento</dt>
                                <dd class="text-lg font-medium text-gray-900">0 días</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
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
        </div>

        <!-- Gráficos de análisis de tiempo -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Distribución de tiempos -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Distribución de Tiempos de Procesamiento</h3>
                    <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Histograma - Distribución de tiempos</p>
                    </div>
                </div>
            </div>

            <!-- Comparación por tipo de trámite -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tiempos Promedio por Tipo</h3>
                    <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                        <p class="text-gray-500">Gráfico de barras - Tiempo promedio por tipo</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evolución temporal -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Evolución de Tiempos en el Período</h3>
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                    <p class="text-gray-500">Gráfico de líneas - Evolución de tiempos promedio</p>
                </div>
            </div>
        </div>

        <!-- Tabla de SLA (Service Level Agreement) -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cumplimiento de SLA por Tipo de Trámite</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo de Trámite
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    SLA Definido
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tiempo Promedio
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dentro de SLA
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fuera de SLA
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    % Cumplimiento
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No hay datos disponibles para el período seleccionado.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cuellos de botella y oportunidades de mejora -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Cuellos de botella -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Principales Cuellos de Botella</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-red-800">Revisión de Documentos</p>
                                <p class="text-xs text-red-600">Tiempo promedio: 5.2 días</p>
                            </div>
                            <div class="text-red-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-center text-sm text-gray-500">
                            No hay datos suficientes para mostrar cuellos de botella
                        </div>
                    </div>
                </div>
            </div>

            <!-- Oportunidades de mejora -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Oportunidades de Mejora</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-green-800">Automatización de Notificaciones</p>
                                <p class="text-xs text-green-600">Potencial reducción: 1-2 días</p>
                            </div>
                            <div class="text-green-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-center text-sm text-gray-500">
                            No hay datos suficientes para mostrar oportunidades
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection