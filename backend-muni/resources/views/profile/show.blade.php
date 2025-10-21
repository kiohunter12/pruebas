@extends('layouts.app')
@section('title','Mi Perfil')
@section('content')
<div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-medium">Mi Perfil</h3>
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf @method('PATCH')
        <div class="mb-3"><label>Nombre</label><input name="name" value="{{ Auth::user()->name }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label>Email</label><input name="email" value="{{ Auth::user()->email }}" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label>Contraseña actual (si cambia)</label><input type="password" name="current_password" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label>Nueva contraseña</label><input type="password" name="password" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label>Confirmar contraseña</label><input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2"></div>
        <div class="flex gap-2"><button class="px-4 py-2 bg-blue-600 text-white rounded">Actualizar</button></div>
    </form>
</div></div></div>
@endsection
