@extends('layouts.app')

@section('title', 'Configuraciones Personales')

@section('content')
<div class="bg-white shadow">
    <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8">
        <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Configuraciones Personales
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Personaliza tu experiencia en el sistema de trámites documentarios
                </p>
            </div>
        </div>
    </div>
</div>

<div class="py-10">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="space-y-8">
            <!-- Preferencias de Notificaciones -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Preferencias de Notificaciones</h3>
                    <form class="space-y-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="email_notifications" name="email_notifications" type="checkbox" checked
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="email_notifications" class="font-medium text-gray-700">
                                        Notificaciones por email
                                    </label>
                                    <p class="text-gray-500">Recibir actualizaciones sobre mis expedientes por correo electrónico</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="browser_notifications" name="browser_notifications" type="checkbox" checked
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="browser_notifications" class="font-medium text-gray-700">
                                        Notificaciones del navegador
                                    </label>
                                    <p class="text-gray-500">Mostrar notificaciones push en el navegador</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="sms_notifications" name="sms_notifications" type="checkbox"
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="sms_notifications" class="font-medium text-gray-700">
                                        Notificaciones SMS
                                    </label>
                                    <p class="text-gray-500">Recibir mensajes de texto para actualizaciones importantes</p>
                                </div>
                            </div>
                        </div>

                        <fieldset>
                            <legend class="text-sm font-medium text-gray-700">Frecuencia de notificaciones</legend>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center">
                                    <input id="immediate" name="notification_frequency" type="radio" value="immediate" checked
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300">
                                    <label for="immediate" class="ml-3 block text-sm font-medium text-gray-700">
                                        Inmediata
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="daily" name="notification_frequency" type="radio" value="daily"
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300">
                                    <label for="daily" class="ml-3 block text-sm font-medium text-gray-700">
                                        Resumen diario
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="weekly" name="notification_frequency" type="radio" value="weekly"
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300">
                                    <label for="weekly" class="ml-3 block text-sm font-medium text-gray-700">
                                        Resumen semanal
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>

            <!-- Preferencias de Interfaz -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Preferencias de Interfaz</h3>
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="theme" class="block text-sm font-medium text-gray-700">Tema</label>
                                <select name="theme" id="theme"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                    <option value="light" selected>Claro</option>
                                    <option value="dark">Oscuro</option>
                                    <option value="auto">Automático</option>
                                </select>
                            </div>

                            <div>
                                <label for="language" class="block text-sm font-medium text-gray-700">Idioma</label>
                                <select name="language" id="language"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                    <option value="es" selected>Español</option>
                                    <option value="en">Inglés</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="items_per_page" class="block text-sm font-medium text-gray-700">Elementos por página</label>
                                <select name="items_per_page" id="items_per_page"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>

                            <div>
                                <label for="default_view" class="block text-sm font-medium text-gray-700">Vista por defecto</label>
                                <select name="default_view" id="default_view"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                    <option value="dashboard" selected>Dashboard</option>
                                    <option value="expedientes">Mis Expedientes</option>
                                    <option value="mesa-partes">Mesa de Partes</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="compact_view" name="compact_view" type="checkbox"
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="compact_view" class="font-medium text-gray-700">
                                        Vista compacta
                                    </label>
                                    <p class="text-gray-500">Mostrar más información en menos espacio</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="show_tooltips" name="show_tooltips" type="checkbox" checked
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="show_tooltips" class="font-medium text-gray-700">
                                        Mostrar ayuda contextual
                                    </label>
                                    <p class="text-gray-500">Mostrar consejos y ayuda al pasar el cursor sobre elementos</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Configuración de Seguridad -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Configuración de Seguridad</h3>
                    <div class="space-y-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="two_factor" name="two_factor" type="checkbox"
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="two_factor" class="font-medium text-gray-700">
                                        Autenticación de dos factores
                                    </label>
                                    <p class="text-gray-500">Agregar una capa adicional de seguridad a tu cuenta</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="login_notifications" name="login_notifications" type="checkbox" checked
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="login_notifications" class="font-medium text-gray-700">
                                        Notificar inicios de sesión
                                    </label>
                                    <p class="text-gray-500">Recibir notificación cuando alguien acceda a tu cuenta</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="session_timeout" class="block text-sm font-medium text-gray-700">Tiempo de sesión (minutos)</label>
                            <select name="session_timeout" id="session_timeout"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm max-w-xs">
                                <option value="30">30 minutos</option>
                                <option value="60" selected>1 hora</option>
                                <option value="120">2 horas</option>
                                <option value="240">4 horas</option>
                                <option value="480">8 horas</option>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Cambiar Contraseña
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuración de Datos y Privacidad -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Datos y Privacidad</h3>
                    <div class="space-y-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="analytics" name="analytics" type="checkbox" checked
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="analytics" class="font-medium text-gray-700">
                                        Permitir análisis de uso
                                    </label>
                                    <p class="text-gray-500">Ayudar a mejorar el sistema compartiendo datos de uso anónimos</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="marketing" name="marketing" type="checkbox"
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="marketing" class="font-medium text-gray-700">
                                        Recibir comunicaciones promocionales
                                    </label>
                                    <p class="text-gray-500">Recibir información sobre nuevas funciones y actualizaciones</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex space-x-3">
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                    </svg>
                                    Descargar mis datos
                                </button>
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar cuenta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-3">
                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                    Cancelar
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-municipal-600 hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Configuración
                </button>
            </div>
        </div>
    </div>
</div>
@endsection