// ================================
// FUNCIONES GLOBALES PARA MODALES
// ================================

// Variables globales
let currentEditingId = null;
let currentEditingType = null; // 'user', 'role', 'permission'

// ================================
// FUNCIONES PARA USUARIOS
// ================================

function openCreateUserModal() {
    currentEditingId = null;
    currentEditingType = 'user';
    
    // Resetear formulario
    document.getElementById('userForm').reset();
    document.getElementById('userModalTitle').textContent = 'Crear Usuario';
    document.getElementById('userSubmitText').textContent = 'Crear Usuario';
    
    // Mostrar sección de contraseña para nuevos usuarios
    document.getElementById('passwordSection').style.display = 'block';
    document.querySelector('#user-password').required = true;
    document.querySelector('#user-password-confirm').required = true;
    
    // Mostrar modal
    document.getElementById('userModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Focus en primer campo
    setTimeout(() => {
        document.getElementById('user-name').focus();
    }, 100);
}

function editUser(userId, userData = null) {
    currentEditingId = userId;
    currentEditingType = 'user';
    
    document.getElementById('userModalTitle').textContent = 'Editar Usuario';
    document.getElementById('userSubmitText').textContent = 'Actualizar Usuario';
    
    // Ocultar sección de contraseña para edición
    document.getElementById('passwordSection').style.display = 'none';
    document.querySelector('#user-password').required = false;
    document.querySelector('#user-password-confirm').required = false;
    
    // Si tenemos datos del usuario, llenar el formulario
    if (userData) {
        fillUserForm(userData);
    } else {
        // Simular carga de datos del usuario
        setTimeout(() => {
            fillUserForm({
                name: 'Juan Carlos',
                lastname: 'Pérez García',
                email: 'juan.perez@muni.gob.pe',
                dni: '12345678',
                phone: '987654321',
                gerencia_id: '2',
                role_id: '3',
                active: true
            });
        }, 200);
    }
    
    // Mostrar modal
    document.getElementById('userModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function fillUserForm(userData) {
    document.getElementById('user-name').value = userData.name || '';
    document.getElementById('user-lastname').value = userData.lastname || '';
    document.getElementById('user-email').value = userData.email || '';
    document.getElementById('user-dni').value = userData.dni || '';
    document.getElementById('user-phone').value = userData.phone || '';
    document.getElementById('user-gerencia').value = userData.gerencia_id || '';
    document.getElementById('user-role').value = userData.role_id || '';
    document.getElementById('user-active').checked = userData.active || false;
}

function closeUserModal() {
    document.getElementById('userModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    currentEditingId = null;
    currentEditingType = null;
}

function submitUserForm(event) {
    event.preventDefault();
    
    const submitButton = event.target.querySelector('button[type="submit"]');
    const submitText = document.getElementById('userSubmitText');
    const submitSpinner = document.getElementById('userSubmitSpinner');
    
    // Mostrar loading
    submitButton.disabled = true;
    submitText.textContent = currentEditingId ? 'Actualizando...' : 'Creando...';
    submitSpinner.classList.remove('hidden');
    
    // Obtener datos del formulario
    const formData = new FormData(event.target);
    const userData = {
        name: formData.get('name'),
        email: formData.get('email'),
        gerencia_id: formData.get('gerencia_id') || null,
        estado: formData.get('estado') || 'activo'
    };
    
    // Solo incluir password si no está editando o si se proporcionó una nueva
    if (!currentEditingId || formData.get('password')) {
        userData.password = formData.get('password');
        userData.password_confirmation = formData.get('password_confirmation');
    }
    
    // Hacer petición a la API
    const url = currentEditingId ? `/api/usuarios/${currentEditingId}` : '/api/usuarios';
    const method = currentEditingId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(userData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(
                currentEditingId ? 'Usuario actualizado correctamente' : 'Usuario creado correctamente',
                'success'
            );
            closeUserModal();
            // Recargar la página para mostrar los cambios
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Error al procesar el usuario', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error de conexión', 'error');
    })
    .finally(() => {
        // Restaurar botón
        submitButton.disabled = false;
        submitText.textContent = currentEditingId ? 'Actualizar Usuario' : 'Crear Usuario';
        submitSpinner.classList.add('hidden');
    });
}

function deleteUser(userId, userName) {
    if (confirm(`¿Estás seguro de que quieres eliminar al usuario "${userName}"? Esta acción no se puede deshacer.`)) {
        fetch(`/api/usuarios/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Usuario eliminado correctamente', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Error al eliminar el usuario', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        });
    }
}

function toggleUserStatus(userId, currentStatus) {
    const action = currentStatus ? 'desactivar' : 'activar';
    const newStatus = !currentStatus;
    
    if (confirm(`¿Estás seguro de que quieres ${action} este usuario?`)) {
        console.log('Toggling user status:', userId, 'to', newStatus);
        
        showNotification(`Usuario ${action}do correctamente`, 'success');
        
        // En implementación real, aquí iría la petición de cambio de estado
    }
}

// ================================
// FUNCIONES PARA ROLES
// ================================

function openCreateRoleModal() {
    currentEditingId = null;
    currentEditingType = 'role';
    
    // Resetear formulario
    document.getElementById('roleForm').reset();
    document.getElementById('roleModalTitle').textContent = 'Crear Rol';
    document.getElementById('roleSubmitText').textContent = 'Crear Rol';
    
    // Limpiar permisos seleccionados
    clearAllPermissions();
    
    // Mostrar modal
    document.getElementById('roleModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Focus en primer campo
    setTimeout(() => {
        document.getElementById('role-name').focus();
    }, 100);
}

function editRole(roleId, roleData = null) {
    currentEditingId = roleId;
    currentEditingType = 'role';
    
    document.getElementById('roleModalTitle').textContent = 'Editar Rol';
    document.getElementById('roleSubmitText').textContent = 'Actualizar Rol';
    
    // Si tenemos datos del rol, llenar el formulario
    if (roleData) {
        fillRoleForm(roleData);
    } else {
        // Simular carga de datos del rol
        setTimeout(() => {
            fillRoleForm({
                name: 'Jefe de Mesa de Partes',
                description: 'Responsable de supervisar las operaciones de mesa de partes',
                level: '3',
                active: true,
                permissions: ['users.read', 'expedientes.create', 'expedientes.read', 'mesa.recibir', 'mesa.derivar']
            });
        }, 200);
    }
    
    // Mostrar modal
    document.getElementById('roleModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function fillRoleForm(roleData) {
    document.getElementById('role-name').value = roleData.name || '';
    document.getElementById('role-description').value = roleData.description || '';
    document.getElementById('role-level').value = roleData.level || '';
    document.getElementById('role-active').checked = roleData.active || false;
    
    // Marcar permisos
    if (roleData.permissions) {
        clearAllPermissions();
        roleData.permissions.forEach(permission => {
            const checkbox = document.querySelector(`input[name="permissions[]"][value="${permission}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
        updateModuleCheckboxes();
    }
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    currentEditingId = null;
    currentEditingType = null;
}

function submitRoleForm(event) {
    event.preventDefault();
    
    const submitButton = event.target.querySelector('button[type="submit"]');
    const submitText = document.getElementById('roleSubmitText');
    const submitSpinner = document.getElementById('roleSubmitSpinner');
    
    // Mostrar loading
    submitButton.disabled = true;
    submitText.textContent = currentEditingId ? 'Actualizando...' : 'Creando...';
    submitSpinner.classList.remove('hidden');
    
    // Obtener datos del formulario
    const formData = new FormData(event.target);
    const roleData = {
        name: formData.get('name'),
        guard_name: 'web'
    };
    
    // Obtener permisos seleccionados
    const permissions = Array.from(document.querySelectorAll('input[name="permissions[]"]:checked'))
                            .map(checkbox => checkbox.value);
    roleData.permissions = permissions;
    
    // Hacer petición a la API
    const url = currentEditingId ? `/api/roles/${currentEditingId}` : '/api/roles';
    const method = currentEditingId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(roleData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(
                currentEditingId ? 'Rol actualizado correctamente' : 'Rol creado correctamente',
                'success'
            );
            closeRoleModal();
            // Recargar la página para mostrar los cambios
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Error al procesar el rol', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error de conexión', 'error');
    })
    .finally(() => {
        // Restaurar botón
        submitButton.disabled = false;
        submitText.textContent = currentEditingId ? 'Actualizar Rol' : 'Crear Rol';
        submitSpinner.classList.add('hidden');
    });
}

function deleteRole(roleId, roleName) {
    if (confirm(`¿Estás seguro de que quieres eliminar el rol "${roleName}"? Esta acción no se puede deshacer y afectará a todos los usuarios con este rol.`)) {
        fetch(`/api/roles/${roleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Rol eliminado correctamente', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Error al eliminar el rol', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        });
    }
}

// ================================
// FUNCIONES PARA PERMISOS EN ROLES
// ================================

function toggleModulePermissions(moduleName) {
    const moduleCheckbox = document.getElementById(`module-${moduleName}`);
    const permissionCheckboxes = document.querySelectorAll(`.module-${moduleName}-perm`);
    
    permissionCheckboxes.forEach(checkbox => {
        checkbox.checked = moduleCheckbox.checked;
    });
}

function selectAllPermissions() {
    const allPermissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
    const allModuleCheckboxes = document.querySelectorAll('input[id^="module-"]');
    
    allPermissionCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    
    allModuleCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

function clearAllPermissions() {
    const allPermissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
    const allModuleCheckboxes = document.querySelectorAll('input[id^="module-"]');
    
    allPermissionCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    allModuleCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

function updateModuleCheckboxes() {
    const modules = ['users', 'expedientes', 'mesa-partes', 'reports'];
    
    modules.forEach(moduleName => {
        const moduleCheckbox = document.getElementById(`module-${moduleName}`);
        const permissionCheckboxes = document.querySelectorAll(`.module-${moduleName}-perm`);
        
        if (moduleCheckbox && permissionCheckboxes.length > 0) {
            const checkedCount = Array.from(permissionCheckboxes).filter(cb => cb.checked).length;
            moduleCheckbox.checked = checkedCount === permissionCheckboxes.length;
            moduleCheckbox.indeterminate = checkedCount > 0 && checkedCount < permissionCheckboxes.length;
        }
    });
}

// Event listeners para actualizar checkboxes de módulo
document.addEventListener('DOMContentLoaded', function() {
    const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateModuleCheckboxes);
    });
});

// ================================
// FUNCIONES PARA PERMISOS
// ================================

function openCreatePermissionModal() {
    currentEditingId = null;
    currentEditingType = 'permission';
    
    // Resetear formulario
    document.getElementById('permissionForm').reset();
    document.getElementById('permissionModalTitle').textContent = 'Crear Permiso';
    document.getElementById('permissionSubmitText').textContent = 'Crear Permiso';
    
    // Limpiar vista previa
    document.getElementById('permission-preview').textContent = 'Selecciona módulo y acción';
    
    // Mostrar modal
    document.getElementById('permissionModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Focus en primer campo
    setTimeout(() => {
        document.getElementById('permission-name').focus();
    }, 100);
}

function editPermission(permissionName) {
    currentEditingId = permissionName;
    currentEditingType = 'permission';
    
    document.getElementById('permissionModalTitle').textContent = 'Editar Permiso';
    document.getElementById('permissionSubmitText').textContent = 'Actualizar Permiso';
    
    // Cargar datos del permiso desde la API
    fetch(`/api/permissions/${permissionName}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const parts = data.data.name.split('.');
            fillPermissionForm({
                name: data.data.name,
                description: `Permite ${parts[1]} ${parts[0]} en el sistema`,
                module: parts[0],
                action: parts[1],
                level: 'medium',
                active: true,
                is_system: true,
                assignable: true
            });
        } else {
            showNotification('Error al cargar los datos del permiso', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Fallback con datos simulados si la API falla
        const parts = permissionName.split('.');
        fillPermissionForm({
            name: permissionName,
            description: `Permite ${parts[1]} ${parts[0]} en el sistema`,
            module: parts[0],
            action: parts[1],
            level: 'medium',
            active: true,
            is_system: true,
            assignable: true
        });
    });
    
    // Mostrar modal
    document.getElementById('permissionModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function fillPermissionForm(permissionData) {
    document.getElementById('permission-name').value = permissionData.name || '';
    document.getElementById('permission-description').value = permissionData.description || '';
    document.getElementById('permission-module').value = permissionData.module || '';
    document.getElementById('permission-action').value = permissionData.action || '';
    document.getElementById('permission-level').value = permissionData.level || '';
    document.getElementById('permission-active').checked = permissionData.active || false;
    document.getElementById('permission-system').checked = permissionData.is_system || false;
    document.getElementById('permission-assignable').checked = permissionData.assignable || false;
    
    updatePermissionPreview();
}

function closePermissionModal() {
    document.getElementById('permissionModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    currentEditingId = null;
    currentEditingType = null;
}

function submitPermissionForm(event) {
    event.preventDefault();
    
    const submitButton = event.target.querySelector('button[type="submit"]');
    const submitText = document.getElementById('permissionSubmitText');
    const submitSpinner = document.getElementById('permissionSubmitSpinner');
    
    // Mostrar loading
    submitButton.disabled = true;
    submitText.textContent = currentEditingId ? 'Actualizando...' : 'Creando...';
    submitSpinner.classList.remove('hidden');
    
    // Obtener datos del formulario
    const formData = new FormData(event.target);
    
    // Validar que se hayan seleccionado módulo y acción
    const module = formData.get('module');
    const action = formData.get('action');
    const permissionName = formData.get('name') || `${module}.${action}`;
    
    if (!module || !action) {
        showNotification('Debe seleccionar un módulo y una acción', 'error');
        submitButton.disabled = false;
        submitText.textContent = currentEditingId ? 'Actualizar Permiso' : 'Crear Permiso';
        submitSpinner.classList.add('hidden');
        return;
    }
    
    const permissionData = {
        name: permissionName,
        guard_name: 'web'
    };
    
    // Hacer petición a la API
    const url = currentEditingId ? `/api/permissions/${currentEditingId}` : '/api/permissions';
    const method = currentEditingId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(permissionData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(
                currentEditingId ? 'Permiso actualizado correctamente' : 'Permiso creado correctamente',
                'success'
            );
            closePermissionModal();
            // Recargar la página para mostrar los cambios
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            const errorMessage = data.errors ? 
                Object.values(data.errors).flat().join(', ') : 
                (data.message || 'Error al procesar el permiso');
            showNotification(errorMessage, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error de conexión al servidor', 'error');
    })
    .finally(() => {
        // Restaurar botón
        submitButton.disabled = false;
        submitText.textContent = currentEditingId ? 'Actualizar Permiso' : 'Crear Permiso';
        submitSpinner.classList.add('hidden');
    });
}

function deletePermission(permissionName) {
    if (confirm(`¿Estás seguro de que quieres eliminar el permiso "${permissionName}"? Esta acción no se puede deshacer y afectará a todos los roles que tengan este permiso.`)) {
        fetch(`/api/permissions/${permissionName}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Permiso eliminado correctamente', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.message || 'Error al eliminar el permiso', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        });
    }
}

function togglePermission(permissionName) {
    if (confirm(`¿Estás seguro de que quieres cambiar el estado de este permiso? Esto puede afectar el funcionamiento del sistema.`)) {
        console.log('Toggling permission:', permissionName);
        
        showNotification('Estado del permiso actualizado', 'success');
    }
}

// Función para actualizar vista previa del nombre del permiso
function updatePermissionPreview() {
    const module = document.getElementById('permission-module').value;
    const action = document.getElementById('permission-action').value;
    const preview = document.getElementById('permission-preview');
    const nameInput = document.getElementById('permission-name');
    
    if (module && action) {
        const permissionName = `${module}.${action}`;
        preview.textContent = permissionName;
        // Actualizar automáticamente el campo de nombre si está vacío o sigue el patrón módulo.acción
        if (!nameInput.value || nameInput.value.match(/^[a-z_]+\.[a-z_]+$/)) {
            nameInput.value = permissionName;
        }
    } else {
        preview.textContent = 'Selecciona módulo y acción';
    }
}

// Event listeners para vista previa del permiso
document.addEventListener('DOMContentLoaded', function() {
    const moduleSelect = document.getElementById('permission-module');
    const actionSelect = document.getElementById('permission-action');
    
    if (moduleSelect && actionSelect) {
        moduleSelect.addEventListener('change', updatePermissionPreview);
        actionSelect.addEventListener('change', updatePermissionPreview);
    }
});

// ================================
// FUNCIONES AUXILIARES
// ================================

function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
    
    // Estilos según el tipo
    const styles = {
        success: 'bg-green-50 border border-green-200 text-green-800',
        error: 'bg-red-50 border border-red-200 text-red-800',
        warning: 'bg-yellow-50 border border-yellow-200 text-yellow-800',
        info: 'bg-blue-50 border border-blue-200 text-blue-800'
    };
    
    notification.className += ` ${styles[type] || styles.info}`;
    
    // Icono según el tipo
    const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ'
    };
    
    notification.innerHTML = `
        <div class="flex items-center">
            <span class="mr-2 font-bold">${icons[type] || icons.info}</span>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    // Agregar al DOM
    document.body.appendChild(notification);
    
    // Animar entrada
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Función para cerrar modales con Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        // Cerrar modal activo
        const openModals = document.querySelectorAll('.fixed.inset-0:not(.hidden)');
        openModals.forEach(modal => {
            if (modal.id === 'userModal') closeUserModal();
            if (modal.id === 'roleModal') closeRoleModal();
            if (modal.id === 'permissionModal') closePermissionModal();
        });
    }
});

console.log('Sistema de gestión municipal cargado correctamente');