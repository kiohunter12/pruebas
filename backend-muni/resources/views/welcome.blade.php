@extends('layouts.auth')

@section('title', 'Bienvenido')
@section('subtitle', 'Sistema de Trámite Documentario')

@section('content')
<div class="bg-white py-8 px-6 shadow-xl rounded-xl text-center">
    <div class="mb-6">
        <i class="fas fa-building text-6xl text-blue-600 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">
            Bienvenido al Sistema Municipal
        </h3>
        <p class="text-gray-600 mb-6">
            Gestiona tus trámites documentarios de manera eficiente y segura
        </p>
    </div>

    <div class="space-y-4">
        <a href="{{ route('login') }}" 
           class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Iniciar Sesión
        </a>
        
        <div class="text-sm text-gray-500">
            ¿Necesitas ayuda? Contacta al administrador del sistema
        </div>
    </div>
</div>

<div class="mt-6 bg-white/50 backdrop-blur-sm rounded-lg p-4">
    <h4 class="text-sm font-medium text-gray-700 mb-3 text-center">
        <i class="fas fa-star mr-2 text-yellow-500"></i>
        Características del Sistema
    </h4>
    <div class="grid grid-cols-1 gap-3 text-xs text-gray-600">
        <div class="flex items-center">
            <i class="fas fa-shield-alt mr-2 text-green-500"></i>
            Autenticación segura con tokens
        </div>
        <div class="flex items-center">
            <i class="fas fa-file-alt mr-2 text-blue-500"></i>
            Gestión completa de expedientes
        </div>
        <div class="flex items-center">
            <i class="fas fa-users mr-2 text-purple-500"></i>
            Control de usuarios y permisos
        </div>
        <div class="flex items-center">
            <i class="fas fa-chart-line mr-2 text-orange-500"></i>
            Reportes y estadísticas en tiempo real
        </div>
    </div>
</div>
@endsection