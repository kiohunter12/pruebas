@extends('layouts.app')
@section('title','Editar Tramite')
@section('content')
<div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-medium mb-4">Editar Tipo de Trámite</h3>
    <form method="POST" action="{{ route('tramites.update', $tramite->id) }}">
        @csrf @method('PUT')
        <div class="mb-3"><label class="block text-sm">Nombre</label><input name="nombre" value="{{ $tramite->nombre }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="flex gap-2"><button class="px-4 py-2 bg-blue-600 text-white rounded">Actualizar</button><a href="{{ route('tramites.index') }}" class="px-4 py-2 bg-gray-100 rounded">Cancelar</a></div>
    </form>
</div></div></div>
@endsection
