# 📋 ANÁLISIS COMPLETO: Lógica de Asignación de Gerencias y Workflow

## 🎯 RESUMEN EJECUTIVO

El sistema tiene **DOS FLUJOS PRINCIPALES** de asignación:

1. **Asignación INICIAL** → Cuando se crea el expediente (basado en TipoTramite)
2. **Asignación en WORKFLOW** → Cuando el expediente avanza entre pasos (basado en WorkflowStep)

---

## 📊 FLUJO 1: CREACIÓN DEL EXPEDIENTE (Asignación Inicial)

### Ubicación del Código
- **Controlador Ciudadano**: `app/Http/Controllers/CiudadanoTramiteController.php` (línea ~110)
- **Controlador Expediente**: `app/Http/Controllers/ExpedienteController.php` (línea ~206)

### ¿Cómo funciona?

```php
// PASO 1: El ciudadano selecciona un TIPO DE TRÁMITE
$tipoTramite = TipoTramite::find($request->tipo_tramite_id);

// PASO 2: Se crea el expediente con la GERENCIA del tipo de trámite
$expediente = Expediente::create([
    'numero' => $numeroExpediente,
    'tipo_tramite_id' => $tipoTramite->id,
    'gerencia_id' => $tipoTramite->gerencia_id,  // ← AQUÍ SE ASIGNA LA GERENCIA
    'workflow_id' => $workflow?->id,
    'current_step_id' => $primerPaso?->id,
    // ... otros campos
]);
```

### 🔍 Tabla Clave: `tipo_tramites`

```sql
CREATE TABLE tipo_tramites (
    id BIGINT PRIMARY KEY,
    nombre VARCHAR(255),
    codigo VARCHAR(50),
    gerencia_id BIGINT,          -- ← Define qué gerencia maneja este trámite
    workflow_id BIGINT,           -- ← Workflow asociado (opcional)
    documentos_requeridos JSON,
    costo DECIMAL,
    tiempo_estimado_dias INT,
    requiere_pago BOOLEAN,
    activo BOOLEAN
);
```

### Ejemplo Práctico

```
Tipo de Trámite: "Licencia de Funcionamiento"
├── gerencia_id: 3 (Gerencia de Desarrollo Económico)
├── workflow_id: 70 (Flujo para Licencia de Funcionamiento)
└── Cuando un ciudadano crea este trámite:
    └── El expediente.gerencia_id = 3
```

### 📝 Código Completo (CiudadanoTramiteController)

**Líneas 100-125:**
```php
// Obtener el tipo de trámite seleccionado
$tipoTramite = TipoTramite::findOrFail($validated['tipo_tramite_id']);

// Buscar workflow asociado
$workflow = null;
$primerPaso = null;

if ($tipoTramite->workflow_id) {
    $workflow = Workflow::with('steps')->find($tipoTramite->workflow_id);
    
    if ($workflow) {
        // Buscar primer paso (tipo 'inicio')
        $primerPaso = $workflow->steps()
            ->where('tipo', 'inicio')
            ->orderBy('orden')
            ->first();
        
        if (!$primerPaso) {
            $primerPaso = $workflow->steps()->orderBy('orden')->first();
        }
    }
}

// CREAR EXPEDIENTE con gerencia del tipo de trámite
$expediente = Expediente::create([
    'numero' => $numeroExpediente,
    'tipo_tramite_id' => $tipoTramite->id,
    'workflow_id' => $workflow?->id,
    'current_step_id' => $primerPaso?->id,
    'gerencia_id' => $tipoTramite->gerencia_id,  // ← ASIGNACIÓN INICIAL
    'estado' => Expediente::ESTADO_PENDIENTE,
    'fecha_registro' => now(),
    // ... otros campos
]);
```

---

## 📊 FLUJO 2: AVANCE EN EL WORKFLOW (Asignación por Pasos)

### Ubicación del Código
- **Modelo**: `app/Models/Expediente.php` método `activarSiguienteEtapa()` (línea ~373)
- **Controlador**: `app/Http/Controllers/GerenciaTramiteController.php` (aprobar/rechazar)

### ¿Cómo funciona?

```php
// PASO 1: Se aprueba un paso actual
$etapaActual->aprobar($comentarios);

// PASO 2: Se busca la siguiente etapa
$siguienteEtapa = WorkflowStep::where('workflow_id', $this->workflow_id)
    ->where('orden', '>', $etapaActual->workflowStep->orden)
    ->orderBy('orden', 'asc')
    ->first();

// PASO 3: Se activa la siguiente etapa con SU RESPONSABLE
$this->activarSiguienteEtapa($siguienteEtapa);
```

### 🔍 Tabla Clave: `workflow_steps`

```sql
CREATE TABLE workflow_steps (
    id BIGINT PRIMARY KEY,
    workflow_id BIGINT,
    nombre VARCHAR(255),
    descripcion TEXT,
    orden INT,
    tipo ENUM('inicio', 'proceso', 'decision', 'fin'),
    responsable_tipo ENUM('usuario', 'gerencia', 'rol'),  -- ← Tipo de responsable
    responsable_id BIGINT,                                 -- ← ID del responsable
    tiempo_estimado INT,
    es_requerido BOOLEAN,
    permite_rechazar BOOLEAN
);
```

### 📋 Tipos de Responsables

El campo `responsable_tipo` define QUIÉN es responsable de cada paso:

#### 1️⃣ **'usuario'** - Usuario Específico
```php
responsable_tipo = 'usuario'
responsable_id = 5  // ID del usuario (ej: María González)
```
**Comportamiento**: Asigna directamente al usuario con ese ID

#### 2️⃣ **'gerencia'** - Gerencia/Subgerencia
```php
responsable_tipo = 'gerencia'
responsable_id = 8  // ID de la gerencia (ej: Subgerencia de Promoción Empresarial)
```
**Comportamiento**: Busca un usuario con rol alto en esa gerencia

#### 3️⃣ **'rol'** - Rol del Sistema
```php
responsable_tipo = 'rol'
responsable_id = 'subgerente'  // Nombre del rol
```
**Comportamiento**: Busca un usuario con ese rol en la gerencia del expediente

### 📝 Código Completo (Expediente::activarSiguienteEtapa)

**Líneas 373-470:**
```php
public function activarSiguienteEtapa(WorkflowStep $siguienteEtapa)
{
    \Log::info('🔄 Activando siguiente etapa', [
        'expediente' => $this->numero,
        'paso_siguiente' => $siguienteEtapa->nombre,
        'paso_siguiente_id' => $siguienteEtapa->id,
        'responsable_tipo' => $siguienteEtapa->responsable_tipo,
        'responsable_id' => $siguienteEtapa->responsable_id
    ]);
    
    // Actualizar el current_step_id del expediente
    $this->current_step_id = $siguienteEtapa->id;
    $this->save();

    // Determinar quién será el responsable según el TIPO
    $responsableId = null;
    
    // OPCIÓN 1: Usuario específico
    if ($siguienteEtapa->responsable_tipo === 'usuario' && $siguienteEtapa->responsable_id) {
        $responsableId = $siguienteEtapa->responsable_id;
        \Log::info('✅ Responsable es usuario', ['usuario_id' => $responsableId]);
    } 
    
    // OPCIÓN 2: Gerencia (buscar jefe/gerente/subgerente en esa gerencia)
    elseif ($siguienteEtapa->responsable_tipo === 'gerencia' && $siguienteEtapa->responsable_id) {
        $usuario = User::where('gerencia_id', $siguienteEtapa->responsable_id)
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['jefe_gerencia', 'subgerente', 'gerente']);
            })
            ->first();
        
        if ($usuario) {
            $responsableId = $usuario->id;
            \Log::info('✅ Responsable encontrado en gerencia', [
                'gerencia_id' => $siguienteEtapa->responsable_id,
                'usuario' => $usuario->name,
                'usuario_id' => $responsableId,
                'rol' => $usuario->roles->pluck('name')->toArray()
            ]);
        } else {
            \Log::warning('⚠️ No se encontró usuario responsable en gerencia', [
                'gerencia_id' => $siguienteEtapa->responsable_id
            ]);
        }
    } 
    
    // OPCIÓN 3: Rol (buscar usuario con ese rol en la gerencia actual del expediente)
    elseif ($siguienteEtapa->responsable_tipo === 'rol') {
        $usuario = User::whereHas('roles', function($query) use ($siguienteEtapa) {
                $query->where('name', $siguienteEtapa->responsable_id);
            })
            ->where('gerencia_id', $this->gerencia_id)
            ->first();
        
        if ($usuario) {
            $responsableId = $usuario->id;
            \Log::info('✅ Responsable encontrado por rol', [
                'rol' => $siguienteEtapa->responsable_id,
                'usuario' => $usuario->name,
                'usuario_id' => $responsableId
            ]);
        }
    }

    // CREAR o ACTUALIZAR el progreso del workflow
    $progreso = $this->workflowProgress()
        ->where('workflow_step_id', $siguienteEtapa->id)
        ->first();

    if ($progreso) {
        // Ya existe, solo actualizarlo
        $progreso->update([
            'estado' => ExpedienteWorkflowProgress::ESTADO_EN_PROCESO,
            'fecha_inicio' => now(),
            'asignado_a' => $responsableId,  // ← ASIGNACIÓN DEL RESPONSABLE
        ]);
    } else {
        // No existe, crearlo
        $progreso = ExpedienteWorkflowProgress::create([
            'expediente_id' => $this->id,
            'workflow_step_id' => $siguienteEtapa->id,
            'estado' => ExpedienteWorkflowProgress::ESTADO_EN_PROCESO,
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays($siguienteEtapa->tiempo_estimado ?? 30),
            'asignado_a' => $responsableId,  // ← ASIGNACIÓN DEL RESPONSABLE
        ]);
    }
    
    \Log::info('✅ Siguiente etapa activada exitosamente');
}
```

---

## 🔍 TABLA CLAVE: `expediente_workflow_progress`

Esta tabla rastrea **qué usuario está asignado en cada paso**:

```sql
CREATE TABLE expediente_workflow_progress (
    id BIGINT PRIMARY KEY,
    expediente_id BIGINT,
    workflow_step_id BIGINT,
    estado ENUM('pendiente', 'en_proceso', 'aprobado', 'rechazado'),
    asignado_a BIGINT,           -- ← ID del usuario responsable de este paso
    fecha_inicio DATETIME,
    fecha_completado DATETIME,
    fecha_limite DATETIME,
    comentarios TEXT,
    decision VARCHAR(50)
);
```

### Ejemplo Real (Tu Workflow):

```
Workflow: "Flujo para Licencia de Funcionamiento" (ID: 70)

Paso 1: Gerencia de Desarrollo Económico
├── responsable_tipo: 'usuario'
├── responsable_id: 5 (María González)
└── expediente_workflow_progress
    ├── expediente_id: 26
    ├── workflow_step_id: 1
    ├── asignado_a: 5 (María)
    └── estado: 'aprobado' ✅

Paso 2: Subgerencia de Promoción Empresarial
├── responsable_tipo: 'gerencia'
├── responsable_id: 8 (ID de la subgerencia)
└── expediente_workflow_progress
    ├── expediente_id: 26
    ├── workflow_step_id: 2
    ├── asignado_a: 11 (Laura Ruiz - encontrada automáticamente)
    └── estado: 'aprobado' ✅

Paso 3: Subgerencia de Comercio, Industria y Turismo
├── responsable_tipo: 'gerencia'
├── responsable_id: 9 (ID de la subgerencia)
└── expediente_workflow_progress
    ├── expediente_id: 26
    ├── workflow_step_id: 3
    ├── asignado_a: 12 (Ricardo Morales - encontrado automáticamente)
    └── estado: 'en_proceso' 🔄 (ACTUAL)
```

---

## 📍 ¿CÓMO VE CADA USUARIO SUS EXPEDIENTES?

### Vista "Mis Asignados" (GerenciaTramiteController)

**Antes (INCORRECTO - línea ~40):**
```php
public function misAsignados()
{
    $gerencia = auth()->user()->gerencia;
    
    $expedientes = Expediente::with(['tipoTramite', 'workflow'])
        ->where('gerencia_id', $gerencia->id)  // ❌ PROBLEMA: Filtra por gerencia del expediente
        ->where('estado', '!=', Expediente::ESTADO_FINALIZADO)
        ->get();
}
```
**Problema**: Mostraba todos los expedientes de esa gerencia, no los ASIGNADOS al usuario actual.

**Ahora (CORRECTO - FIJADO):**
```php
public function misAsignados()
{
    $expedientes = Expediente::with(['tipoTramite', 'workflow', 'workflowProgress'])
        ->whereHas('workflowProgress', function($query) {
            $query->where('asignado_a', auth()->id())  // ✅ Filtra por usuario asignado
                  ->where('estado', ExpedienteWorkflowProgress::ESTADO_EN_PROCESO);
        })
        ->where('estado', '!=', Expediente::ESTADO_FINALIZADO)
        ->get();
}
```

**Ahora sí**: Laura solo ve expedientes donde `expediente_workflow_progress.asignado_a = 11` (su ID)

---

## 🎯 DIAGRAMA DE FLUJO COMPLETO

```
┌─────────────────────────────────────────────────────────────────┐
│ 1. CIUDADANO CREA TRÁMITE                                       │
└─────────────────────────────────────────────────────────────────┘
                            │
                            ▼
           ┌────────────────────────────────┐
           │ Selecciona Tipo de Trámite     │
           │ "Licencia de Funcionamiento"   │
           └────────────────────────────────┘
                            │
                            ▼
           ┌────────────────────────────────┐
           │ Sistema busca en tipo_tramites │
           │ gerencia_id = 3                │
           │ workflow_id = 70               │
           └────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│ 2. CREAR EXPEDIENTE                                             │
│    expediente.gerencia_id = 3 (Gerencia padre inicial)         │
│    expediente.workflow_id = 70                                  │
│    expediente.current_step_id = 1 (Primer paso)                │
└─────────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│ 3. CREAR PROGRESO PASO 1                                        │
│    workflow_step_id = 1 (Gerencia Desarrollo Económico)        │
│    asignado_a = 5 (María González - del workflow_step)         │
│    estado = 'pendiente'                                         │
└─────────────────────────────────────────────────────────────────┘
                            │
                            ▼
        ┌───────────────────────────────────────┐
        │ María ve expediente en "Mis Asignados"│
        │ (workflowProgress.asignado_a = 5)     │
        └───────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│ 4. MARÍA APRUEBA                                                │
│    - Actualiza progreso paso 1: estado = 'aprobado'            │
│    - Busca siguiente paso: paso 2                              │
│    - Llama: activarSiguienteEtapa(paso 2)                      │
└─────────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│ 5. ACTIVAR PASO 2 (Subgerencia Promoción)                      │
│    - responsable_tipo = 'gerencia'                             │
│    - responsable_id = 8 (ID de Subgerencia Promoción)         │
│    - Sistema busca: User con gerencia_id=8 y rol alto          │
│    - Encuentra: Laura Ruiz (ID: 11)                            │
│    - Actualiza expediente.current_step_id = 2                  │
└─────────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│ 6. CREAR/ACTUALIZAR PROGRESO PASO 2                            │
│    workflow_step_id = 2                                         │
│    asignado_a = 11 (Laura Ruiz)                                │
│    estado = 'en_proceso'                                        │
└─────────────────────────────────────────────────────────────────┘
                            │
                            ▼
        ┌───────────────────────────────────────┐
        │ Laura ve expediente en "Mis Asignados"│
        │ (workflowProgress.asignado_a = 11)    │
        └───────────────────────────────────────┘
                            │
                            ▼
                       (Proceso continúa...)
```

---

## 🐛 PROBLEMAS QUE HABÍAN (Y SE ARREGLARON)

### ❌ Problema 1: Workflow sin responsables
**Causa**: `WorkflowController::store()` no guardaba `responsable_tipo` ni `responsable_id`

**Solución**:
```php
// ANTES
$step = $workflow->steps()->create([
    'nombre' => $stepData['nombre'],
    'orden' => $stepData['orden'],
    // ❌ Faltaban estos campos
]);

// DESPUÉS
$step = $workflow->steps()->create([
    'nombre' => $stepData['nombre'],
    'orden' => $stepData['orden'],
    'responsable_tipo' => 'gerencia',  // ✅
    'responsable_id' => $stepData['gerencia_id'],  // ✅
]);
```

### ❌ Problema 2: Vista mostraba por gerencia_id en lugar de asignado_a
**Causa**: `GerenciaTramiteController::misAsignados()` filtraba por `gerencia_id`

**Solución**:
```php
// ANTES
->where('gerencia_id', $gerencia->id)  // ❌

// DESPUÉS
->whereHas('workflowProgress', function($query) {
    $query->where('asignado_a', auth()->id())  // ✅
          ->where('estado', ExpedienteWorkflowProgress::ESTADO_EN_PROCESO);
})
```

### ❌ Problema 3: No se buscaba usuario al activar siguiente etapa
**Causa**: No había lógica para encontrar usuario según `responsable_tipo='gerencia'`

**Solución**: Se agregó el bloque que busca usuarios en la gerencia con roles altos

---

## 📊 RESUMEN VISUAL: CAMPOS CLAVE

```
┌─────────────────────────────────────────────────────────────────┐
│ TABLA: tipo_tramites                                            │
├─────────────────────────────────────────────────────────────────┤
│ gerencia_id → Define gerencia INICIAL del expediente           │
│ workflow_id → Define workflow a usar                            │
└─────────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────────┐
│ TABLA: expedientes                                              │
├─────────────────────────────────────────────────────────────────┤
│ gerencia_id → Gerencia "dueña" (del tipo_tramite)              │
│ workflow_id → Workflow activo                                   │
│ current_step_id → Paso actual en el workflow                    │
└─────────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────────┐
│ TABLA: workflow_steps (Pasos del workflow)                     │
├─────────────────────────────────────────────────────────────────┤
│ responsable_tipo → 'usuario' | 'gerencia' | 'rol'              │
│ responsable_id → ID según el tipo                              │
│ orden → Secuencia de pasos                                      │
└─────────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────────┐
│ TABLA: expediente_workflow_progress (Rastrea asignaciones)     │
├─────────────────────────────────────────────────────────────────┤
│ asignado_a → Usuario responsable del PASO ACTUAL               │
│ estado → pendiente | en_proceso | aprobado | rechazado         │
│ workflow_step_id → Paso al que pertenece                        │
└─────────────────────────────────────────────────────────────────┘
```

---

## ✅ CONCLUSIÓN

**La gerencia del expediente NO cambia** durante el workflow. El campo `expedientes.gerencia_id` siempre mantiene la gerencia inicial del `tipo_tramite`.

**Lo que SÍ cambia** es el usuario asignado en cada paso, almacenado en `expediente_workflow_progress.asignado_a`.

**La vista "Mis Asignados"** debe filtrar por:
```sql
WHERE expediente_workflow_progress.asignado_a = current_user_id
  AND expediente_workflow_progress.estado = 'en_proceso'
```

**NO por**:
```sql
WHERE expedientes.gerencia_id = current_user_gerencia_id  -- ❌ INCORRECTO
```

---

## 🔧 ARCHIVOS MODIFICADOS (Ya arreglados)

1. ✅ `app/Http/Controllers/WorkflowController.php` - Guarda responsables
2. ✅ `app/Models/Expediente.php` - Busca usuarios según responsable_tipo
3. ✅ `app/Http/Controllers/GerenciaTramiteController.php` - Filtra por asignado_a

---

🎉 **Sistema funcionando correctamente ahora**
