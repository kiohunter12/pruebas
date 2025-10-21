# 🏛️ Sistema de Trámite Documentario Municipal# 🏛️ Sistema de Trámite Documentario Municipal - Backend



Sistema integral de gestión documental para gobiernos locales, desarrollado con Laravel 11 y Tailwind CSS.## 📋 Descripción



---**Backend API REST** desarrollado en **Laravel 11** para la gestión integral de expedientes municipales con workflows personalizables, sistema de roles granular y arquitectura escalable. Diseñado para municipalidades que requieren digitalizar sus procesos administrativos según normativas peruanas.



## 📊 Estado del Proyecto: **85% COMPLETADO**---



### ✅ Módulos Completados (100%)## 🏗️ Arquitectura Técnica del Backend



#### 🔐 **1. Autenticación y Seguridad** ### **Stack Tecnológico Principal**

- ✅ Sistema de login/logout```php

- ✅ Gestión de sesionesFramework: Laravel 11.x LTS

- ✅ Middleware de autenticaciónPHP: 8.1+ (Recomendado 8.2+)

- ✅ Protección CSRFBase de Datos: MySQL 8.0+ / PostgreSQL 13+ / SQLite (desarrollo)

- ✅ Sanctum para API tokensCache: Redis 6.0+ / Memcached

Queue: Redis / Database / Amazon SQS

#### 👥 **2. Gestión de Usuarios**Storage: Local / Amazon S3 / Google Cloud Storage

- ✅ CRUD completo de usuariosWeb Server: Nginx / Apache

- ✅ Perfiles de usuarioContainer: Docker + Docker Compose

- ✅ Asignación de roles```

- ✅ Gestión de permisos (Spatie Permissions)

- ✅ Estados de usuario (activo/inactivo)### **Dependencias Principales**

```json

#### 🏢 **3. Gerencias y Subgerencias**{

- ✅ CRUD de gerencias    "laravel/sanctum": "Autenticación API con tokens",

- ✅ Estructura jerárquica (gerencias padre/hijas)    "spatie/laravel-permission": "Roles y permisos granulares",

- ✅ Asignación de responsables    "laravel/telescope": "Debug y monitoring (desarrollo)",

- ✅ 67 gerencias y subgerencias seeded    "barryvdh/laravel-dompdf": "Generación de PDFs",

- ✅ Asociación con tipos de trámite    "maatwebsite/excel": "Exportación Excel/CSV",

    "intervention/image": "Procesamiento de imágenes",

#### 🔑 **4. Roles y Permisos**    "pusher/pusher-php-server": "Notificaciones real-time",

- ✅ 7 roles predefinidos:    "predis/predis": "Cliente Redis para cache",

  - Superadministrador (todos los permisos)    "sentry/sentry-laravel": "Error tracking producción"

  - Administrador}

  - Jefe de Gerencia```

  - Funcionario

  - Funcionario Junior### **Patrones de Diseño Implementados**

  - Supervisor- **Repository Pattern**: Abstracción de acceso a datos

  - Ciudadano- **Service Layer**: Lógica de negocio separada de controladores

- ✅ 65 permisos granulares- **Observer Pattern**: Para eventos y notificaciones automáticas

- ✅ Middleware de autorización- **Factory Pattern**: Para creación de modelos en tests

- ✅ Políticas de acceso por rol- **Middleware Pattern**: Para validación de permisos y rate limiting

- **Command Pattern**: Para operaciones complejas en artisan commands

#### 📋 **5. Tipos de Trámite**- **Strategy Pattern**: Para diferentes tipos de workflows

- ✅ CRUD completo

- ✅ Códigos únicos autogenerados### **Estructura de Directorios Backend**

- ✅ Asociación con gerencias```

- ✅ Documentos requeridosapp/

- ✅ Costos y tiempo estimado├── Http/

- ✅ Estados (activo/inactivo)│   ├── Controllers/          # Controladores API y Web

- ✅ Estadísticas por tipo│   │   ├── Api/             # Controladores específicos de API

│   │   ├── Auth/            # Autenticación y autorización

#### 📄 **6. Tipos de Documentos**│   │   └── Web/             # Controladores para vistas Blade

- ✅ Catálogo de documentos│   ├── Middleware/          # Middleware personalizado

- ✅ Relación many-to-many con tipos de trámite│   ├── Requests/            # Form Request Validation

- ✅ Marcado de documentos obligatorios/opcionales│   └── Resources/           # API Resources para transformación JSON

- ✅ Validación de formatos permitidos├── Models/                  # Modelos Eloquent

├── Services/               # Servicios de lógica de negocio

#### 📦 **7. Expedientes**├── Repositories/           # Repositorios para acceso a datos

- ✅ Registro de expedientes├── Events/                 # Eventos del sistema

- ✅ Numeración automática correlativa├── Listeners/              # Listeners para eventos

- ✅ Estados del expediente: pendiente, en_proceso, derivado, resuelto, rechazado, archivado├── Jobs/                   # Jobs para colas asíncronas

- ✅ Trazabilidad completa├── Mail/                   # Templates de email

- ✅ Asignación de responsables├── Notifications/          # Notificaciones del sistema

- ✅ Soft deletes└── Traits/                # Traits reutilizables

- ✅ Historial de cambios

- ✅ Filtrado por gerencia, estado, tipo de trámitedatabase/

- ✅ Vista con iconos y colores por estado├── migrations/             # Migraciones de base de datos

- ✅ Estadísticas en tiempo real├── seeders/               # Seeders para datos iniciales

└── factories/             # Factories para tests

#### 🔄 **8. Flujos de Trabajo (Workflows)**

- ✅ Creación de workflows personalizadostests/

- ✅ Definición de etapas/pasos├── Feature/               # Tests de integración

- ✅ Transiciones entre etapas├── Unit/                  # Tests unitarios

- ✅ Asignación de responsables por etapa└── TestCase.php          # Configuración base de tests

- ✅ Tipos de pasos: inicio, proceso, fin

- ✅ Asociación workflow ↔ tipo de trámite (1:1)routes/

- ✅ Visualización gráfica del flujo├── api.php               # Rutas API REST

- ✅ Indicadores de progreso├── web.php               # Rutas web Blade

- ✅ Clonación de workflows└── console.php           # Comandos Artisan



#### 📊 **9. Visualización de Procesos**config/

- ✅ Vista de trámites con sus flujos├── auth.php              # Configuración autenticación

- ✅ Diagrama horizontal de etapas├── permission.php        # Configuración Spatie Permission

- ✅ Colores por tipo de etapa (verde=inicio, azul=proceso, morado=fin)├── sanctum.php           # Configuración tokens API

- ✅ Información de responsables por etapa├── queue.php             # Configuración colas

- ✅ Iconos y badges visuales├── mail.php              # Configuración email

└── filesystems.php       # Configuración storage

#### 📎 **10. Gestión Documental**```

- ✅ Carga de archivos por expediente

- ✅ Almacenamiento en storage---

- ✅ Validación de tipos de archivo

- ✅ Tamaño máximo configurable## 📊 Base de Datos - Esquema Técnico

- ✅ Descarga de documentos

### **Tablas de Autenticación y Seguridad**

#### 📜 **11. Historial y Auditoría**```sql

- ✅ Registro de todas las acciones-- Usuarios del sistema

- ✅ Tabla historial_expedientesusers (

- ✅ Tabla action_logs para auditoría    id BIGINT PRIMARY KEY,

- ✅ Timestamps automáticos    name VARCHAR(255) NOT NULL,

- ✅ Usuario que realizó la acción    email VARCHAR(255) UNIQUE NOT NULL,

    email_verified_at TIMESTAMP NULL,

---    password VARCHAR(255) NOT NULL,

    gerencia_id BIGINT NULL,

### 🚧 Módulos en Desarrollo (50-90%)    telefono VARCHAR(20) NULL,

    cargo VARCHAR(100) NULL,

#### 📝 **12. Notificaciones** - 70%    activo BOOLEAN DEFAULT TRUE,

- ✅ Tabla de notificaciones    remember_token VARCHAR(100) NULL,

- ✅ Modelo creado    created_at TIMESTAMP,

- ⏳ Sistema de envío de notificaciones    updated_at TIMESTAMP,

- ⏳ Notificaciones en tiempo real (pusher/broadcasting)    

- ⏳ Notificaciones por email    FOREIGN KEY (gerencia_id) REFERENCES gerencias(id)

)

#### 💰 **13. Pagos** - 60%

- ✅ Tabla de pagos-- Tokens de acceso API (Sanctum)

- ✅ Modelo creadopersonal_access_tokens (

- ✅ Asociación con expedientes    id BIGINT PRIMARY KEY,

- ⏳ Pasarela de pagos    tokenable_type VARCHAR(255) NOT NULL,

- ⏳ Confirmación de pagos    tokenable_id BIGINT NOT NULL,

- ⏳ Comprobantes/recibos    name VARCHAR(255) NOT NULL,

    token VARCHAR(64) UNIQUE NOT NULL,

#### 🗣️ **14. Quejas y Reclamos** - 50%    abilities TEXT NULL,

- ✅ Tabla complaints    last_used_at TIMESTAMP NULL,

- ✅ Modelo Complaint    expires_at TIMESTAMP NULL,

- ⏳ Formulario de registro    created_at TIMESTAMP,

- ⏳ Gestión de respuestas    updated_at TIMESTAMP,

- ⏳ Escalamiento de quejas    

- ⏳ Seguimiento    INDEX tokenable,

    INDEX token

#### 📈 **15. Reportes y Estadísticas** - 40%)

- ✅ Estadísticas básicas en dashboard

- ✅ Contadores por estado-- Sesiones web

- ⏳ Reportes exportables (PDF/Excel)sessions (

- ⏳ Gráficos avanzados    id VARCHAR(255) PRIMARY KEY,

- ⏳ Reportes por período    user_id BIGINT NULL,

- ⏳ Métricas de desempeño    ip_address VARCHAR(45) NULL,

    user_agent TEXT NULL,

---    payload LONGTEXT NOT NULL,

    last_activity INT NOT NULL,

### ❌ Módulos Pendientes (0-30%)    

    INDEX user_id,

#### 🔔 **16. Panel de Notificaciones** - 20%    INDEX last_activity

- ⏳ Vista de notificaciones no leídas)

- ⏳ Marcar como leído```

- ⏳ Notificaciones push

### **Tablas de Roles y Permisos (Spatie)**

#### 🧾 **17. Procedimientos Administrativos** - 10%```sql

- ✅ Tabla procedures-- Roles del sistema

- ⏳ Gestión completaroles (

    id BIGINT PRIMARY KEY,

#### 🌐 **18. Portal Ciudadano** - 30%    name VARCHAR(255) NOT NULL,

- ⏳ Registro de ciudadanos    guard_name VARCHAR(255) NOT NULL,

- ⏳ Seguimiento de trámites    created_at TIMESTAMP,

- ⏳ Consulta de estado    updated_at TIMESTAMP,

- ⏳ Descarga de resoluciones    

    UNIQUE KEY name_guard (name, guard_name)

#### 📧 **19. Notificaciones Email** - 0%)

- ⏳ Configuración SMTP

- ⏳ Templates de emails-- Permisos granulares (59+ permisos)

- ⏳ Queue para envíos masivospermissions (

    id BIGINT PRIMARY KEY,

#### 🔍 **20. Búsqueda Avanzada** - 20%    name VARCHAR(255) NOT NULL,

- ✅ Filtros básicos    guard_name VARCHAR(255) NOT NULL,

- ⏳ Búsqueda full-text    created_at TIMESTAMP,

- ⏳ Filtros combinados avanzados    updated_at TIMESTAMP,

    

---    UNIQUE KEY name_guard (name, guard_name)

)

## 🗄️ Base de Datos

-- Relación muchos a muchos: usuarios-roles

### Tablas Principales (30)model_has_roles (

1. ✅ `users` - Usuarios del sistema    role_id BIGINT NOT NULL,

2. ✅ `roles` - Roles (Spatie)    model_type VARCHAR(255) NOT NULL,

3. ✅ `permissions` - Permisos (Spatie)    model_id BIGINT NOT NULL,

4. ✅ `model_has_roles` - Asignación usuario-rol    

5. ✅ `model_has_permissions` - Asignación usuario-permiso    PRIMARY KEY (role_id, model_type, model_id),

6. ✅ `gerencias` - Gerencias y subgerencias    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE

7. ✅ `tipo_tramites` - Tipos de trámite)

8. ✅ `tipo_documentos` - Tipos de documentos

9. ✅ `tipo_tramite_tipo_documento` - Relación many-to-many-- Relación muchos a muchos: usuarios-permisos directos

10. ✅ `expedientes` - Expedientes principalesmodel_has_permissions (

11. ✅ `documentos_expediente` - Documentos adjuntos    permission_id BIGINT NOT NULL,

12. ✅ `historial_expedientes` - Historial de cambios    model_type VARCHAR(255) NOT NULL,

13. ✅ `workflows` - Definición de flujos    model_id BIGINT NOT NULL,

14. ✅ `workflow_steps` - Etapas de flujos    

15. ✅ `workflow_transitions` - Transiciones entre etapas    PRIMARY KEY (permission_id, model_type, model_id),

16. ✅ `workflow_rules` - Reglas de derivación    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE

17. ✅ `expediente_workflow_progress` - Progreso del expediente)

18. ✅ `expedient_files` - Archivos del expediente

19. ✅ `action_logs` - Registro de acciones-- Relación muchos a muchos: roles-permisos

20. ✅ `notifications` - Notificacionesrole_has_permissions (

21. ✅ `payments` - Pagos    permission_id BIGINT NOT NULL,

22. ✅ `complaints` - Quejas y reclamos    role_id BIGINT NOT NULL,

23. ✅ `procedures` - Procedimientos    

24. ✅ `sessions` - Sesiones    PRIMARY KEY (permission_id, role_id),

25. ✅ `personal_access_tokens` - Tokens API    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,

26. ✅ `cache` - Caché    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE

27. ✅ `cache_locks` - Bloqueos de caché)

28. ✅ `jobs` - Cola de trabajos```

29. ✅ `job_batches` - Lotes de trabajos

30. ✅ `failed_jobs` - Trabajos fallidos### **Tablas de Estructura Organizacional**

```sql

### Seeders Implementados-- Gerencias con jerarquía padre-hijo

- ✅ GerenciasSeeder (67 gerencias/subgerencias)gerencias (

- ✅ UsersSeeder (21 usuarios de prueba)    id BIGINT PRIMARY KEY,

- ✅ RolesAndPermissionsSeeder (7 roles, 65 permisos)    nombre VARCHAR(255) NOT NULL,

- ✅ TipoDocumentosSeeder (15 tipos de documentos)    descripcion TEXT NULL,

    codigo VARCHAR(50) UNIQUE NULL,

---    parent_id BIGINT NULL,

    nivel INT DEFAULT 1,

## 🎨 Interfaz de Usuario    activa BOOLEAN DEFAULT TRUE,

    responsable_id BIGINT NULL,

### Diseño    created_at TIMESTAMP,

- ✅ Tailwind CSS 3.x    updated_at TIMESTAMP,

- ✅ Alpine.js para interactividad    

- ✅ Diseño responsive    FOREIGN KEY (parent_id) REFERENCES gerencias(id),

- ✅ Componentes reutilizables    FOREIGN KEY (responsable_id) REFERENCES users(id),

- ✅ Iconos SVG personalizados    INDEX parent_nivel (parent_id, nivel),

- ✅ Sistema de colores consistente    INDEX activa

)

### Vistas Implementadas```

1. ✅ Login/Logout

2. ✅ Dashboard (pendiente mejorar)### **Tablas de Gestión de Expedientes**

3. ✅ Usuarios (index, create, edit, show)```sql

4. ✅ Gerencias (index, create, edit, show)-- Expedientes principales

5. ✅ Tipos de Trámite (index, create, edit, show)expedientes (

6. ✅ Expedientes (index, create, edit, show)    id BIGINT PRIMARY KEY,

7. ✅ Workflows (index, create, edit, show)    numero_expediente VARCHAR(50) UNIQUE NOT NULL,

8. ✅ Trámites con visualización de flujos (index)    solicitante_nombre VARCHAR(255) NOT NULL,

9. ⏳ Reportes    solicitante_email VARCHAR(255) NOT NULL,

10. ⏳ Estadísticas avanzadas    solicitante_telefono VARCHAR(20) NULL,

    solicitante_dni VARCHAR(8) NULL,

---    tipo_tramite_id BIGINT NOT NULL,

    gerencia_actual_id BIGINT NOT NULL,

## 🛠️ Tecnologías Utilizadas    funcionario_asignado_id BIGINT NULL,

    estado ENUM('ingresado', 'en_proceso', 'observado', 'aprobado', 'rechazado') DEFAULT 'ingresado',

### Backend    prioridad ENUM('baja', 'normal', 'alta', 'urgente') DEFAULT 'normal',

- **Framework**: Laravel 11.x    fecha_ingreso DATETIME NOT NULL,

- **PHP**: 8.2+    fecha_limite DATETIME NULL,

- **Base de Datos**: MySQL 8.0 / MariaDB 10.x    observaciones TEXT NULL,

- **Autenticación**: Laravel Sanctum    workflow_id BIGINT NULL,

- **Permisos**: Spatie Laravel-Permission    step_actual_id BIGINT NULL,

- **Storage**: Laravel Filesystem    deleted_at TIMESTAMP NULL,

    created_at TIMESTAMP,

### Frontend    updated_at TIMESTAMP,

- **CSS Framework**: Tailwind CSS 3.x    

- **JavaScript**: Alpine.js 3.x    FOREIGN KEY (tipo_tramite_id) REFERENCES tipo_tramites(id),

- **Build Tool**: Vite 5.x    FOREIGN KEY (gerencia_actual_id) REFERENCES gerencias(id),

- **Iconos**: SVG personalizados    FOREIGN KEY (funcionario_asignado_id) REFERENCES users(id),

    FOREIGN KEY (workflow_id) REFERENCES workflows(id),

### Herramientas de Desarrollo    INDEX numero_expediente,

- **Package Manager**: Composer (PHP), NPM (JS)    INDEX estado_fecha (estado, fecha_ingreso),

- **Testing**: PHPUnit    INDEX gerencia_funcionario (gerencia_actual_id, funcionario_asignado_id)

- **Code Style**: PSR-12)

- **Version Control**: Git

-- Documentos adjuntos por expediente

---documentos_expediente (

    id BIGINT PRIMARY KEY,

## 📦 Instalación    expediente_id BIGINT NOT NULL,

    nombre_original VARCHAR(255) NOT NULL,

### Requisitos    nombre_almacenado VARCHAR(255) NOT NULL,

- PHP >= 8.2    ruta_archivo VARCHAR(500) NOT NULL,

- Composer    tipo_mime VARCHAR(100) NOT NULL,

- Node.js >= 18.x    tamaño_bytes BIGINT NOT NULL,

- MySQL >= 8.0 o MariaDB >= 10.x    tipo_documento ENUM('requisito', 'adicional', 'respuesta', 'interno') DEFAULT 'requisito',

    es_obligatorio BOOLEAN DEFAULT FALSE,

### Pasos de Instalación    subido_por BIGINT NOT NULL,

    created_at TIMESTAMP,

1. **Clonar repositorio**    updated_at TIMESTAMP,

```bash    

git clone <repository-url>    FOREIGN KEY (expediente_id) REFERENCES expedientes(id) ON DELETE CASCADE,

cd backend-muni    FOREIGN KEY (subido_por) REFERENCES users(id),

```    INDEX expediente_tipo (expediente_id, tipo_documento)

)

2. **Instalar dependencias PHP**

```bash-- Historial completo de movimientos

composer installhistorial_expedientes (

```    id BIGINT PRIMARY KEY,

    expediente_id BIGINT NOT NULL,

3. **Instalar dependencias JavaScript**    user_id BIGINT NOT NULL,

```bash    accion VARCHAR(100) NOT NULL,

npm install    estado_anterior VARCHAR(50) NULL,

```    estado_nuevo VARCHAR(50) NULL,

    gerencia_origen_id BIGINT NULL,

4. **Configurar entorno**    gerencia_destino_id BIGINT NULL,

```bash    comentarios TEXT NULL,

cp .env.example .env    datos_adicionales JSON NULL,

php artisan key:generate    ip_address VARCHAR(45) NULL,

```    user_agent VARCHAR(500) NULL,

    created_at TIMESTAMP,

5. **Configurar base de datos en `.env`**    

```env    FOREIGN KEY (expediente_id) REFERENCES expedientes(id) ON DELETE CASCADE,

DB_CONNECTION=mysql    FOREIGN KEY (user_id) REFERENCES users(id),

DB_HOST=127.0.0.1    FOREIGN KEY (gerencia_origen_id) REFERENCES gerencias(id),

DB_PORT=3306    FOREIGN KEY (gerencia_destino_id) REFERENCES gerencias(id),

DB_DATABASE=tramite_muni    INDEX expediente_fecha (expediente_id, created_at),

DB_USERNAME=root    INDEX user_fecha (user_id, created_at)

DB_PASSWORD=)

``````



6. **Ejecutar migraciones y seeders**### **Tablas de Workflows Personalizables**

```bash```sql

php artisan migrate:fresh --seed-- Workflows personalizados por tipo de trámite

```workflows (

    id BIGINT PRIMARY KEY,

7. **Crear enlace simbólico para storage**    nombre VARCHAR(255) NOT NULL,

```bash    descripcion TEXT NULL,

php artisan storage:link    tipo_tramite_id BIGINT NULL,

```    version VARCHAR(10) DEFAULT '1.0',

    activo BOOLEAN DEFAULT TRUE,

8. **Compilar assets**    configuracion JSON NULL,

```bash    created_by BIGINT NOT NULL,

npm run dev    created_at TIMESTAMP,

# o para producción:    updated_at TIMESTAMP,

npm run build    

```    FOREIGN KEY (tipo_tramite_id) REFERENCES tipo_tramites(id),

    FOREIGN KEY (created_by) REFERENCES users(id),

9. **Iniciar servidor**    INDEX tipo_activo (tipo_tramite_id, activo)

```bash)

php artisan serve

```-- Pasos individuales de workflows

workflow_steps (

10. **Acceder al sistema**    id BIGINT PRIMARY KEY,

```    workflow_id BIGINT NOT NULL,

URL: http://127.0.0.1:8000    nombre VARCHAR(255) NOT NULL,

Usuario: superadmin@muni.gob.pe    descripcion TEXT NULL,

Password: password123    orden INT NOT NULL,

```    gerencia_responsable_id BIGINT NULL,

    tiempo_estimado_horas INT NULL,

---    es_automatico BOOLEAN DEFAULT FALSE,

    reglas_validacion JSON NULL,

## 👤 Usuarios de Prueba    configuracion JSON NULL,

    created_at TIMESTAMP,

### Administración    updated_at TIMESTAMP,

| Email | Password | Rol |    

|-------|----------|-----|    FOREIGN KEY (workflow_id) REFERENCES workflows(id) ON DELETE CASCADE,

| superadmin@muni.gob.pe | password123 | Superadministrador |    FOREIGN KEY (gerencia_responsable_id) REFERENCES gerencias(id),

| admin@muni.gob.pe | password123 | Administrador |    UNIQUE KEY workflow_orden (workflow_id, orden),

    INDEX workflow_gerencia (workflow_id, gerencia_responsable_id)

### Jefes de Gerencia)

| Email | Gerencia | Password |

|-------|----------|----------|-- Transiciones entre pasos de workflow

| roberto.sanchez@muni.gob.pe | GDUR | password123 |workflow_transitions (

| maria.gonzalez@muni.gob.pe | GDEL | password123 |    id BIGINT PRIMARY KEY,

| carmen.lopez@muni.gob.pe | GDS | password123 |    step_from_id BIGINT NOT NULL,

    step_to_id BIGINT NOT NULL,

### Funcionarios    condicion VARCHAR(255) NULL,

| Email | Subgerencia | Password |    es_automatica BOOLEAN DEFAULT FALSE,

|-------|-------------|----------|    requiere_documentos JSON NULL,

| carlos.mendoza@muni.gob.pe | SGPUR | password123 |    configuracion JSON NULL,

| laura.ruiz@muni.gob.pe | SGOPL | password123 |    created_at TIMESTAMP,

| juan.torres@muni.gob.pe | SGLCI | password123 |    updated_at TIMESTAMP,

    

---    FOREIGN KEY (step_from_id) REFERENCES workflow_steps(id) ON DELETE CASCADE,

    FOREIGN KEY (step_to_id) REFERENCES workflow_steps(id) ON DELETE CASCADE,

## 🔐 Permisos del Sistema    UNIQUE KEY from_to (step_from_id, step_to_id)

)

### Categorías de Permisos (65 total)

-- Progreso de expedientes en workflows

#### Expedientes (15)expediente_workflow_progress (

- ver_expedientes, registrar_expediente, editar_expediente    id BIGINT PRIMARY KEY,

- derivar_expediente, emitir_resolucion, rechazar_expediente    expediente_id BIGINT NOT NULL,

- finalizar_expediente, archivar_expediente, subir_documento    workflow_id BIGINT NOT NULL,

- ver_todos_expedientes, asignar_expediente, reasignar_expediente    step_actual_id BIGINT NOT NULL,

- consultar_historial, exportar_expedientes, eliminar_expediente    fecha_inicio DATETIME NOT NULL,

    fecha_fin DATETIME NULL,

#### Usuarios (6)    tiempo_transcurrido_horas DECIMAL(8,2) NULL,

- gestionar_usuarios, crear_usuarios, editar_usuarios    datos_progreso JSON NULL,

- eliminar_usuarios, ver_todos_usuarios, asignar_usuarios_gerencia    created_at TIMESTAMP,

    updated_at TIMESTAMP,

#### Roles y Permisos (2)    

- asignar_roles, gestionar_permisos    FOREIGN KEY (expediente_id) REFERENCES expedientes(id) ON DELETE CASCADE,

    FOREIGN KEY (workflow_id) REFERENCES workflows(id),

#### Gerencias (3)    FOREIGN KEY (step_actual_id) REFERENCES workflow_steps(id),

- gestionar_gerencias, crear_gerencias, editar_gerencias    INDEX expediente_workflow (expediente_id, workflow_id),

    INDEX step_fecha (step_actual_id, fecha_inicio)

#### Procedimientos (3))

- gestionar_procedimientos, crear_procedimientos, eliminar_procedimientos```



#### Tipos de Trámite (6)### **Tablas de Tipos y Configuración**

- gestionar_tipos_tramite, crear_tipos_tramite, editar_tipos_tramite```sql

- eliminar_tipos_tramite, activar_tipos_tramite, ver_tipos_tramite-- Tipos de trámites configurables

tipo_tramites (

#### Reportes (4)    id BIGINT PRIMARY KEY,

- ver_reportes, exportar_datos, ver_estadisticas_gerencia, ver_estadisticas_sistema    nombre VARCHAR(255) NOT NULL,

    descripcion TEXT NULL,

#### Configuración (3)    codigo VARCHAR(50) UNIQUE NOT NULL,

- configurar_sistema, gestionar_respaldos, ver_logs    categoria VARCHAR(100) NULL,

    costo_base DECIMAL(10,2) DEFAULT 0.00,

#### Notificaciones (2)    tiempo_estimado_dias INT NULL,

- enviar_notificaciones, gestionar_notificaciones    documentos_requeridos JSON NULL,

    requisitos TEXT NULL,

#### Pagos (3)    activo BOOLEAN DEFAULT TRUE,

- gestionar_pagos, confirmar_pagos, ver_pagos    workflow_default_id BIGINT NULL,

    created_at TIMESTAMP,

#### Quejas (3)    updated_at TIMESTAMP,

- gestionar_quejas, responder_quejas, escalar_quejas    

    FOREIGN KEY (workflow_default_id) REFERENCES workflows(id),

#### Workflows (11)    INDEX codigo_activo (codigo, activo),

- gestionar_workflows, crear_workflows, editar_workflows, eliminar_workflows    INDEX categoria

- ver_workflows, activar_workflows, clonar_workflows)

- crear_reglas_flujo, editar_reglas_flujo, eliminar_reglas_flujo, ver_reglas_flujo

-- Tipos de documentos aceptados

---tipo_documentos (

    id BIGINT PRIMARY KEY,

## 🚀 Próximos Pasos (Roadmap)    nombre VARCHAR(255) NOT NULL,

    descripcion TEXT NULL,

### Corto Plazo (1-2 semanas)    extensiones_permitidas JSON NULL,

- [ ] Completar módulo de notificaciones en tiempo real    tamaño_maximo_mb INT DEFAULT 10,

- [ ] Implementar generación de reportes PDF/Excel    es_imagen BOOLEAN DEFAULT FALSE,

- [ ] Portal ciudadano básico    activo BOOLEAN DEFAULT TRUE,

- [ ] Dashboard con gráficos mejorados    created_at TIMESTAMP,

    updated_at TIMESTAMP,

### Mediano Plazo (1 mes)    

- [ ] Sistema de pagos en línea    INDEX activo

- [ ] Notificaciones por email)

- [ ] Búsqueda avanzada full-text```

- [ ] Métricas y KPIs avanzados

- [ ] Firma digital de documentos### **Tablas de Auditoría y Logs**

```sql

### Largo Plazo (2-3 meses)-- Logs de acciones del sistema

- [ ] Integración con sistemas externos (RENIEC, SUNAT)action_logs (

- [ ] App móvil (React Native / Flutter)    id BIGINT PRIMARY KEY,

- [ ] OCR para documentos escaneados    user_id BIGINT NULL,

- [ ] Chatbot de atención    action VARCHAR(100) NOT NULL,

- [ ] Panel de Business Intelligence    model_type VARCHAR(255) NULL,

    model_id BIGINT NULL,

---    datos_anteriores JSON NULL,

    datos_nuevos JSON NULL,

## 📝 Notas Técnicas    ip_address VARCHAR(45) NULL,

    user_agent VARCHAR(500) NULL,

### Arquitectura    created_at TIMESTAMP,

- Patrón MVC (Model-View-Controller)    

- Repository Pattern para lógica de negocio compleja    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,

- Service Layer para operaciones transaccionales    INDEX user_fecha (user_id, created_at),

- Policy-based authorization    INDEX model_action (model_type, model_id, action),

- Queue para procesos pesados    INDEX fecha_action (created_at, action)

)

### Optimizaciones Implementadas

- ✅ Eager loading para relaciones-- Notificaciones del sistema

- ✅ Índices en columnas de búsqueda frecuentenotifications (

- ✅ Cache de permisos (Spatie)    id CHAR(36) PRIMARY KEY,

- ✅ Paginación en listados    type VARCHAR(255) NOT NULL,

- ✅ Soft deletes para auditoría    notifiable_type VARCHAR(255) NOT NULL,

    notifiable_id BIGINT NOT NULL,

### Seguridad    data JSON NOT NULL,

- ✅ Validación de entrada en todos los formularios    read_at TIMESTAMP NULL,

- ✅ Protección CSRF    created_at TIMESTAMP,

- ✅ Sanitización de datos    updated_at TIMESTAMP,

- ✅ Hashing de contraseñas (bcrypt)    

- ✅ Autorización basada en políticas    INDEX notifiable (notifiable_type, notifiable_id),

- ✅ Middleware de autenticación    INDEX read_at,

    INDEX created_at

---)

```

## 🐛 Problemas Conocidos

### **Índices de Rendimiento Críticos**

1. ~~Workflows no asociados correctamente a tipos de trámite~~ ✅ **RESUELTO**```sql

2. ~~Permisos de editar/eliminar tipos de trámite no asignados~~ ✅ **RESUELTO**-- Índices para consultas frecuentes

3. ~~Variable $gerencias en rojo en IDE~~ ✅ **RESUELTO** (falso positivo)CREATE INDEX idx_expedientes_busqueda ON expedientes (numero_expediente, estado, fecha_ingreso);

4. ⏳ Notificaciones en tiempo real pendientesCREATE INDEX idx_expedientes_gerencia_funcionario ON expedientes (gerencia_actual_id, funcionario_asignado_id, estado);

5. ⏳ Exportación de reportes pendienteCREATE INDEX idx_historial_expediente_fecha ON historial_expedientes (expediente_id, created_at DESC);

CREATE INDEX idx_documentos_expediente_tipo ON documentos_expediente (expediente_id, tipo_documento);

---CREATE INDEX idx_users_active_search ON users (activo, name, email);

CREATE INDEX idx_gerencias_hierarchy ON gerencias (parent_id, nivel, activa);

## 📞 Contacto y SoporteCREATE INDEX idx_workflow_progress_active ON expediente_workflow_progress (expediente_id, workflow_id, step_actual_id);



**Desarrollador**: Sistema Municipal-- Índices para reportes y estadísticas

**Email**: soporte@muni.gob.peCREATE INDEX idx_expedientes_stats ON expedientes (estado, gerencia_actual_id, created_at);

**Versión**: 1.0.0-betaCREATE INDEX idx_action_logs_reports ON action_logs (created_at, action, user_id);

**Última Actualización**: 06 de Octubre, 2025CREATE INDEX idx_notifications_unread ON notifications (notifiable_type, notifiable_id, read_at);

```

---

---

## 📄 Licencia

## 🎯 Requerimientos Funcionales del Backend

Este proyecto es propiedad de la Municipalidad y está protegido bajo licencia propietaria.

Todos los derechos reservados © 2025### **RF-001: Sistema de Autenticación y Autorización**

- **RF-001.1** El sistema debe autenticar usuarios mediante email y contraseña con hash seguro

---- **RF-001.2** El sistema debe generar tokens JWT/Sanctum para autenticación stateless

- **RF-001.3** El sistema debe validar tokens en cada request a endpoints protegidos

## 🎯 Estado General del Sistema- **RF-001.4** El sistema debe mantener sesiones web simultáneas con tokens API

- **RF-001.5** El sistema debe permitir logout que invalide tokens activos

```- **RF-001.6** El sistema debe implementar rate limiting para prevenir ataques de fuerza bruta

┌─────────────────────────────────────────────────────┐

│  COMPLETITUD DEL PROYECTO: 85%                      │### **RF-002: Gestión de Usuarios y Permisos**

├─────────────────────────────────────────────────────┤- **RF-002.1** El sistema debe permitir CRUD completo de usuarios con validaciones

│  ████████████████████████████████████████████░░░░░  │- **RF-002.2** El sistema debe manejar roles jerárquicos: Super Admin, Admin, Gerente, Subgerente, Mesa Partes, Ciudadano

├─────────────────────────────────────────────────────┤- **RF-002.3** El sistema debe asignar permisos granulares (59+ permisos) por módulo y acción

│  ✅ Core del Sistema: 95%                           │- **RF-002.4** El sistema debe validar permisos antes de ejecutar operaciones CRUD

│  ✅ Autenticación y Seguridad: 100%                 │- **RF-002.5** El sistema debe permitir asignación múltiple de roles y permisos por usuario

│  ✅ Gestión Documental: 90%                         │- **RF-002.6** El sistema debe mantener audit trail de cambios en roles y permisos

│  ✅ Workflows: 85%                                   │- **RF-002.7** El sistema debe permitir activación/desactivación de usuarios sin eliminar datos

│  ⏳ Notificaciones: 70%                             │

│  ⏳ Reportes: 40%                                    │### **RF-003: Gestión de Estructura Organizacional**

│  ⏳ Portal Ciudadano: 30%                           │- **RF-003.1** El sistema debe permitir CRUD de gerencias con jerarquía padre-hijo ilimitada

│  ❌ Pagos en Línea: 60%                             │- **RF-003.2** El sistema debe asignar usuarios a múltiples gerencias

└─────────────────────────────────────────────────────┘- **RF-003.3** El sistema debe calcular estadísticas por gerencia (usuarios activos, expedientes)

```- **RF-003.4** El sistema debe validar que gerencias padre no puedan ser hijas de sus descendientes

- **RF-003.5** El sistema debe permitir reorganización de jerarquías manteniendo integridad

**El sistema está listo para ambiente de pruebas y desarrollo.**- **RF-003.6** El sistema debe generar reportes de estructura organizacional

**Requiere completar módulos de notificaciones y reportes para producción.**

### **RF-004: Gestión de Expedientes**
- **RF-004.1** El sistema debe generar números únicos de expediente con formato configurable
- **RF-004.2** El sistema debe permitir CRUD completo de expedientes con validaciones de negocio
- **RF-004.3** El sistema debe manejar estados: Ingresado, En Proceso, Observado, Aprobado, Rechazado
- **RF-004.4** El sistema debe permitir derivación entre gerencias según flujo definido
- **RF-004.5** El sistema debe asignar expedientes a funcionarios específicos
- **RF-004.6** El sistema debe registrar historial completo de movimientos con timestamps
- **RF-004.7** El sistema debe implementar soft deletes para recuperación de datos
- **RF-004.8** El sistema debe validar reglas de negocio antes de cambios de estado

### **RF-005: Sistema de Workflows Personalizables**
- **RF-005.1** El sistema debe permitir CRUD de workflows personalizados por tipo de trámite
- **RF-005.2** El sistema debe definir pasos secuenciales con reglas específicas en JSON
- **RF-005.3** El sistema debe configurar transiciones automáticas entre estados
- **RF-005.4** El sistema debe validar condiciones antes de ejecutar transiciones
- **RF-005.5** El sistema debe mostrar progreso del expediente en el workflow actual
- **RF-005.6** El sistema debe permitir workflows paralelos y condicionales
- **RF-005.7** El sistema debe mantener versionado de workflows para auditoria

### **RF-006: Gestión Documental**
- **RF-006.1** El sistema debe permitir carga múltiple de archivos por expediente
- **RF-006.2** El sistema debe validar tipos: PDF, DOC, DOCX, JPG, PNG con tamaños máximos
- **RF-006.3** El sistema debe generar nombres únicos para evitar colisiones
- **RF-006.4** El sistema debe almacenar archivos en storage configurable (local/cloud)
- **RF-006.5** El sistema debe generar URLs temporales para descarga segura
- **RF-006.6** El sistema debe mantener versiones de documentos con histórico
- **RF-006.7** El sistema debe comprimir imágenes automáticamente para optimizar storage

### **RF-007: API REST Completa**
- **RF-007.1** El sistema debe exponer endpoints RESTful para todas las entidades
- **RF-007.2** El sistema debe implementar paginación en listados con parámetros configurables
- **RF-007.3** El sistema debe retornar códigos HTTP estándar (200, 201, 400, 401, 403, 404, 500)
- **RF-007.4** El sistema debe formatear respuestas JSON consistentes con metadata
- **RF-007.5** El sistema debe implementar filtros avanzados por múltiples campos
- **RF-007.6** El sistema debe permitir ordenamiento dinámico por cualquier campo
- **RF-007.7** El sistema debe implementar búsqueda full-text en campos relevantes

### **RF-008: Sistema de Notificaciones**
- **RF-008.1** El sistema debe generar notificaciones automáticas por cambios de estado
- **RF-008.2** El sistema debe enviar notificaciones por email con templates personalizables
- **RF-008.3** El sistema debe mantener notificaciones en base de datos para dashboard
- **RF-008.4** El sistema debe permitir configuración de tipos de notificaciones por rol
- **RF-008.5** El sistema debe implementar sistema de colas para notificaciones masivas
- **RF-008.6** El sistema debe permitir notificaciones push para aplicaciones móviles
- **RF-008.7** El sistema debe registrar histórico de notificaciones enviadas

### **RF-009: Auditoría y Trazabilidad**
- **RF-009.1** El sistema debe registrar todas las acciones CRUD con usuario, fecha y datos
- **RF-009.2** El sistema debe mantener log de cambios con valores anteriores y nuevos
- **RF-009.3** El sistema debe generar reportes de auditoría por período y usuario
- **RF-009.4** El sistema debe implementar logging de errores con stack traces
- **RF-009.5** El sistema debe registrar intentos de acceso no autorizado
- **RF-009.6** El sistema debe mantener backup automático de logs críticos
- **RF-009.7** El sistema debe permitir exportación de logs en formatos estándar

### **RF-010: Reportes y Estadísticas**
- **RF-010.1** El sistema debe generar estadísticas en tiempo real para dashboards
- **RF-010.2** El sistema debe crear reportes por gerencia, funcionario y período
- **RF-010.3** El sistema debe calcular métricas de rendimiento (tiempos promedio, eficiencia)
- **RF-010.4** El sistema debe exportar reportes en formatos Excel, PDF y CSV
- **RF-010.5** El sistema debe generar gráficos estadísticos con datos JSON
- **RF-010.6** El sistema debe implementar cache de reportes frecuentes
- **RF-010.7** El sistema debe permitir reportes programados automáticos

### **RF-011: Tipos de Trámites y Documentos**
- **RF-011.1** El sistema debe permitir CRUD de tipos de trámites con requisitos específicos
- **RF-011.2** El sistema debe definir documentos requeridos por tipo de trámite
- **RF-011.3** El sistema debe validar documentos obligatorios antes de envío
- **RF-011.4** El sistema debe configurar costos y tiempos estimados por trámite
- **RF-011.5** El sistema debe permitir categorización jerárquica de trámites
- **RF-011.6** El sistema debe mantener histórico de cambios en tipos de trámites

### **RF-012: Mesa de Partes Digital**
- **RF-012.1** El sistema debe recibir documentos digitales con metadatos estructurados
- **RF-012.2** El sistema debe generar códigos de seguimiento únicos automáticamente
- **RF-012.3** El sistema debe clasificar trámites por tipo con reglas configurables
- **RF-012.4** El sistema debe derivar automáticamente según tipo de trámite
- **RF-012.5** El sistema debe notificar recepción al solicitante automáticamente
- **RF-012.6** El sistema debe validar completitud de documentos antes de aceptar

---

## 🔧 Requerimientos No Funcionales del Backend

### **RNF-001: Rendimiento**
- **RNF-001.1** El sistema debe responder requests API en máximo 200ms (percentil 95)
- **RNF-001.2** El sistema debe soportar mínimo 100 usuarios concurrentes
- **RNF-001.3** El sistema debe procesar carga de archivos hasta 50MB en máximo 30 segundos
- **RNF-001.4** El sistema debe ejecutar consultas complejas en máximo 2 segundos
- **RNF-001.5** El sistema debe implementar cache Redis para consultas frecuentes
- **RNF-001.6** El sistema debe optimizar queries N+1 con eager loading
- **RNF-001.7** El sistema debe implementar compresión gzip en respuestas HTTP

### **RNF-002: Seguridad**
- **RNF-002.1** El sistema debe encriptar contraseñas con bcrypt (cost factor 12+)
- **RNF-002.2** El sistema debe implementar protección CSRF en formularios web
- **RNF-002.3** El sistema debe validar y sanitizar todas las entradas de usuario
- **RNF-002.4** El sistema debe implementar rate limiting (60 requests/minuto por IP)
- **RNF-002.5** El sistema debe usar HTTPS obligatorio en producción
- **RNF-002.6** El sistema debe implementar headers de seguridad (HSTS, CSP, X-Frame-Options)
- **RNF-002.7** El sistema debe mantener tokens con expiración configurable
- **RNF-002.8** El sistema debe registrar intentos de acceso sospechosos

### **RNF-003: Escalabilidad**
- **RNF-003.1** El sistema debe usar arquitectura stateless para horizontal scaling
- **RNF-003.2** El sistema debe implementar queue system para procesos pesados
- **RNF-003.3** El sistema debe soportar múltiples instancias con load balancer
- **RNF-003.4** El sistema debe implementar cache distribuido para sesiones
- **RNF-003.5** El sistema debe usar índices de base de datos optimizados
- **RNF-003.6** El sistema debe implementar paginación eficiente con cursor-based
- **RNF-003.7** El sistema debe soportar sharding de base de datos si es necesario

### **RNF-004: Disponibilidad**
- **RNF-004.1** El sistema debe mantener uptime mínimo de 99.5% (SLA)
- **RNF-004.2** El sistema debe implementar health checks automáticos
- **RNF-004.3** El sistema debe recuperarse automáticamente de fallos menores
- **RNF-004.4** El sistema debe mantener backup automático diario de base de datos
- **RNF-004.5** El sistema debe implementar redundancia en componentes críticos
- **RNF-004.6** El sistema debe tener plan de disaster recovery documentado
- **RNF-004.7** El sistema debe monitorear recursos (CPU, memoria, disco) automáticamente

### **RNF-005: Mantenibilidad**
- **RNF-005.1** El sistema debe seguir principios SOLID y clean architecture
- **RNF-005.2** El sistema debe mantener cobertura de tests mínima del 80%
- **RNF-005.3** El sistema debe usar dependency injection para acoplamiento bajo
- **RNF-005.4** El sistema debe implementar logging estructurado con niveles
- **RNF-005.5** El sistema debe documentar API con OpenAPI/Swagger
- **RNF-005.6** El sistema debe usar convenciones de código consistentes (PSR-12)
- **RNF-005.7** El sistema debe implementar CI/CD pipeline automatizado

### **RNF-006: Usabilidad de API**
- **RNF-006.1** El sistema debe retornar mensajes de error descriptivos y localizados
- **RNF-006.2** El sistema debe implementar versionado de API (/api/v1, /api/v2)
- **RNF-006.3** El sistema debe incluir metadata en respuestas (timestamps, paginación)
- **RNF-006.4** El sistema debe usar nombres de endpoints intuitivos y consistentes
- **RNF-006.5** El sistema debe implementar CORS configurable para frontends
- **RNF-006.6** El sistema debe incluir documentación interactiva de API
- **RNF-006.7** El sistema debe retornar códigos de estado HTTP semánticamente correctos

### **RNF-007: Compatibilidad**
- **RNF-007.1** El sistema debe ser compatible con PHP 8.1+ y Laravel 11+
- **RNF-007.2** El sistema debe soportar MySQL 8.0+ y PostgreSQL 13+
- **RNF-007.3** El sistema debe funcionar en servidores Linux (Ubuntu 20.04+)
- **RNF-007.4** El sistema debe ser compatible con Redis 6.0+ para cache
- **RNF-007.5** El sistema debe soportar deployment en Docker containers
- **RNF-007.6** El sistema debe ser compatible con nginx/Apache como reverse proxy
- **RNF-007.7** El sistema debe funcionar en cloud providers (AWS, Azure, GCP)

### **RNF-008: Configurabilidad**
- **RNF-008.1** El sistema debe usar variables de entorno para configuración
- **RNF-008.2** El sistema debe permitir configuración de límites y timeouts
- **RNF-008.3** El sistema debe implementar feature flags para funcionalidades
- **RNF-008.4** El sistema debe permitir configuración de workflows sin código
- **RNF-008.5** El sistema debe soportar múltiples idiomas (i18n)
- **RNF-008.6** El sistema debe permitir configuración de templates de email
- **RNF-008.7** El sistema debe implementar configuración de reglas de negocio

---

## ⚙️ Estado del Servidor

**Servidor activo:** `http://127.0.0.1:8000`

**Guía de endpoints:** [POSTMAN_GUIDE.md](./POSTMAN_GUIDE.md)

---

## ✨ Características Técnicas Implementadas

### 🔐 **Seguridad y Autenticación Completa**
- **Laravel Sanctum** para tokens API seguros
- **59 permisos granulares** con Spatie Permission
- **5 roles predefinidos** con jerarquía: Super Admin, Admin, Gerente, Subgerente, Mesa Partes, Ciudadano
- **Middleware de autorización** en todas las rutas protegidas
- **Guards web y api** configurados
- **Validación CSRF** en formularios web

### 👥 **Gestión Completa de Usuarios y Roles**
- ✅ **CRUD de usuarios** con validaciones
- ✅ **Creación y edición de roles** personalizados
- ✅ **Asignación de permisos** granular por usuario/rol
- ✅ **Gestión de estados** (activo/inactivo)
- ✅ **Campos personalizados**: teléfono, cargo, gerencia
- ✅ **Cambio de contraseñas** seguro
- ✅ **Verificación de email** disponible

### 🏢 **Arquitectura de Gerencias Jerárquica**
- ✅ **Creación de gerencias** principales
- ✅ **Subgerencias ilimitadas** (estructura padre-hijo)
- ✅ **Asignación de usuarios** a múltiples gerencias
- ✅ **Flujos específicos** por tipo de gerencia
- ✅ **Estadísticas por gerencia** individuales
- ✅ **Jerarquía completa** visualizable

### 📋 **Gestión Avanzada de Expedientes**
- ✅ **CRUD completo** con validaciones
- ✅ **Estados dinámicos** según workflow asignado
- ✅ **Derivaciones entre gerencias** con trazabilidad
- ✅ **Asignación a funcionarios** específicos
- ✅ **Historial completo** de cambios y movimientos
- ✅ **Gestión documental** integrada (carga de archivos)
- ✅ **Búsqueda avanzada** por múltiples criterios
- ✅ **Exportación de datos** (Excel, PDF)
- ✅ **Prioridades** y estados personalizados

### 🔄 **Workflows Personalizables Avanzados**
- ✅ **Creación de workflows** por tipo de trámite
- ✅ **Pasos secuenciales** con reglas específicas
- ✅ **Transiciones automáticas** entre estados
- ✅ **Reglas de negocio** configurables
- ✅ **Progreso visual** del expediente
- ✅ **Validaciones** antes de cambios de estado

### 📊 **Reportes y Estadísticas**
- ✅ **Dashboard administrativo** con métricas en tiempo real
- ✅ **Estadísticas por gerencia** individuales
- ✅ **Reportes de expedientes** (creados, procesados, tiempos promedio)
- ✅ **Estadísticas de usuarios** y actividad
- ✅ **Métricas de mesa de partes** y recepción
- ✅ **Exportación** en Excel/PDF

### 🔔 **Sistema de Notificaciones Integrado**
- ✅ **Notificaciones automáticas** por cambio de estado
- ✅ **Alertas personalizadas** por rol y función
- ✅ **Histórico de notificaciones** completo
- ✅ **Templates configurables** de mensajes
- ✅ **Integración con email** (opcional)

### 📱 **API REST Completa**
- ✅ **Endpoints documentados** para todas las funciones
- ✅ **Autenticación** via Sanctum tokens
- ✅ **Validación de permisos** en cada endpoint
- ✅ **Códigos de respuesta HTTP** estándar
- ✅ **Rate limiting** configurado
- ✅ **CORS** habilitado para frontends externos

---

## 🏗️ Arquitectura Técnica

### **Backend (Laravel 11)**
- **Framework:** Laravel 11.x LTS
- **Base de datos:** SQLite (desarrollo) / MySQL/PostgreSQL (producción)
- **Autenticación:** Laravel Sanctum + Spatie Permission
- **Validaciones:** Form Requests personalizadas
- **Middleware:** Protección de rutas y validación de permisos
- **Queue System:** Para notificaciones y procesos pesados

### **Frontend (Híbrido)**
- **Admin Panel:** Blade Templates + Tailwind CSS 3.x
- **Ciudadano Portal:** Preparado para Angular/React SPA
- **Componentes:** Alpine.js para interactividad
- **Responsive Design:** Mobile-first approach
- **Icons:** Heroicons + FontAwesome

### **Seguridad**
- **CSRF Protection:** Habilitado en formularios
- **SQL Injection:** Prevención via Eloquent ORM
- **XSS Protection:** Escapado automático en Blade
- **Rate Limiting:** Configurado en rutas públicas
- **File Upload:** Validación estricta de tipos y tamaños

---

## 📊 Base de Datos

### **Tablas Principales:**
```sql
-- Usuarios y Autenticación
users (id, name, email, password, gerencia_id, telefono, cargo, activo)
personal_access_tokens (tokenable_type, tokenable_id, name, token)
sessions (id, user_id, ip_address, user_agent)

-- Roles y Permisos (Spatie)
roles (id, name, guard_name)
permissions (id, name, guard_name)  -- 59+ permisos
model_has_roles (role_id, model_type, model_id)
model_has_permissions (permission_id, model_type, model_id)
role_has_permissions (permission_id, role_id)

-- Estructura Organizacional
gerencias (id, nombre, descripcion, parent_id, activa)

-- Gestión de Expedientes
expedientes (id, numero, solicitante_nombre, solicitante_email, estado, gerencia_id, user_id)
documentos_expediente (id, expediente_id, nombre, ruta, tipo, tamaño)
historial_expedientes (id, expediente_id, user_id, accion, estado_anterior, estado_nuevo)

-- Workflows Personalizables
workflows (id, nombre, descripcion, activo)
workflow_steps (id, workflow_id, nombre, orden, reglas)
workflow_transitions (id, step_from_id, step_to_id, condiciones)
expediente_workflow_progress (id, expediente_id, workflow_id, step_actual_id)

-- Mesa de Partes
mesa_partes (id, numero_documento, tipo_documento_id, solicitante, fecha_recepcion)
mesa_partes_archivos (id, mesa_parte_id, nombre_archivo, ruta)
tipo_documentos (id, nombre, descripcion, requisitos)
tipo_tramites (id, nombre, descripcion, documentos_requeridos)

-- Auditoría y Notificaciones
action_logs (id, user_id, action, model_type, model_id, datos_anteriores, datos_nuevos)
notifications (id, type, notifiable_type, notifiable_id, data, read_at)

-- Módulos Adicionales
complaints (id, titulo, descripcion, solicitante_email, estado, respuesta)
payments (id, expediente_id, monto, numero_recibo, fecha_pago, verificado)
procedures (id, nombre, descripcion, pasos, documentos_requeridos)
```

---

}- ✅ **Creación visual** desde la interfaz web

```- ✅ **Pasos configurables**: Inicio, Proceso, Decisión, Fin

- ✅ **Transiciones condicionales** con reglas JSON

### Registro- ✅ **Activación/Desactivación** dinámica de workflows

```http- ✅ **Clonación** de workflows existentes

POST /api/auth/register- ✅ **Múltiples tipos**: Expediente, Trámite, Proceso

Content-Type: application/json- ✅ **Configuración JSON** para pasos y transiciones

- ✅ **API REST completa** para integración frontend

{

    "name": "Juan Pérez",### 📝 **Mesa de Partes Completa**

    "email": "juan.perez@municipalidad.gob.pe",- ✅ **Registro de documentos** de entrada

    "password": "password123",- ✅ **Códigos de seguimiento** únicos automáticos

    "password_confirmation": "password123",- ✅ **Consulta pública** por código de seguimiento

    "gerencia_id": 2- ✅ **Tipos de documento** configurables

}- ✅ **Tipos de trámite** con documentos requeridos

```- ✅ **Derivación automática** según reglas

- ✅ **Observaciones** y seguimiento de estados

### Obtener Usuario Autenticado

```http### 📊 **Reportes y Estadísticas**

GET /api/auth/user- ✅ **Dashboard administrativo** con métricas

Authorization: Bearer {token}- ✅ **Estadísticas por gerencia** individuales

```- ✅ **Reportes de expedientes** (creados, procesados, tiempos)

- ✅ **Estadísticas de usuarios** y actividad

### Logout- ✅ **Métricas de mesa de partes** y recepción

```http

POST /api/auth/logout---

Authorization: Bearer {token}

```## 🎯 Funcionalidades Detalladas del Administrador



---### 👤 **Gestión de Usuarios**

El administrador puede realizar las siguientes acciones:

## 👥 Usuarios {#usuarios}

#### **Crear Usuarios**

### Listar Usuarios```http

```httpPOST /api/usuarios

GET /api/usuarios{

Authorization: Bearer {token}    "name": "Juan Pérez",

```    "email": "juan@municipalidad.com",

    "password": "password123",

### Crear Usuario    "telefono": "+51987654321",

```http    "cargo": "Funcionario de Licencias",

POST /api/usuarios    "activo": true

Authorization: Bearer {token}}

Content-Type: application/json```



{#### **Asignar Roles a Usuarios**

    "name": "María García",```http

    "email": "maria.garcia@municipalidad.gob.pe",POST /api/usuarios/{user}/roles

    "password": "password123",{

    "gerencia_id": 3,    "role": "funcionario"

    "telefono": "987654321",}

    "cargo": "Especialista en Licencias"```

}

```#### **Asignar Permisos Específicos**

```http

### Obtener UsuarioPOST /api/usuarios/{user}/permissions

```http{

GET /api/usuarios/{id}    "permissions": ["crear_expedientes", "derivar_expediente"]

Authorization: Bearer {token}}

``````



### Actualizar Usuario#### **Sincronizar Permisos**

```http```http

PUT /api/usuarios/{id}POST /api/usuarios/{user}/permissions/sync

Authorization: Bearer {token}{

Content-Type: application/json    "permissions": ["ver_expedientes", "crear_expedientes", "editar_expedientes"]

}

{```

    "name": "María García Actualizada",

    "telefono": "987654322",---

## 🛠️ Estructura Técnica del Proyecto

### 📂 **Base de Datos - Seeders Unificados**
El proyecto utiliza seeders idempotentes (pueden ejecutarse múltiples veces sin duplicar datos):

- **`RolesAndPermissionsSeeder`** - Roles y permisos del sistema
- **`GerenciasSeeder`** - Estructura de gerencias municipales
- **`UsersSeeder`** - Usuarios con credenciales predefinidas
- **`TipoDocumentoSeeder`** - Tipos de documentos (upsert por código)
- **`TipoTramiteSeeder`** - Tipos de trámites (upsert por código)
- **`ProceduresSeeder`** - Procedimientos TUPA
- **`WorkflowRulesSeeder`** - Reglas de flujo automático
- **`ExpedientesSeeder`** - Expedientes de ejemplo

#### ⚠️ **Comandos de Seeding**
```bash
# Limpiar y recrear toda la base de datos
php artisan migrate:fresh --seed

# Solo ejecutar seeders (preserva datos existentes)
php artisan db:seed

# Seeder específico
php artisan db:seed --class=UsersSeeder
```

#### 🔑 **Credenciales de Prueba Garantizadas**
```
Super Admin:     superadmin@muni.gob.pe     / password123
Alcalde:         alcalde@muni.gob.pe        / alcalde123
Gerente:         gerente.municipal@muni.gob.pe / gerente123
Ciudadano:       juan.ciudadano@gmail.com   / ciudadano123
```

### 🚦 **Rutas API Organizadas**
- **Públicas**: `/api/public/*` - Sin autenticación
- **Debug**: `/api/debug/*` - Solo desarrollo (eliminar en producción)
- **Formularios**: `/api/form-data/*` - Datos para frontend (temporal)
- **Autenticación**: `/api/auth/*` - Login, logout, registro
- **Protegidas**: Requieren `Authorization: Bearer {token}`

### 📋 **Meta Endpoints Añadidos**
- `GET /api/health` - Estado del servidor
- `GET /api/version` - Versión de Laravel/PHP
- **Fallback 404** - JSON uniforme para rutas no encontradas

---

## 📖 Documentación Completa

Para testing completo con Postman, consulta: **[POSTMAN_GUIDE.md](./POSTMAN_GUIDE.md)**

---

## 🎭 **Gestión de Roles y Permisos**

    "cargo": "Jefe de Licencias"

}#### **Crear Roles Personalizados**

``````http

POST /api/roles

### Asignar Rol{

```http    "name": "Supervisor de Licencias",

POST /api/usuarios/{id}/roles    "guard_name": "web",

Authorization: Bearer {token}    "permissions": ["ver_expedientes", "aprobar_expediente"]

Content-Type: application/json}

```

{

    "role": "funcionario"#### **Crear Permisos Personalizados**

}```http

```POST /api/permissions

{

---    "name": "revisar_licencias_comerciales",

    "guard_name": "web"

## 🏢 Gerencias {#gerencias}}

```

### Listar Gerencias

```http#### **Editar Roles Existentes**

GET /api/gerencias```http

Authorization: Bearer {token}PUT /api/roles/{role}

```{

    "name": "Supervisor de Licencias Actualizado",

### Crear Gerencia    "permissions": ["ver_expedientes", "aprobar_expediente", "rechazar_expediente"]

```http}

POST /api/gerencias```

Authorization: Bearer {token}

Content-Type: application/json### 🏢 **Gestión de Gerencias**



{#### **Crear Gerencias Principales**

    "nombre": "Gerencia de Medio Ambiente",```http

    "codigo": "GMA",POST /api/gerencias

    "descripcion": "Encargada de temas ambientales",{

    "tipo": "gerencia",    "nombre": "Gerencia de Desarrollo Urbano",

    "gerencia_padre_id": 1,    "codigo": "GDU",

    "activo": true    "descripcion": "Encargada del desarrollo urbano municipal",

}    "tipo": "operativa",

```    "activo": true

}

### Obtener Gerencia```

```http

GET /api/gerencias/{id}#### **Crear Subgerencias**

Authorization: Bearer {token}```http

```POST /api/gerencias

{

### Actualizar Gerencia    "nombre": "Subgerencia de Licencias",

```http    "codigo": "SGL",

PUT /api/gerencias/{id}    "descripcion": "Manejo de licencias de funcionamiento",

Authorization: Bearer {token}    "tipo": "subgerencia",

Content-Type: application/json    "parent_id": 1,

    "activo": true

{}

    "nombre": "Gerencia de Medio Ambiente y Salud",```

    "descripcion": "Encargada de temas ambientales y de salud pública"

}#### **Asignar Usuarios a Gerencias**

``````http

POST /api/gerencias/{gerencia}/usuarios

### Obtener Subgerencias{

```http    "user_id": 5,

GET /api/gerencias/{id}/subgerencias    "cargo_especifico": "Especialista en Licencias"

Authorization: Bearer {token}}

``````



### Obtener Usuarios de Gerencia#### **Obtener Jerarquía Completa**

```http```http

GET /api/gerencias/{id}/usuariosGET /api/gerencias/jerarquia

Authorization: Bearer {token}```

```

### � **Gestión de Expedientes**

---

#### **Derivar Expedientes Entre Gerencias**

## 📄 Expedientes {#expedientes}```http

POST /api/expedientes/{expediente}/derivar

### Listar Expedientes{

```http    "gerencia_destino_id": 2,

GET /api/expedientes    "usuario_destino_id": 8,

Authorization: Bearer {token}    "observaciones": "Requiere revisión técnica especializada",

```    "prioridad": "alta"

}

### Crear Expediente```

```http

POST /api/expedientes#### **Aprobar/Rechazar Expedientes**

Authorization: Bearer {token}```http

Content-Type: application/jsonPOST /api/expedientes/{expediente}/aprobar

{

{    "observaciones": "Expediente aprobado según normativa vigente",

    "numero_expediente": "EXP-2025-000001",    "documento_resolution": "RES-2025-001"

    "asunto": "Solicitud de Licencia de Funcionamiento",}

    "tipo_tramite_id": 1,

    "solicitante_nombre": "Carlos Mendoza",POST /api/expedientes/{expediente}/rechazar

    "solicitante_email": "carlos.mendoza@email.com",{

    "solicitante_telefono": "987654321",    "motivo": "Documentación incompleta",

    "solicitante_dni": "12345678",    "observaciones": "Falta certificado de zonificación"

    "gerencia_id": 2,}

    "prioridad": "normal",```

    "observaciones": "Documentos completos"

}#### **Subir Documentos**

``````http

POST /api/expedientes/{expediente}/documentos

### Obtener ExpedienteContent-Type: multipart/form-data

```http{

GET /api/expedientes/{id}    "archivo": [archivo],

Authorization: Bearer {token}    "tipo_documento": "resolucion",

```    "descripcion": "Resolución de aprobación"

}

### Actualizar Expediente```

```http

PUT /api/expedientes/{id}### 🔄 **Gestión de Workflows Personalizables**

Authorization: Bearer {token}

Content-Type: application/json#### **Crear Workflow Completo**

```http

{POST /api/custom-workflows

    "asunto": "Solicitud de Licencia de Funcionamiento - Actualizada",{

    "observaciones": "Documentos completos y revisados"    "nombre": "Flujo de Licencias Comerciales",

}    "descripcion": "Proceso completo para licencias de funcionamiento",

```    "tipo": "expediente",

    "activo": true

### Derivar Expediente}

```http```

POST /api/expedientes/{id}/derivar

Authorization: Bearer {token}#### **Crear Pasos del Workflow**

Content-Type: application/json```http

POST /api/custom-workflow-steps

{{

    "gerencia_destino_id": 3,    "workflow_id": 1,

    "usuario_destino_id": 5,    "nombre": "Revisión Inicial",

    "observaciones": "Derivado para evaluación técnica"    "descripcion": "Verificación de documentos básicos",

}    "tipo": "proceso",

```    "orden": 1,

    "configuracion": {

### Aprobar Expediente        "requiere_aprobacion": true,

```http        "tiempo_limite_dias": 5,

POST /api/expedientes/{id}/aprobar        "usuarios_autorizados": ["funcionario", "supervisor"]

Authorization: Bearer {token}    },

Content-Type: application/json    "activo": true

}

{```

    "observaciones": "Expediente aprobado conforme a normativa"

}#### **Crear Transiciones**

``````http

POST /api/custom-workflow-transitions

### Rechazar Expediente{

```http    "workflow_id": 1,

POST /api/expedientes/{id}/rechazar    "from_step_id": 1,

Authorization: Bearer {token}    "to_step_id": 2,

Content-Type: application/json    "nombre": "Aprobar Revisión",

    "descripcion": "Transición cuando la revisión es aprobada",

{    "condicion": {

    "motivo": "Documentación incompleta",        "estado_anterior": "revision_inicial",

    "observaciones": "Falta certificado de zonificación"        "accion": "aprobar",

}        "rol_requerido": "supervisor"

```    },

    "orden": 1,

### Subir Documento    "activo": true

```http}

POST /api/expedientes/{id}/documentos```

Authorization: Bearer {token}

Content-Type: multipart/form-data#### **Clonar Workflows**

```http

{POST /api/custom-workflows/{id}/clone

    "file": [archivo],{

    "tipo_documento": "licencia",    "nuevo_nombre": "Flujo de Licencias Comerciales - Copia",

    "descripcion": "Licencia de funcionamiento aprobada"    "modificaciones": {

}        "tipo": "tramite"

```    }

}

---```



## 📋 Mesa de Partes {#mesa-de-partes}### 📝 **Gestión de Mesa de Partes**



### Listar Mesa de Partes#### **Configurar Tipos de Trámite**

```http```http

GET /api/mesa-partesPOST /api/tipos-tramite

Authorization: Bearer {token}{

```    "nombre": "Licencia de Funcionamiento",

    "codigo": "LF",

### Crear Registro en Mesa de Partes    "descripcion": "Trámite para obtener licencia comercial",

```http    "documentos_requeridos": [

POST /api/mesa-partes        "DNI del solicitante",

Authorization: Bearer {token}        "Certificado de zonificación",

Content-Type: application/json        "Plano de distribución"

    ],

{    "costo": 150.00,

    "tipo_documento_id": 1,    "tiempo_respuesta_dias": 15

    "tipo_tramite_id": 1,}

    "remitente_nombre": "Ana López",```

    "remitente_email": "ana.lopez@email.com",

    "remitente_telefono": "987654321",#### **Derivar Documentos Automáticamente**

    "remitente_dni": "87654321",```http

    "asunto": "Solicitud de certificado de numeración",POST /api/mesa-partes/{id}/derivar

    "folio_inicio": 1,{

    "folio_fin": 5,    "gerencia_destino_id": 2,

    "observaciones": "Documentos en original",    "usuario_asignado_id": 5,

    "gerencia_destino_id": 2    "prioridad": "normal",

}    "observaciones": "Derivado según tipo de trámite"

```}

```

### Obtener Registro

```http---

GET /api/mesa-partes/{id}

Authorization: Bearer {token}## 🚀 Tecnologías

```

- **Backend**: Laravel 11

### Derivar Documento- **Base de Datos**: SQLite/MySQL

```http- **Autenticación**: Laravel Sanctum

POST /api/mesa-partes/{id}/derivar- **Permisos**: Spatie Laravel Permission

Authorization: Bearer {token}- **Documentación**: Markdown completo

Content-Type: application/json

---

{

    "gerencia_destino_id": 3,## 📦 Instalación Rápida

    "observaciones": "Derivado para evaluación"

}```bash

```# 1. Instalar dependencias

composer install

### Consulta Pública por Código

```http# 2. Configurar environment

GET /api/mesa-partes/consultar/{codigoSeguimiento}cp .env.example .env

```php artisan key:generate



**Ejemplo:**# 3. Configurar base de datos en .env

```httpDB_CONNECTION=mysql

GET /api/mesa-partes/consultar/MP-2025-000001DB_DATABASE=tramite_muni

```DB_USERNAME=usuario

DB_PASSWORD=password

---

# 4. Ejecutar migraciones y seeders

## 🔄 Workflows Personalizables {#workflows}php artisan migrate

php artisan db:seed

### Listar Workflows

```http# 5. Crear enlace simbólico para storage

GET /api/custom-workflowsphp artisan storage:link

Authorization: Bearer {token}

```# 6. Iniciar servidor

php artisan serve

### Crear Workflow```

```http

POST /api/custom-workflows---

Authorization: Bearer {token}

Content-Type: application/json## 🌐 URLs de Prueba



{### **Interfaz Administrativa**

    "nombre": "Proceso de Licencia de Funcionamiento",```

    "descripcion": "Workflow para licencias comerciales",http://localhost:8000/test_admin.html

    "tipo": "expediente",```

    "gerencia_id": 2,

    "activo": true,### **API de Expedientes**

    "configuracion": {```

        "tiempo_limite_dias": 15,http://localhost:8000/test_api.html

        "requiere_aprobacion": true```

    }

}### **Mesa de Partes**

``````

http://localhost:8000/test_mesa_partes_api.html

### Obtener Workflow```

```http

GET /api/custom-workflows/{id}---

Authorization: Bearer {token}

```## 👤 Usuarios de Prueba



### Crear Paso de Workflow| Rol | Email | Password | Permisos |

```http|-----|-------|----------|----------|

POST /api/custom-workflow-steps| Super Admin | `superadmin@example.com` | `password` | Todos (59 permisos) |

Authorization: Bearer {token}| Admin | `admin@example.com` | `password` | Gestión completa + workflows |

Content-Type: application/json| Jefe de Gerencia | `jefe@example.com` | `password` | Gestión de gerencia + workflows básicos |

| Funcionario | `funcionario@example.com` | `password` | Procesamiento de expedientes |

{| Ciudadano | `ciudadano@example.com` | `password` | Creación y consulta |

    "workflow_id": 1,

    "nombre": "Revisión Inicial",---

    "descripcion": "Revisión de documentos",

    "orden": 1,## 📱 API Endpoints Completos

    "tipo": "manual",

    "responsable_rol": "funcionario",### 🔐 **Autenticación** (`/api/auth/*`)

    "tiempo_limite_horas": 48,```http

    "configuracion": {POST   /api/auth/login                    # Login

        "campos_requeridos": ["observaciones"],POST   /api/auth/register                 # Registro

        "puede_rechazar": truePOST   /api/auth/logout                   # Logout

    }GET    /api/auth/user                     # Usuario actual

}POST   /api/auth/refresh                  # Refresh token

```POST   /api/auth/change-password          # Cambiar contraseña

GET    /api/auth/check-email              # Verificar email

---```



## 👮‍♂️ Roles y Permisos {#roles-y-permisos}### 👥 **Gestión de Usuarios** (`/api/usuarios/*`)

```http

### Listar RolesGET    /api/usuarios                      # Listar usuarios

```httpPOST   /api/usuarios                      # Crear usuario

GET /api/rolesGET    /api/usuarios/{id}                 # Obtener usuario

Authorization: Bearer {token}PUT    /api/usuarios/{id}                 # Actualizar usuario

```DELETE /api/usuarios/{id}                 # Eliminar usuario

POST   /api/usuarios/{id}/estado          # Cambiar estado

### Crear RolPOST   /api/usuarios/{id}/roles           # Asignar rol

```httpDELETE /api/usuarios/{id}/roles/{role}    # Remover rol

POST /api/rolesPOST   /api/usuarios/{id}/permissions     # Asignar permisos

Authorization: Bearer {token}POST   /api/usuarios/{id}/permissions/sync # Sincronizar permisos

Content-Type: application/jsonPOST   /api/usuarios/{id}/password        # Cambiar contraseña

GET    /api/usuarios/role/{role}          # Usuarios por rol

{GET    /api/usuarios/gerencia/{gerencia}  # Usuarios por gerencia

    "name": "especialista_licencias",```

    "guard_name": "web",

    "permissions": ["ver_expediente", "editar_expediente", "derivar_expediente"]### 🎭 **Roles y Permisos** (`/api/roles/*`, `/api/permissions/*`)

}```http

```GET    /api/roles                         # Listar roles

POST   /api/roles                         # Crear rol

### Listar PermisosGET    /api/roles/{role}                  # Obtener rol

```httpPUT    /api/roles/{role}                  # Actualizar rol

GET /api/permissionsDELETE /api/roles/{role}                  # Eliminar rol

Authorization: Bearer {token}

```GET    /api/permissions                   # Listar permisos

POST   /api/permissions                   # Crear permiso

### Crear PermisoGET    /api/permissions/{permission}      # Obtener permiso

```httpPUT    /api/permissions/{permission}      # Actualizar permiso

POST /api/permissionsDELETE /api/permissions/{permission}      # Eliminar permiso

Authorization: Bearer {token}```

Content-Type: application/json

### 🏢 **Gerencias** (`/api/gerencias/*`)

{```http

    "name": "generar_reporte_avanzado",GET    /api/gerencias                     # Listar gerencias

    "guard_name": "web"POST   /api/gerencias                     # Crear gerencia

}GET    /api/gerencias/{id}                # Obtener gerencia

```PUT    /api/gerencias/{id}                # Actualizar gerencia

DELETE /api/gerencias/{id}                # Eliminar gerencia

---POST   /api/gerencias/{id}/estado         # Cambiar estado

GET    /api/gerencias/{id}/subgerencias   # Obtener subgerencias

## 📊 Catálogos {#catalogos}GET    /api/gerencias/{id}/usuarios       # Usuarios de gerencia

POST   /api/gerencias/{id}/usuarios       # Asignar usuario

### Tipos de DocumentoDELETE /api/gerencias/{id}/usuarios/{user} # Remover usuario

```httpGET    /api/gerencias/jerarquia           # Jerarquía completa

GET /api/tipos-documentoGET    /api/gerencias/tipo/{tipo}         # Gerencias por tipo

``````



**Respuesta:**### 📋 **Expedientes** (`/api/expedientes/*`)

```json```http

{GET    /api/expedientes                   # Listar expedientes

    "data": [POST   /api/expedientes                   # Crear expediente

        {GET    /api/expedientes/{id}              # Obtener expediente

            "id": 1,PUT    /api/expedientes/{id}              # Actualizar expediente

            "nombre": "Solicitud Simple",DELETE /api/expedientes/{id}              # Eliminar expediente

            "codigo": "SOL-001",POST   /api/expedientes/{id}/derivar      # Derivar expediente

            "descripcion": "Solicitud de trámite simple",POST   /api/expedientes/{id}/aprobar      # Aprobar expediente

            "requiere_firma": true,POST   /api/expedientes/{id}/rechazar     # Rechazar expediente

            "vigencia_dias": 30POST   /api/expedientes/{id}/documentos   # Subir documento

        }GET    /api/expedientes/{id}/history      # Historial de cambios

    ]POST   /api/expedientes/{id}/assign       # Asignar a usuario

}GET    /api/expedientes/estadisticas      # Estadísticas

```GET    /api/expedientes/exportar          # Exportar datos

```

### Tipos de Trámite

```http### 🔄 **Workflows Personalizables** ⭐ (`/api/custom-workflows/*`)

GET /api/tipos-tramite```http

```GET    /api/custom-workflows              # Listar workflows

POST   /api/custom-workflows              # Crear workflow

**Respuesta:**GET    /api/custom-workflows/{id}         # Obtener workflow

```jsonPUT    /api/custom-workflows/{id}         # Actualizar workflow

{DELETE /api/custom-workflows/{id}         # Eliminar workflow

    "data": [POST   /api/custom-workflows/{id}/toggle  # Activar/desactivar

        {POST   /api/custom-workflows/{id}/clone   # Clonar workflow

            "id": 1,GET    /api/custom-workflows/tipo/{tipo}  # Por tipo

            "nombre": "Licencia de Funcionamiento",

            "codigo": "LF-001",# Pasos de Workflow

            "costo": 125.50,GET    /api/custom-workflow-steps         # Listar pasos

            "tiempo_estimado_dias": 15,POST   /api/custom-workflow-steps         # Crear paso

            "gerencia": {GET    /api/custom-workflow-steps/{id}    # Obtener paso

                "id": 2,PUT    /api/custom-workflow-steps/{id}    # Actualizar paso

                "nombre": "Gerencia de Desarrollo Económico"DELETE /api/custom-workflow-steps/{id}    # Eliminar paso

            }

        }# Transiciones de Workflow

---

## 🚀 API Endpoints - Referencia Completa

### **Autenticación y Tokens**
```bash
# Autenticación Sanctum
POST   /api/auth/login              # Login con email/password
POST   /api/auth/logout             # Logout e invalidar token
POST   /api/auth/refresh            # Refrescar token
GET    /api/auth/user               # Datos del usuario autenticado
POST   /api/auth/verify-token       # Validar token actual

# Gestión de Tokens
GET    /api/auth/tokens             # Listar tokens del usuario
DELETE /api/auth/tokens/{id}        # Revocar token específico
DELETE /api/auth/tokens/all         # Revocar todos los tokens
```

### **Gestión de Usuarios**
```bash
# CRUD Usuarios (Requiere permisos admin)
GET    /api/users                   # Listar usuarios con filtros
POST   /api/users                   # Crear nuevo usuario
GET    /api/users/{id}              # Obtener usuario específico
PUT    /api/users/{id}              # Actualizar usuario
DELETE /api/users/{id}              # Eliminar usuario (soft delete)

# Perfiles y Configuración
GET    /api/users/profile           # Perfil del usuario actual
PUT    /api/users/profile           # Actualizar perfil propio
POST   /api/users/change-password   # Cambiar contraseña
GET    /api/users/{id}/permissions  # Permisos del usuario
POST   /api/users/{id}/sync-roles   # Sincronizar roles
```

### **Roles y Permisos**
```bash
# Gestión de Roles
GET    /api/roles                   # Listar roles del sistema
POST   /api/roles                   # Crear nuevo rol
GET    /api/roles/{id}              # Obtener rol específico
PUT    /api/roles/{id}              # Actualizar rol
DELETE /api/roles/{id}              # Eliminar rol

# Gestión de Permisos
GET    /api/permissions             # Listar todos los permisos
POST   /api/permissions             # Crear nuevo permiso
GET    /api/permissions/{id}        # Obtener permiso específico
PUT    /api/permissions/{id}        # Actualizar permiso
DELETE /api/permissions/{id}        # Eliminar permiso

# Asignación Roles-Permisos
POST   /api/roles/{id}/permissions  # Asignar permisos a rol
DELETE /api/roles/{id}/permissions/{permissionId} # Quitar permiso
GET    /api/roles/{id}/users        # Usuarios con el rol
```

### **Gestión de Expedientes**
```bash
# CRUD Expedientes
GET    /api/expedientes             # Listar expedientes con filtros
POST   /api/expedientes             # Crear nuevo expediente
GET    /api/expedientes/{id}        # Obtener expediente específico
PUT    /api/expedientes/{id}        # Actualizar expediente
DELETE /api/expedientes/{id}        # Eliminar expediente

# Búsquedas y Filtros
GET    /api/expedientes/search      # Búsqueda avanzada
GET    /api/expedientes/por-estado/{estado} # Filtrar por estado
GET    /api/expedientes/por-gerencia/{gerenciaId} # Filtrar por gerencia
GET    /api/expedientes/asignados   # Expedientes asignados al usuario

# Gestión de Estados
PUT    /api/expedientes/{id}/estado # Cambiar estado del expediente
POST   /api/expedientes/{id}/asignar # Asignar funcionario
POST   /api/expedientes/{id}/transferir # Transferir entre gerencias
POST   /api/expedientes/{id}/observar # Marcar como observado
POST   /api/expedientes/{id}/aprobar # Aprobar expediente
```

### **Documentos y Archivos**
```bash
# Gestión de Documentos
GET    /api/expedientes/{id}/documentos # Listar documentos del expediente
POST   /api/expedientes/{id}/documentos # Subir nuevo documento
GET    /api/documentos/{id}            # Obtener documento específico
PUT    /api/documentos/{id}            # Actualizar metadata
DELETE /api/documentos/{id}            # Eliminar documento

# Descarga y Visualización
GET    /api/documentos/{id}/download   # Descargar archivo
GET    /api/documentos/{id}/preview    # Vista previa (imágenes/PDFs)
GET    /api/documentos/{id}/thumbnail  # Miniatura
POST   /api/documentos/bulk-download   # Descarga múltiple (ZIP)
```

### **Workflows Personalizables**
```bash
# Gestión de Workflows
GET    /api/custom-workflows          # Listar workflows
POST   /api/custom-workflows          # Crear workflow
GET    /api/custom-workflows/{id}     # Obtener workflow específico
PUT    /api/custom-workflows/{id}     # Actualizar workflow
DELETE /api/custom-workflows/{id}     # Eliminar workflow

# Pasos de Workflow
GET    /api/custom-workflows/{id}/steps # Listar pasos
POST   /api/custom-workflows/{id}/steps # Crear paso
GET    /api/custom-workflow-steps/{id}  # Obtener paso
PUT    /api/custom-workflow-steps/{id}  # Actualizar paso
DELETE /api/custom-workflow-steps/{id} # Eliminar paso

# Transiciones y Progreso
GET    /api/custom-workflow-transitions # Listar transiciones
POST   /api/custom-workflow-transitions # Crear transición
GET    /api/expedientes/{id}/workflow-progress # Progreso del expediente
POST   /api/expedientes/{id}/advance-step # Avanzar al siguiente paso
```

### **Historial y Auditoría**
```bash
# Historial de Expedientes
GET    /api/expedientes/{id}/historial # Historial completo
POST   /api/expedientes/{id}/comentario # Agregar comentario
GET    /api/historial                  # Historial global con filtros

# Logs de Auditoría
GET    /api/action-logs                # Logs de acciones del sistema
GET    /api/action-logs/user/{id}      # Logs por usuario
GET    /api/action-logs/model/{type}/{id} # Logs por modelo específico
```

### **Notificaciones**
```bash
# Gestión de Notificaciones
GET    /api/notifications              # Notificaciones del usuario
POST   /api/notifications/{id}/read    # Marcar como leída
POST   /api/notifications/read-all     # Marcar todas como leídas
DELETE /api/notifications/{id}         # Eliminar notificación
GET    /api/notifications/unread-count # Contador de no leídas
```

### **Reportes y Estadísticas**
```bash
# Reportes Predefinidos
GET    /api/reportes/dashboard         # Estadísticas del dashboard
GET    /api/reportes/expedientes-por-estado # Reporte por estados
GET    /api/reportes/tiempo-promedio   # Tiempos promedio de trámites
GET    /api/reportes/productividad     # Productividad por funcionario

# Exportación
GET    /api/reportes/export/excel      # Exportar a Excel
GET    /api/reportes/export/pdf        # Exportar a PDF
GET    /api/reportes/export/csv        # Exportar a CSV
```

### **Configuración del Sistema**
```bash
# Tipos de Trámites
GET    /api/tipo-tramites              # Listar tipos de trámites
POST   /api/tipo-tramites              # Crear tipo de trámite
GET    /api/tipo-tramites/{id}         # Obtener tipo específico
PUT    /api/tipo-tramites/{id}         # Actualizar tipo
DELETE /api/tipo-tramites/{id}         # Eliminar tipo

# Gerencias
GET    /api/gerencias                  # Listar gerencias (jerárquico)
POST   /api/gerencias                  # Crear gerencia
GET    /api/gerencias/{id}             # Obtener gerencia específica
PUT    /api/gerencias/{id}             # Actualizar gerencia
DELETE /api/gerencias/{id}             # Eliminar gerencia
GET    /api/gerencias/tree             # Árbol jerárquico completo
```

### **Endpoints Públicos (Sin Autenticación)**
```bash
# Información Pública
GET    /api/public/tipos-tramites      # Tipos de trámites públicos
GET    /api/public/gerencias           # Lista de gerencias
GET    /api/public/requisitos/{tipoId} # Requisitos por tipo de trámite
GET    /api/status                     # Estado del sistema
GET    /api/health                     # Health check
```

### **Códigos de Respuesta HTTP**
```
200 OK                    # Operación exitosa
201 Created              # Recurso creado exitosamente
204 No Content           # Operación exitosa sin contenido
400 Bad Request          # Error en la solicitud
401 Unauthorized         # No autenticado
403 Forbidden            # Sin permisos suficientes
404 Not Found            # Recurso no encontrado
422 Unprocessable Entity # Errores de validación
429 Too Many Requests    # Rate limit excedido
500 Internal Server Error # Error interno del servidor
```

### **Middleware y Autenticación por Ruta**
```php
// Rutas públicas (sin autenticación)
/api/public/*

// Rutas autenticadas (Sanctum token requerido)
/api/* (excepto /api/public/*)

// Rutas con roles específicos
/api/admin/*           # Requiere rol 'admin'
/api/usuarios/*        # Requiere permiso 'gestionar_usuarios'
/api/reportes/*        # Requiere permiso 'ver_reportes'

// Rate Limiting
/api/auth/login        # 5 intentos por minuto
/api/*                # 60 requests por minuto por usuario
```

---

## 📚 Documentación Técnica Adicional

### **Variables de Entorno Críticas**
```env
# Base de Datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tramite_municipal
DB_USERNAME=root
DB_PASSWORD=

# Sanctum (Autenticación)
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,::1
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Storage y Archivos
FILESYSTEM_DISK=local
MAX_FILE_SIZE=10240    # 10MB en KB
ALLOWED_EXTENSIONS=pdf,doc,docx,jpg,jpeg,png

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls

# Cache y Queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Logging y Debugging
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
APP_DEBUG=true   # Solo en desarrollo
```

### **Comandos Artisan Personalizados**
```bash
# Instalación y Configuración
php artisan tramite:install          # Instalación inicial completa
php artisan tramite:seed-permissions # Crear permisos base
php artisan tramite:create-admin     # Crear usuario administrador

# Mantenimiento de Datos
php artisan tramite:clean-files      # Limpiar archivos huérfanos
php artisan tramite:backup-db        # Backup de base de datos
php artisan tramite:generate-reports # Generar reportes automáticos

# Desarrollo y Testing
php artisan tramite:test-emails      # Probar configuración de email
php artisan tramite:check-permissions # Verificar integridad de permisos
```

---

## 🔧 Instalación y Configuración

### **Requisitos del Sistema**
```bash
PHP >= 8.1 (Recomendado 8.2+)
Composer >= 2.4
MySQL >= 8.0 / PostgreSQL >= 13
Redis >= 6.0 (opcional para cache)
Node.js >= 16 (para assets frontend)
```

### **Instalación Paso a Paso**
```bash
# 1. Clonar repositorio
git clone <repositorio-url>
cd backend-muni

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Configurar base de datos
php artisan migrate
php artisan db:seed

# 5. Instalar sistema completo
php artisan tramite:install

# 6. Crear usuario administrador
php artisan tramite:create-admin

# 7. Compilar assets (si incluye frontend)
npm run build

# 8. Configurar permisos de storage
php artisan storage:link
chmod -R 755 storage bootstrap/cache
```

### **Configuración de Producción**
```bash
# 1. Optimizaciones
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Queue Workers (Supervisor)
sudo supervisorctl start laravel-worker:*

# 3. Cron Jobs (Crontab)
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

# 4. Web Server (Nginx)
server {
    listen 80;
    server_name tu-dominio.com;
    root /path-to-project/public;
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }
}
```

---

## ✅ Testing y Calidad

### **Test Suite Completo**
```bash
# Ejecutar todos los tests
php artisan test

# Tests específicos
php artisan test --filter=AuthenticationTest
php artisan test --filter=ExpedienteTest
php artisan test --filter=PermissionTest

# Coverage report
php artisan test --coverage
```

### **Herramientas de Calidad**
```bash
# PHP CS Fixer (Estilo de código)
./vendor/bin/php-cs-fixer fix

# PHPStan (Análisis estático)
./vendor/bin/phpstan analyse

# Larastan (Laravel-specific)
php artisan code:analyse
```

---

**🎯 Total Backend: 9 CRUDs completos, 85+ endpoints, autenticación dual, 59+ permisos granulares, workflows personalizables y arquitectura escalable lista para producción.**

```http```

GET /api/gerencias

```### 📝 **Mesa de Partes** (`/api/mesa-partes/*`)

```http

---GET    /api/mesa-partes                   # Listar documentos

POST   /api/mesa-partes                   # Crear documento

## 🔐 Autenticación Bearer TokenGET    /api/mesa-partes/{id}              # Obtener documento

PUT    /api/mesa-partes/{id}              # Actualizar documento

Todas las rutas protegidas requieren el token en el header:POST   /api/mesa-partes/{id}/derivar      # Derivar documento

POST   /api/mesa-partes/{id}/observar     # Agregar observación

```httpGET    /api/mesa-partes/tipos/tramites    # Tipos de trámite

Authorization: Bearer 1|abcd1234efgh5678ijkl9012mnop3456GET    /api/mesa-partes/tipos/documentos  # Tipos de documento

```GET    /api/mesa-partes/reportes/estadisticas # Estadísticas

```

---

---

## 👥 Usuarios de Prueba

## 🗄️ Base de Datos Completa

| Email | Contraseña | Rol | Gerencia |

|-------|------------|-----|----------|### 📊 **Tablas Principales**

| admin@municipalidad.gob.pe | admin123 | super_admin | Alcaldía |- `users` - Usuarios del sistema con roles y permisos

| gerente.desarrollo@municipalidad.gob.pe | password123 | jefe_gerencia | Desarrollo Económico |- `expedientes` - Expedientes municipales con workflows

| funcionario.licencias@municipalidad.gob.pe | password123 | funcionario | Desarrollo Económico |- `workflows` ⭐ - Workflows personalizables

| ciudadano@email.com | password123 | ciudadano | N/A |- `workflow_steps` ⭐ - Pasos de workflow

- `workflow_transitions` ⭐ - Transiciones de workflow

---- `gerencias` - Estructura jerárquica de gerencias

- `mesa_partes` - Documentos de entrada y seguimiento

## 🚦 Códigos de Estado- `roles` / `permissions` - Sistema de permisos granular



- `200` - OK### 🔗 **Relaciones Clave**

- `201` - Creado exitosamente- Users ↔ Roles/Permissions (Many-to-Many)

- `400` - Error de validación- Users ↔ Gerencias (Many-to-Many)

- `401` - No autorizado- Expedientes → CustomWorkflows (Utiliza workflow)

- `403` - Prohibido (sin permisos)- CustomWorkflows → CustomWorkflowSteps (Tiene pasos)

- `404` - No encontrado- CustomWorkflowSteps → CustomWorkflowTransitions (Conecta pasos)

- `422` - Error de validación de datos- Gerencias → Gerencias (Padre-Hijo para jerarquía)

- `500` - Error del servidor

---

---

## 🔑 Sistema de Permisos - 59 Permisos Granulares

## 📝 Notas Importantes

### **Permisos de Expedientes** (13 permisos)

1. **Permisos:** Muchos endpoints requieren permisos específicos- `ver_expedientes`, `crear_expedientes`, `editar_expedientes`

2. **Paginación:** Los listados soportan parámetros `page` y `per_page`- `eliminar_expedientes`, `derivar_expediente`, `aprobar_expediente`

3. **Filtros:** Usar parámetros de query para filtrar resultados- `rechazar_expediente`, `finalizar_expediente`, `archivar_expediente`

4. **Archivos:** Usar `multipart/form-data` para subir archivos- `subir_documento`, `eliminar_documento`, `ver_expedientes_todos`

5. **Códigos de Seguimiento:** Se generan automáticamente para mesa de partes

### **Permisos de Usuarios** (11 permisos)

---- `gestionar_usuarios`, `crear_usuarios`, `editar_usuarios`

- `eliminar_usuarios`, `asignar_roles`, `gestionar_permisos`

## 🔍 Ejemplos de Filtros- `ver_usuarios_todos`, `cambiar_contraseña`, `ver_logs`



```http### **Permisos de Gerencias** (8 permisos)

GET /api/expedientes?estado=pendiente&gerencia_id=2&page=1&per_page=10- `gestionar_gerencias`, `crear_gerencias`, `editar_gerencias`

GET /api/mesa-partes?fecha_inicio=2025-01-01&fecha_fin=2025-12-31- `eliminar_gerencias`, `asignar_usuarios_gerencia`, `ver_estadisticas_gerencia`

GET /api/usuarios?role=funcionario&gerencia_id=3

```### **Permisos de Workflows** ⭐ (7 permisos)

- `gestionar_workflows`, `crear_workflows`, `editar_workflows`

---- `eliminar_workflows`, `ver_workflows`, `activar_workflows`, `clonar_workflows`



**🎯 Sistema listo para pruebas con Postman!**### **Permisos de Mesa de Partes** (6 permisos)
- `ver_mesa_partes`, `crear_mesa_partes`, `editar_mesa_partes`
- `derivar_mesa_partes`, `observar_mesa_partes`, `ver_estadisticas_mesa_partes`

### **Permisos Adicionales** (14 permisos)
- Reportes, estadísticas, configuración, notificaciones, pagos, quejas, flujos

---

## 📊 Estado de Implementación

### ✅ **Backend Completo (100%)**
- ✅ Sistema de autenticación con Sanctum
- ✅ Gestión completa de usuarios, roles y permisos (59 permisos)
- ✅ Arquitectura de gerencias jerárquica implementada
- ✅ Sistema de expedientes con derivaciones completo
- ✅ Mesa de partes operativa
- ✅ **Workflows personalizables completos** ⭐
- ✅ API RESTful completa (40+ endpoints)
- ✅ Base de datos optimizada con 15+ tablas
- ✅ Validaciones y middleware de seguridad
- ✅ Seeders completos con datos de prueba

### 🚧 **Próximas Fases**
- 🔄 Interfaz web para gestión visual de workflows
- 🔄 Dashboard administrativo completo
- 🔄 Motor de ejecución automática de workflows
- 🔄 Sistema de notificaciones por email
- 🔄 Reportes avanzados con gráficos

---

## 📞 Soporte

- 📧 Email: soporte@municipalidad.com
- 📱 WhatsApp: +51 999 999 999
- 📋 Issues: [GitHub Issues]

---

**Versión**: 2.0.0  
**Estado**: Backend Completo ✅  
**Workflows**: Implementados ⭐  
**Última actualización**: Septiembre 2025

## 📦 Instalación Rápida

```bash
# 1. Instalar dependencias
composer install

# 2. Configurar environment
cp .env.example .env
php artisan key:generate

# 3. Configurar base de datos en .env
DB_CONNECTION=mysql
DB_DATABASE=tramite_muni
DB_USERNAME=usuario
DB_PASSWORD=password

# 4. Ejecutar migraciones y seeders
php artisan migrate
php artisan db:seed

# 5. Crear enlace simbólico para storage
php artisan storage:link

# 6. Iniciar servidor
php artisan serve
```

## 🌐 URLs de Prueba

### **Interfaz Administrativa**
```
http://localhost:8000/test_admin.html
```

### **API de Expedientes**
```
http://localhost:8000/test_api.html
```

### **Mesa de Partes**
```
http://localhost:8000/test_mesa_partes_api.html
```

## 👤 Usuarios de Prueba

| Rol | Email | Password | Permisos |
|-----|-------|----------|----------|
| Super Admin | `superadmin@example.com` | `password` | Todos |
| Admin | `admin@example.com` | `password` | Gestión completa |
| Jefe de Gerencia | `jefe@example.com` | `password` | Gestión de gerencia |
| Funcionario | `funcionario@example.com` | `password` | Procesamiento básico |
| Ciudadano | `ciudadano@example.com` | `password` | Creación y consulta |

## 📱 API Endpoints Principales

### 🔐 **Autenticación**
```http
POST /api/auth/login
POST /api/auth/register
POST /api/auth/logout
GET  /api/auth/user
```

### 📋 **Expedientes**
```http
GET    /api/expedientes
POST   /api/expedientes
GET    /api/expedientes/{id}
PUT    /api/expedientes/{id}
DELETE /api/expedientes/{id}
```

### 🔄 **Workflows Personalizables** ⭐
```http
GET    /api/custom-workflows
POST   /api/custom-workflows
GET    /api/custom-workflows/{id}
PUT    /api/custom-workflows/{id}
DELETE /api/custom-workflows/{id}
POST   /api/custom-workflows/{id}/clone
```

### 🏢 **Gerencias**
```http
GET    /api/gerencias
POST   /api/gerencias
GET    /api/gerencias/{id}
PUT    /api/gerencias/{id}
DELETE /api/gerencias/{id}
```

### 📝 **Mesa de Partes**
```http
GET    /api/mesa-partes
POST   /api/mesa-partes
GET    /api/mesa-partes/{id}
PUT    /api/mesa-partes/{id}
```

## 🗄️ Base de Datos

### 📊 **Tablas Principales**
- `users` - Usuarios del sistema
- `expedientes` - Expedientes municipales
- `workflows` ⭐ - Workflows personalizables
- `workflow_steps` ⭐ - Pasos de workflow
- `workflow_transitions` ⭐ - Transiciones de workflow
- `gerencias` - Estructura de gerencias
- `mesa_partes` - Documentos de entrada
- `roles` / `permissions` - Sistema de permisos

### 🔗 **Relaciones Clave**
- Users ↔ Roles/Permissions (Many-to-Many)
- Expedientes → CustomWorkflows (Utiliza workflow)
- CustomWorkflows → CustomWorkflowSteps (Tiene pasos)
- CustomWorkflowSteps → CustomWorkflowTransitions (Conecta pasos)

## 📊 Estado de Implementación

### ✅ **Backend Completo (100%)**
- ✅ Sistema de autenticación con Sanctum
- ✅ Gestión completa de usuarios, roles y permisos (59 permisos)
- ✅ Arquitectura de gerencias implementada
- ✅ Sistema de expedientes funcional
- ✅ Mesa de partes operativa
- ✅ **Workflows personalizables completos** ⭐
- ✅ API RESTful completa (25+ endpoints)
- ✅ Base de datos optimizada
- ✅ Validaciones y middleware
- ✅ Seeders y migraciones completos

### 🚧 **Próximas Fases**
- 🔄 Interfaz web para gestión de workflows
- 🔄 Dashboard administrativo completo
- 🔄 Motor de ejecución automática de workflows
- 🔄 Sistema de notificaciones
- 🔄 Reportes avanzados con gráficos

## 📚 Documentación Completa

Para documentación detallada, ver:
- **[DOCUMENTACION_COMPLETA.md](DOCUMENTACION_COMPLETA.md)** - Documentación técnica completa
- **[DOCUMENTACION_FINAL.md](DOCUMENTACION_FINAL.md)** - Guía de implementación

## 📞 Soporte

- 📧 Email: soporte@municipalidad.com
- 📱 WhatsApp: +51 999 999 999
- 📋 Issues: [GitHub Issues]

---

**Versión**: 2.0.0
**Estado**: Backend Completo ✅
**Última actualización**: Septiembre 2025
```

4. **Configurar base de datos**
```bash
# Editar .env con tus credenciales de BD
php artisan migrate --seed
```

5. **Generar datos de prueba**
```bash
php artisan db:seed --class=GerenciaSeeder
php artisan db:seed --class=RolePermissionSeeder
```

6. **Iniciar servidor**
```bash
php artisan serve
```

## 🏗️ Arquitectura del Sistema

### Modelos Principales
- **User**: Usuarios del sistema con roles específicos
- **Gerencia**: Estructura organizacional unificada
- **Expediente**: Trámites y solicitudes ciudadanas
- **DocumentoExpediente**: Archivos adjuntos
- **HistorialExpediente**: Auditoría de cambios

### Roles del Sistema
- **Mesa de Partes**: Registro y derivación inicial
- **Gerente Urbano**: Revisión técnica especializada
- **Inspector**: Inspecciones de campo
- **Secretaria General**: Revisión legal y resoluciones
- **Alcalde**: Firma de actos administrativos mayores
- **Admin**: Gestión completa del sistema

## 🔄 Flujo de Trabajo

### 1. Ciudadano
- Registra solicitud de trámite
- Sube documentos requeridos
- Recibe número de expediente

### 2. Mesa de Partes
- Valida requisitos mínimos
- Deriva a gerencia correspondiente
- Puede rechazar si no cumple requisitos

### 3. Gerencia/Subgerencia
- Realiza revisión técnica
- Ejecuta inspecciones (si aplica)
- Determina si requiere revisión legal

### 4. Secretaría General
- Revisión legal cuando es requerida
- Emite resoluciones
- Determina si requiere firma alcalde

### 5. Alcalde
- Firma actos administrativos mayores
- Resoluciones de alto impacto

## 📚 Documentación

**📖 [DOCUMENTACIÓN COMPLETA](./DOCUMENTACION_COMPLETA.md)** - Toda la información del sistema en un solo lugar

La documentación completa incluye:
- ✅ **Instalación y configuración** paso a paso
- ✅ **Arquitectura del sistema** completa
- ✅ **API RESTful** con todos los endpoints
- ✅ **Sistema de permisos** detallado
- ✅ **Instrucciones de prueba** exhaustivas
- ✅ **Estado de implementación** actual

### 🎯 Enlaces Rápidos
- **Archivos de prueba**: `test_api.html` y `test_mesa_partes_api.html`
- **Backend**: `http://localhost:8000`
- **API**: `http://localhost:8000/api`

## 🧪 Testing

```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar pruebas específicas
php artisan test --filter ExpedienteTest
```

## 📱 Endpoints Principales

```
GET    /api/expedientes          # Listar expedientes
POST   /api/expedientes          # Crear expediente
GET    /api/expedientes/{id}     # Ver expediente
PUT    /api/expedientes/{id}     # Actualizar expediente
POST   /api/expedientes/{id}/derivar   # Derivar expediente
POST   /api/expedientes/{id}/documents # Subir documentos
```

## 🔧 Configuración

### Variables de Entorno Importantes
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SANCTUM_STATEFUL_DOMAINS=localhost:4200
SESSION_DOMAIN=localhost
```

### Estados de Expedientes
- `pendiente`: Recién creado
- `en_revision`: En proceso de revisión
- `revision_tecnica`: Revisión técnica en curso
- `revision_legal`: Revisión legal requerida
- `resolucion_emitida`: Resolución emitida
- `firmado`: Firmado por autoridad
- `notificado`: Notificado al ciudadano
- `completado`: Proceso terminado
- `rechazado`: Rechazado por no cumplir requisitos

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👥 Equipo de Desarrollo

Desarrollado para la gestión municipal de trámites documentarios.

---

### 🚀 Enlaces Rápidos

- **Backend API**: `http://localhost:8000/api`
- **Documentación**: Ver archivos .md en el directorio raíz
- **Pruebas**: `test_api.html` y `test_mesa_partes_api.html`
