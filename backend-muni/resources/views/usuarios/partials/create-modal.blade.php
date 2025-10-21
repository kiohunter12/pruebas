<!-- Modal para Crear/Editar Usuario -->
<div id="userModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeUserModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="userForm" onsubmit="submitUserForm(event)">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-municipal-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-municipal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="userModalTitle">
                                Crear Usuario
                            </h3>
                            <div class="mt-4 space-y-4">
                                <!-- Información Personal -->
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="user-name" class="block text-sm font-medium text-gray-700">Nombre *</label>
                                        <input type="text" id="user-name" name="name" required 
                                               class="mt-1 focus:ring-municipal-500 focus:border-municipal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label for="user-lastname" class="block text-sm font-medium text-gray-700">Apellidos *</label>
                                        <input type="text" id="user-lastname" name="lastname" required 
                                               class="mt-1 focus:ring-municipal-500 focus:border-municipal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>

                                <div>
                                    <label for="user-email" class="block text-sm font-medium text-gray-700">Email *</label>
                                    <input type="email" id="user-email" name="email" required 
                                           class="mt-1 focus:ring-municipal-500 focus:border-municipal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <div>
                                    <label for="user-dni" class="block text-sm font-medium text-gray-700">DNI *</label>
                                    <input type="text" id="user-dni" name="dni" required maxlength="8" pattern="[0-9]{8}"
                                           class="mt-1 focus:ring-municipal-500 focus:border-municipal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <div>
                                    <label for="user-phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                    <input type="tel" id="user-phone" name="phone" 
                                           class="mt-1 focus:ring-municipal-500 focus:border-municipal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <!-- Información Laboral -->
                                <div class="border-t pt-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Información Laboral</h4>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="user-gerencia" class="block text-sm font-medium text-gray-700">Gerencia *</label>
                                            <select id="user-gerencia" name="gerencia_id" required 
                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm rounded-md">
                                                <option value="">Seleccionar gerencia</option>
                                                <option value="1">Gerencia General</option>
                                                <option value="2">Gerencia de Desarrollo Urbano</option>
                                                <option value="3">Gerencia de Servicios Públicos</option>
                                                <option value="4">Gerencia de Desarrollo Social</option>
                                                <option value="5">Gerencia de Administración</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="user-role" class="block text-sm font-medium text-gray-700">Rol *</label>
                                            <select id="user-role" name="role_id" required 
                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm rounded-md">
                                                <option value="">Seleccionar rol</option>
                                                <option value="1">Super Administrador</option>
                                                <option value="2">Administrador</option>
                                                <option value="3">Jefe de Área</option>
                                                <option value="4">Mesa de Partes</option>
                                                <option value="5">Empleado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contraseña (solo para nuevos usuarios) -->
                                <div id="passwordSection" class="border-t pt-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-3">Credenciales</h4>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="user-password" class="block text-sm font-medium text-gray-700">Contraseña *</label>
                                            <input type="password" id="user-password" name="password" required 
                                                   class="mt-1 focus:ring-municipal-500 focus:border-municipal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label for="user-password-confirm" class="block text-sm font-medium text-gray-700">Confirmar Contraseña *</label>
                                            <input type="password" id="user-password-confirm" name="password_confirmation" required 
                                                   class="mt-1 focus:ring-municipal-500 focus:border-municipal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="border-t pt-4">
                                    <div class="flex items-center">
                                        <input id="user-active" name="active" type="checkbox" checked 
                                               class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded">
                                        <label for="user-active" class="ml-2 block text-sm text-gray-900">
                                            Usuario activo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-municipal-600 text-base font-medium text-white hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span id="userSubmitText">Crear Usuario</span>
                        <svg id="userSubmitSpinner" class="hidden animate-spin -mr-1 ml-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="closeUserModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>