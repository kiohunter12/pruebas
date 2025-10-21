@extends('layouts.app')

@section('title', 'Expedientes')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Expedientes</h3>
                </div>
                <a href="{{ route('expedientes.create') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo expediente
                </a>
            </div>

            <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por número, asunto o solicitante" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2" />
                <select name="gerencia_id" class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2">
                    <option value="">Todas las gerencias</option>
                    @foreach($gerencias as $g)
                        <option value="{{ $g->id }}" @selected(request('gerencia_id') == $g->id)>{{ $g->nombre }}</option>
                    @endforeach
                </select>
                <select name="estado" class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2">
                    <option value="">Todos los estados</option>
                    @foreach(\App\Models\Expediente::getEstados() as $key => $label)
                        <option value="{{ $key }}" @selected(request('estado') == $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filtrar
                    </button>
                    <a href="{{ route('expedientes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Limpiar
                    </a>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-yellow-700">Pendientes</div>
                            <div class="text-2xl font-bold text-yellow-900">{{ $stats['pendientes'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-blue-700">En proceso</div>
                            <div class="text-2xl font-bold text-blue-900">{{ $stats['en_proceso'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-green-700">Resueltos</div>
                            <div class="text-2xl font-bold text-green-900">{{ $stats['resueltos'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asunto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Solicitante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gerencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($expedientes as $exp)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $exp->numero ?? $exp->numero_expediente ?? $exp->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ Str::limit($exp->asunto, 80) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $exp->solicitante_nombre ?? $exp->ciudadano_nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $exp->gerencia->nombre ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $estadoConfig = [
                                            'pendiente' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'en_proceso' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                                            'derivado' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                                            'resuelto' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'rechazado' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'archivado' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                                        ];
                                        $config = $estadoConfig[$exp->estado] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'];
                                    @endphp
                                    <span class="px-2 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full {{ $config['bg'] }} {{ $config['text'] }}">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
                                        </svg>
                                        {{ \App\Models\Expediente::getEstados()[$exp->estado] ?? $exp->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('expedientes.show', $exp->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-900">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No hay expedientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $expedientes->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
