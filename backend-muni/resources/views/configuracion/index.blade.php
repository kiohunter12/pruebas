@extends('layouts.app')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="bg-white shadow">
    <div class="px-4 sm:px-6 lg:max-w-6xl lg:mx-auto lg:px-8">
        <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Configuración del Sistema
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Administra las configuraciones generales de la plataforma de trámites documentarios
                </p>
            </div>
            <div class="mt-6 flex space-x-3 md:ml-4 md:mt-0">
                <button type="button" class="inline-flex items-center rounded-md bg-municipal-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-municipal-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-municipal-600">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Actualizar Configuración
                </button>
            </div>
        </div>
    </div>
</div>

<div class="py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Panel izquierdo - Navegación -->
            <div class="lg:col-span-1">
                <nav class="space-y-1">
                    <a href="#general" class="bg-municipal-50 text-municipal-700 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <svg class="text-municipal-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Configuración General
                    </a>
                    <a href="#email" class="text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Configuración de Email
                    </a>
                    <a href="#workflow" class="text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Flujos de Trabajo
                    </a>
                    <a href="#notifications" class="text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h10a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Notificaciones
                    </a>
                    <a href="#security" class="text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Seguridad
                    </a>
                    <a href="#backup" class="text-gray-900 hover:bg-gray-50 group flex items-center px-3 py-2 text-sm font-medium rounded-md">
                        <svg class="text-gray-400 group-hover:text-gray-500 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                        </svg>
                        Respaldos
                    </a>
                </nav>
            </div>

            <!-- Panel derecho - Contenido -->
            <div class="lg:col-span-2">
                <!-- Configuración General -->
                <div id="general" class="bg-white shadow rounded-lg mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Configuración General</h3>
                        <form class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="app_name" class="block text-sm font-medium text-gray-700">Nombre de la Aplicación</label>
                                    <input type="text" name="app_name" id="app_name" value="Trámite Documentario Municipal"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="app_url" class="block text-sm font-medium text-gray-700">URL de la Aplicación</label>
                                    <input type="url" name="app_url" id="app_url" value="http://localhost:8000"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                </div>
                            </div>
                            
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea name="description" id="description" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm"
                                          placeholder="Sistema de gestión de trámites documentarios para la municipalidad"></textarea>
                            </div>

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="timezone" class="block text-sm font-medium text-gray-700">Zona Horaria</label>
                                    <select name="timezone" id="timezone"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                        <option value="America/Lima" selected>America/Lima (UTC-5)</option>
                                        <option value="America/New_York">America/New_York (UTC-4)</option>
                                        <option value="UTC">UTC (UTC+0)</option>
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

                            <div class="flex items-center">
                                <input id="maintenance_mode" name="maintenance_mode" type="checkbox"
                                       class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">
                                    Modo de mantenimiento
                                </label>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Configuración de Email -->
                <div id="email" class="bg-white shadow rounded-lg mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Configuración de Email</h3>
                        <form class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="mail_driver" class="block text-sm font-medium text-gray-700">Driver de Email</label>
                                    <select name="mail_driver" id="mail_driver"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                        <option value="smtp" selected>SMTP</option>
                                        <option value="mailgun">Mailgun</option>
                                        <option value="ses">Amazon SES</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="mail_host" class="block text-sm font-medium text-gray-700">Servidor SMTP</label>
                                    <input type="text" name="mail_host" id="mail_host" placeholder="smtp.gmail.com"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                <div>
                                    <label for="mail_port" class="block text-sm font-medium text-gray-700">Puerto</label>
                                    <input type="number" name="mail_port" id="mail_port" value="587"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="mail_username" class="block text-sm font-medium text-gray-700">Usuario</label>
                                    <input type="text" name="mail_username" id="mail_username"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="mail_encryption" class="block text-sm font-medium text-gray-700">Encriptación</label>
                                    <select name="mail_encryption" id="mail_encryption"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                        <option value="tls" selected>TLS</option>
                                        <option value="ssl">SSL</option>
                                        <option value="">Ninguna</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="mail_from_address" class="block text-sm font-medium text-gray-700">Email Remitente</label>
                                <input type="email" name="mail_from_address" id="mail_from_address" placeholder="noreply@municipalidad.gob.pe"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                            </div>

                            <div class="flex justify-end">
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-municipal-600 hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                                    Probar Configuración
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Flujos de Trabajo -->
                <div id="workflow" class="bg-white shadow rounded-lg mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Configuración de Flujos de Trabajo</h3>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="auto_assign" class="block text-sm font-medium text-gray-700">Asignación Automática</label>
                                    <select name="auto_assign" id="auto_assign"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                        <option value="1" selected>Habilitada</option>
                                        <option value="0">Deshabilitada</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="default_sla" class="block text-sm font-medium text-gray-700">SLA por Defecto (días)</label>
                                    <input type="number" name="default_sla" id="default_sla" value="15"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm">
                                </div>
                            </div>

                            <div class="flex items-center space-x-6">
                                <div class="flex items-center">
                                    <input id="require_approval" name="require_approval" type="checkbox" checked
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                    <label for="require_approval" class="ml-2 block text-sm text-gray-900">
                                        Requerir aprobación para finalizar expedientes
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="auto_notifications" name="auto_notifications" type="checkbox" checked
                                           class="h-4 w-4 text-municipal-600 focus:ring-municipal-500 border-gray-300 rounded">
                                    <label for="auto_notifications" class="ml-2 block text-sm text-gray-900">
                                        Notificaciones automáticas
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón de guardar -->
                <div class="flex justify-end">
                    <button type="button" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-municipal-600 hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar Configuración
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection