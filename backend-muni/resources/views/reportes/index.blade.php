@extends('layouts.app')
@section('title','Reportes')
@section('content')
<div class="py-6"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-medium">Reportes</h3>
    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('reportes.expedientes') }}" class="p-4 bg-blue-600 text-white rounded text-center">Expedientes</a>
        <a href="{{ route('reportes.tramites') }}" class="p-4 bg-green-600 text-white rounded text-center">Trámites</a>
        <a href="{{ route('reportes.tiempos') }}" class="p-4 bg-gray-100 text-gray-700 rounded text-center">Tiempos</a>
    </div>
</div></div></div>
@endsection
