# 🔄 Flujo de Aprobación Automática del Workflow

## 📅 Fecha: 14 de Octubre, 2025

---

## ✅ ¿Cómo funciona el flujo automático?

Cuando un **subgerente aprueba un paso** del workflow, el sistema automáticamente:

1. ✅ **Marca el paso actual como "aprobado"**
2. ✅ **Busca el siguiente paso** en el workflow (por orden)
3. ✅ **Activa automáticamente el siguiente paso**
4. ✅ **Actualiza el `current_step_id`** del expediente
5. ✅ **Crea/inicia el progreso** para el siguiente paso
6. ✅ **Registra todo en el historial**

---

## 🔍 Ejemplo de Flujo

### Workflow: "Sub Gerencia de Desarrollo Urbano"

```
┌─────────────────────────────────────────────────────────────┐
│                    FLUJO DE APROBACIÓN                       │
└─────────────────────────────────────────────────────────────┘

Paso 1: Revisión de documentos (orden: 1)
   ↓ [Gerente aprueba] ✅
   │
   ├─ Estado paso 1: "aprobado"
   ├─ Fecha completado: now()
   └─ Se activa automáticamente el siguiente...

Paso 2: Evaluación técnica (orden: 2)
   ↓ [Gerente aprueba] ✅
   │
   ├─ Estado paso 2: "aprobado"
   ├─ Se activa automáticamente el siguiente...
   └─ Expediente estado: "en_proceso"

Paso 3: Aprobación jefe de área (orden: 3)
   ↓ [Jefe aprueba] ✅
   │
   ├─ Estado paso 3: "aprobado"
   ├─ ¡Es el último paso!
   └─ Expediente estado: "aprobado" (FINALIZADO)

┌─────────────────────────────────────────────────────────────┐
│              ✅ TRÁMITE COMPLETADO                          │
└─────────────────────────────────────────────────────────────┘
```

---

## 💾 Base de Datos: Tablas Involucradas

### 1. **`workflow_steps`** (Pasos del workflow)
```sql
id    workflow_id    nombre                      orden    tipo
1     1             Revisión de documentos       1        inicio
2     1             Evaluación técnica           2        proceso
3     1             Aprobación jefe de área      3        fin
```

### 2. **`expediente_workflow_progress`** (Progreso de cada paso)
```sql
id  expediente_id  workflow_step_id  estado      fecha_inicio  fecha_completado
1   10            1                 aprobado    2025-10-10    2025-10-11
2   10            2                 en_proceso  2025-10-11    NULL
3   10            3                 pendiente   NULL          NULL
```

### 3. **`expedientes`** (Expediente principal)
```sql
id  numero     current_step_id  estado        gerencia_id
10  EXP-2025   2                en_proceso    5
```

---

## 📝 Código Explicado

### 🎯 Método: `aprobar()` en ExpedienteWorkflowProgress

```php
public function aprobar($comentarios = null, $documentos = null)
{
    // 1. Marcar este paso como aprobado
    $this->update([
        'estado' => self::ESTADO_APROBADO,
        'fecha_completado' => Carbon::now(),
        'completado_por' => auth()->id(),
        'comentarios' => $comentarios,
        'documentos_adjuntos' => $documentos
    ]);

    // 2. Buscar el siguiente paso
    $siguienteEtapa = $this->workflowStep->siguienteEtapa();
    
    if ($siguienteEtapa) {
        // 3. Hay más pasos, activar el siguiente
        $this->expediente->activarSiguienteEtapa($siguienteEtapa);
    } else {
        // 4. Era el último paso, finalizar expediente
        $this->expediente->update(['estado' => 'aprobado']);
    }
}
```

### 🚀 Método: `activarSiguienteEtapa()` en Expediente

```php
public function activarSiguienteEtapa(WorkflowStep $siguienteEtapa)
{
    // 1. Actualizar el paso actual del expediente
    $this->current_step_id = $siguienteEtapa->id;
    $this->save();

    // 2. Buscar el progreso de ese paso
    $progreso = $this->workflowProgress()
        ->where('workflow_step_id', $siguienteEtapa->id)
        ->first();

    if ($progreso) {
        // 3. Si existe, iniciarlo
        $progreso->iniciar(); // Cambia estado a "en_proceso"
    } else {
        // 4. Si no existe, crearlo
        ExpedienteWorkflowProgress::create([
            'expediente_id' => $this->id,
            'workflow_step_id' => $siguienteEtapa->id,
            'estado' => ExpedienteWorkflowProgress::ESTADO_EN_PROCESO,
            'fecha_inicio' => now(),
            'fecha_limite' => now()->addDays($siguienteEtapa->tiempo_estimado ?? 30),
            'asignado_a' => auth()->id(),
        ]);
    }
}
```

---

## 🎬 Proceso Completo: Paso a Paso

### Usuario hace clic en "Aprobar"

```
1. Usuario: Clic en botón "Aprobar"
   └─> Se abre modal de confirmación

2. Usuario: Ingresa comentarios (opcional)
   └─> Clic en "Aprobar Trámite"

3. Frontend: Envía POST a /gerencia/tramites/{id}/aprobar
   └─> Con comentarios (opcional)

4. Backend (GerenciaTramiteController::aprobar)
   ├─ Valida que el usuario pertenezca a la gerencia
   ├─ Valida que el expediente esté en estado activo
   ├─ Busca el progreso actual (expediente_workflow_progress)
   └─> Llama a $progresoActual->aprobar()

5. Backend (ExpedienteWorkflowProgress::aprobar)
   ├─ Actualiza el registro a estado "aprobado"
   ├─ Registra fecha_completado y completado_por
   ├─ Busca el siguiente paso: $this->workflowStep->siguienteEtapa()
   │
   ├─ ¿Hay siguiente paso?
   │  ├─ SÍ → Llama a $expediente->activarSiguienteEtapa($siguiente)
   │  │     ├─ Actualiza current_step_id
   │  │     ├─ Inicia el progreso del siguiente paso
   │  │     └─ Estado expediente: "en_proceso"
   │  │
   │  └─ NO → Era el último paso
   │        ├─ Estado expediente: "aprobado"
   │        └─ Trámite finalizado
   │
   └─> Retorna al controlador

6. Backend (GerenciaTramiteController::aprobar)
   ├─ Recarga el expediente: $expediente->refresh()
   ├─ Verifica si hay siguiente etapa
   ├─ Genera mensaje personalizado
   ├─ Registra en historial
   └─> Redirige con mensaje de éxito

7. Frontend: Muestra mensaje de éxito
   └─> "¡Paso aprobado! El trámite avanzó a: [Nombre del siguiente paso]"
```

---

## 🔢 Estados del Expediente Durante el Flujo

| Momento | Estado Expediente | Estado Paso Actual | Acción |
|---------|-------------------|-------------------|---------|
| **Inicio** | `pendiente` | `pendiente` | Recién creado |
| **Primera aprobación** | `en_proceso` | Paso 1: `aprobado` | Avanza a Paso 2 |
| **Segunda aprobación** | `en_proceso` | Paso 2: `aprobado` | Avanza a Paso 3 |
| **Última aprobación** | `aprobado` | Paso 3: `aprobado` | Finalizado ✅ |

---

## 🎯 Estados de Workflow Progress

Cada paso puede tener estos estados:

| Estado | Descripción | Puede Aprobar |
|--------|-------------|---------------|
| `pendiente` | Esperando que llegue su turno | ❌ No |
| `en_proceso` | Es el paso actual, en ejecución | ✅ SÍ |
| `aprobado` | Completado exitosamente | ❌ No |
| `rechazado` | Rechazado, trámite detenido | ❌ No |
| `observado` | Tiene observaciones | ✅ SÍ |

---

## ⚙️ Configuración de Pasos

### En `WorkflowStepsSeeder.php`:

```php
[
    'nombre_etapa' => 'Revisión de documentos',
    'orden' => 1,  // ← Orden determina la secuencia
    'rol_requerido' => 'gerente',
    'dias_limite' => 3,
],
[
    'nombre_etapa' => 'Evaluación técnica',
    'orden' => 2,  // ← Siguiente en la secuencia
    'rol_requerido' => 'gerente',
    'dias_limite' => 7,
],
[
    'nombre_etapa' => 'Aprobación jefe de área',
    'orden' => 3,  // ← Último paso
    'rol_requerido' => 'jefe_gerencia',
    'dias_limite' => 2,
]
```

---

## 📊 Visualización en la Vista

La vista `show.blade.php` muestra el progreso:

```php
@foreach($steps as $step)
    @php
        $isCompleted = $step->orden < $currentStepOrder;
        $isCurrent = $step->id == $currentStepId;
        $isPending = $step->orden > $currentStepOrder;
    @endphp
    
    <!-- Esfera con check verde si completado -->
    @if($isCompleted)
        <i class="fas fa-check text-white"></i> ✅
    @endif
@endforeach
```

---

## 🧪 Prueba del Flujo Completo

### Test: Aprobar los 3 pasos de un workflow

```bash
# 1. Crear expediente (como ciudadano)
POST /ciudadano/tramites/crear
- tipo_tramite_id: 1 (Desarrollo Urbano)
- Estado inicial: "pendiente"
- Paso actual: 1 (Revisión de documentos)

# 2. Primera aprobación (Gerente)
POST /gerencia/tramites/1/aprobar
✅ Resultado:
   - Paso 1: "aprobado" ✓
   - Paso 2: "en_proceso" ← Automático
   - Expediente: "en_proceso"
   - Mensaje: "¡Paso aprobado! El trámite avanzó a: Evaluación técnica"

# 3. Segunda aprobación (Gerente)
POST /gerencia/tramites/1/aprobar
✅ Resultado:
   - Paso 2: "aprobado" ✓
   - Paso 3: "en_proceso" ← Automático
   - Expediente: "en_proceso"
   - Mensaje: "¡Paso aprobado! El trámite avanzó a: Aprobación jefe de área"

# 4. Tercera aprobación (Jefe)
POST /gerencia/tramites/1/aprobar
✅ Resultado:
   - Paso 3: "aprobado" ✓
   - Expediente: "aprobado" (FINALIZADO)
   - Mensaje: "¡Trámite completado y aprobado exitosamente!"
```

---

## 🔧 Métodos Útiles Agregados

### En `WorkflowStep.php`:

```php
// Obtener siguiente etapa
$siguiente = $step->siguienteEtapa(); // WorkflowStep | null

// Obtener anterior
$anterior = $step->etapaAnterior(); // WorkflowStep | null

// Verificar si es última
if ($step->esUltimaEtapa()) {
    // Finalizar trámite
}

// Verificar si es primera
if ($step->esPrimeraEtapa()) {
    // Es el inicio
}
```

---

## 📈 Estadísticas y Métricas

```sql
-- Pasos completados por expediente
SELECT 
    e.numero,
    COUNT(CASE WHEN ewp.estado = 'aprobado' THEN 1 END) as pasos_aprobados,
    COUNT(*) as total_pasos
FROM expedientes e
JOIN expediente_workflow_progress ewp ON e.id = ewp.expediente_id
WHERE e.id = 10
GROUP BY e.id;

-- Tiempo promedio por paso
SELECT 
    ws.nombre,
    AVG(TIMESTAMPDIFF(HOUR, ewp.fecha_inicio, ewp.fecha_completado)) as horas_promedio
FROM expediente_workflow_progress ewp
JOIN workflow_steps ws ON ewp.workflow_step_id = ws.id
WHERE ewp.estado = 'aprobado'
GROUP BY ws.id;
```

---

## ✅ Resumen

### ¿Qué pasa cuando apruebo?

1. ✅ El paso actual se marca como "aprobado"
2. ✅ El sistema busca automáticamente el siguiente paso
3. ✅ Si hay siguiente paso:
   - Se activa automáticamente
   - Estado cambia a "en_proceso"
   - El expediente continúa
4. ✅ Si NO hay siguiente paso:
   - El expediente se marca como "aprobado"
   - El trámite se finaliza
5. ✅ Todo se registra en el historial

### ¿Tengo que avanzar manualmente?

**NO** ❌ - El avance es **100% automático**

Solo necesitas:
- Clic en "Aprobar"
- Agregar comentarios (opcional)
- Confirmar

El sistema hace el resto.

---

## 🚨 Casos Especiales

### ¿Qué pasa si rechazo?

- ❌ El flujo se detiene
- ❌ El expediente se marca como "rechazado"
- ❌ NO avanza al siguiente paso
- ❌ Se notifica al ciudadano

### ¿Qué pasa si agrego observación?

- ⚠️ El flujo NO avanza
- ⚠️ El expediente se marca como "observado"
- ⚠️ El ciudadano debe corregir
- ⚠️ Luego se puede aprobar/rechazar

---

**✅ El flujo de aprobación está COMPLETAMENTE automatizado**

**Desarrollado por:** Sistema de Trámite Documentario Municipal  
**Versión:** 2.0  
**Estado:** ✅ Flujo Automático FUNCIONANDO
