{{-- Ejemplo de uso de directivas de roles y permisos en Blade --}}

{{-- MOSTRAR SOLO PARA ROLES ESPECÍFICOS --}}
@role('superadministrador')
    <div class="admin-only-section">
        <h3>Panel de Super Administrador</h3>
        <p>Solo visible para super administradores</p>
    </div>
@endrole

@role('administrador|superadministrador')
    <div class="admin-section">
        <h3>Panel de Administración</h3>
        <p>Visible para administradores y super administradores</p>
    </div>
@endrole

{{-- MOSTRAR SOLO PARA PERMISOS ESPECÍFICOS --}}
@can('gestionar_usuarios')
    <a href="{{ route('usuarios.index') }}" class="btn btn-primary">
        Gestionar Usuarios
    </a>
@endcan

@can('crear_usuarios')
    <a href="{{ route('usuarios.create') }}" class="btn btn-success">
        Crear Usuario
    </a>
@endcan

@can('ver_todos_expedientes')
    <a href="{{ route('expedientes.all') }}" class="btn btn-info">
        Ver Todos los Expedientes
    </a>
@endcan

{{-- OCULTAR PARA ROLES ESPECÍFICOS --}}
@hasanyrole('gerente|subgerente')
    <div class="gerente-section">
        <h3>Panel de Gerente</h3>
        <p>Solo visible para gerentes y subgerentes</p>
    </div>
@endhasanyrole

{{-- MÚLTIPLES CONDICIONES --}}
@auth
    @role('jefe_gerencia')
        @can('gestionar_expedientes')
            <div class="jefe-expedientes">
                <h3>Gestión de Expedientes</h3>
                <a href="{{ route('expedientes.pending') }}">Expedientes Pendientes</a>
            </div>
        @endcan
    @endrole
@endauth

{{-- USANDO UNLESS (lo contrario) --}}
@unlessrole('ciudadano')
    <div class="internal-user-menu">
        <h3>Menú Interno</h3>
        <p>No visible para ciudadanos</p>
    </div>
@endunlessrole

{{-- COMBINANDO ROLES Y PERMISOS --}}
@if(auth()->user()->hasRole('superadministrador') || auth()->user()->can('gestionar_sistema'))
    <div class="system-admin">
        <h3>Administración del Sistema</h3>
        <a href="{{ route('admin.settings') }}">Configuraciones</a>
    </div>
@endif

{{-- EJEMPLO DE MENÚ DINÁMICO --}}
<nav class="main-navigation">
    <ul>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        
        @can('ver_expedientes')
            <li><a href="{{ route('expedientes.index') }}">Mis Expedientes</a></li>
        @endcan
        
        @can('registrar_expediente')
            <li><a href="{{ route('expedientes.create') }}">Crear Expediente</a></li>
        @endcan
        
        @role('administrador|superadministrador')
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Administración</a>
                <ul class="dropdown-menu">
                    @can('gestionar_usuarios')
                        <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
                    @endcan
                    
                    @can('gestionar_gerencias')
                        <li><a href="{{ route('gerencias.index') }}">Gerencias</a></li>
                    @endcan
                    
                    @can('gestionar_workflows')
                        <li><a href="{{ route('workflows.index') }}">Flujos de Trabajo</a></li>
                    @endcan
                </ul>
            </li>
        @endrole
        
        @hasanyrole('gerente|jefe_gerencia')
            <li><a href="{{ route('expedientes.assigned') }}">Asignados a Mí</a></li>
        @endhasanyrole
    </ul>
</nav>