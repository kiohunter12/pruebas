<!-- Modal para Crear/Editar Permiso -->
<div id="permissionModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closePermissionModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="permissionForm" onsubmit="submitPermissionForm(event)">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="permissionModalTitle">
                                Crear Permiso
                            </h3>
                            <div class="mt-4 space-y-4">
                                <!-- Información Básica -->
                                <div>
                                    <label for="permission-name" class="block text-sm font-medium text-gray-700">Nombre del Permiso *</label>
                                    <input type="text" id="permission-name" name="name" required 
                                           class="mt-1 focus:ring-municipal-500 focus:border-municipal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                           placeholder="Ej: users.create">
                                    <p class="mt-1 text-sm text-gray-500">Usa el formato: módulo.acción (ej: users.create, expedientes.approve)</p>
                                </div>

                                <div>
                                    <label for="permission-description" class="block text-sm font-medium text-gray-700">Descripción *</label>
                                    <textarea id="permission-description" name="description" rows="3" required 
                                              class="mt-1 focus:ring-municipal-500 focus:border-municipal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                              placeholder="Describe qué permite hacer este permiso..."></textarea>
                                </div>

                                <!-- Módulo -->
                                <div>
                                    <label for="permission-module" class="block text-sm font-medium text-gray-700">Módulo *</label>
                                    <select id="permission-module" name="module" required 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm rounded-md">
                                        <option value="">Seleccionar módulo</option>
                                        @if(isset($permisosPorModulo))
                                            @foreach($permisosPorModulo as $moduleName => $permisos)
                                                <option value="{{ $moduleName }}">{{ ucfirst(str_replace('_', ' ', $moduleName)) }}</option>
                                            @endforeach
                                        @endif
                                        <option value="users">Gestión de Usuarios</option>
                                        <option value="expedientes">Gestión de Expedientes</option>
                                        <option value="mesa">Mesa de Partes</option>
                                        <option value="gerencias">Gerencias</option>
                                        <option value="roles">Roles y Permisos</option>
                                        <option value="reports">Reportes</option>
                                        <option value="config">Configuración</option>
                                        <option value="system">Sistema</option>
                                    </select>
                                </div>

                                <!-- Acción -->
                                <div>
                                    <label for="permission-action" class="block text-sm font-medium text-gray-700">Acción *</label>
                                    <select id="permission-action" name="action" required 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm rounded-md">
                                        <option value="">Seleccionar acción</option>
                                        <option value="create">Crear (Create)</option>
                                        <option value="read">Ver/Leer (Read)</option>
                                        <option value="update">Actualizar (Update)</option>
                                        <option value="delete">Eliminar (Delete)</option>
                                        <option value="approve">Aprobar</option>
                                        <option value="reject">Rechazar</option>
                                        <option value="assign">Asignar</option>
                                        <option value="export">Exportar</option>
                                        <option value="import">Importar</option>
                                        <option value="manage">Gestionar</option>
                                    </select>
                                </div>

                                <!-- Nivel de Criticidad -->
                                <div>
                                    <label for="permission-level" class="block text-sm font-medium text-gray-700">Nivel de Criticidad *</label>
                                    <select id="permission-level" name="level" required 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm rounded-md">
                                        <option value="">Seleccionar nivel</option>
                                        <option value="low">Bajo - Acciones de lectura básicas</option>
                                        <option value="medium">Medio - Acciones de creación/edición</option>
                                        <option value="high">Alto - Acciones de eliminación/aprobación</option>
                                        <option value="critical">Crítico - Acciones del sistema</option>
                                    </select>
                                </div>

                                <!-- Configuraciones Adicionales -->
                                <div class="border-t pt-4 space-y-3">
                                    <div class="flex items-center">
                                        <input id="permission-active" name="active" type="checkbox" checked 
                                               class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded">
                                        <label for="permission-active" class="ml-2 block text-sm text-gray-900">
                                            Permiso activo
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="permission-system" name="is_system" type="checkbox" 
                                               class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded">
                                        <label for="permission-system" class="ml-2 block text-sm text-gray-900">
                                            Permiso del sistema
                                            <span class="text-gray-500 text-xs block">Los permisos del sistema no pueden ser eliminados</span>
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="permission-assignable" name="assignable" type="checkbox" checked 
                                               class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded">
                                        <label for="permission-assignable" class="ml-2 block text-sm text-gray-900">
                                            Asignable a roles
                                            <span class="text-gray-500 text-xs block">Permite que este permiso sea asignado a roles</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Vista Previa del Nombre -->
                                <div class="border-t pt-4">
                                    <label class="block text-sm font-medium text-gray-700">Vista Previa del Nombre</label>
                                    <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md">
                                        <code id="permission-preview" class="text-sm text-gray-900">
                                            Selecciona módulo y acción
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-municipal-600 text-base font-medium text-white hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span id="permissionSubmitText">Crear Permiso</span>
                        <svg id="permissionSubmitSpinner" class="hidden animate-spin -mr-1 ml-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="closePermissionModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>