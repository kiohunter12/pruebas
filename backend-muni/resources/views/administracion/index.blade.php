@extends('layouts.app')
@section('title','Administración')
@section('content')
<div class="py-6"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-medium">Administración</h3>
    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('usuarios.index') }}" class="p-4 bg-blue-600 text-white rounded text-center">Usuarios</a>
        <a href="{{ route('roles.index') }}" class="p-4 bg-gray-100 text-gray-700 rounded text-center">Roles</a>
    </div>
</div></div></div>
@endsection
