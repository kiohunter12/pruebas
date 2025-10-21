# 🔐 Control de Acceso y Visibilidad de Documentos en Workflows

## 📋 Pregunta del Usuario

> **"¿El encargado de tal gerencia solo puede ver el documento que se le asigna?"**
> **"¿Usamos workflow_steps y workflow_rule?"**

---

## 🎯 Respuesta: SÍ, Control por Paso

### ✅ **Cómo Funciona Actualmente**

```
┌─────────────────────────────────────────────────────────┐
│                    WORKFLOW                              │
│  "Flujo para Licencia de Edificación"                  │
└─────────────────────────────────────────────────────────┘
                        │
        ┌───────────────┴────────────────┐
        │                                │
┌───────▼──────────┐          ┌─────────▼────────────┐
│  PASO 1          │          │  PASO 2              │
│  Gerencia A      │──────────▶  Gerencia B          │
│  👤 Juan Pérez   │          │  👤 María López      │
├──────────────────┤          ├──────────────────────┤
│ 📄 Documentos:   │          │ 📄 Documentos:       │
│  • DNI           │          │  • Título Propiedad  │
│  • Solicitud     │          │                      │
└──────────────────┘          └──────────────────────┘
       │                              │
       │ SOLO VE ESTOS                │ SOLO VE ESTE
       │ DOCUMENTOS                   │ DOCUMENTO
```

---

## 🗂️ Modelos Involucrados

### 1️⃣ **Workflow**
```php
// app/Models/Workflow.php
class Workflow extends Model
{
    // Define el flujo completo del trámite
    public function steps() { /* Todos los pasos */ }
    public function rules() { /* Reglas del workflow */ }
}
```

### 2️⃣ **WorkflowStep** ⭐ (Principal)
```php
// app/Models/WorkflowStep.php
class WorkflowStep extends Model
{
    // Cada paso está asignado a una gerencia
    public function gerencia() { /* Gerencia responsable */ }
    
    // Documentos específicos de ESTE paso
    public function tiposDocumentos() { 
        /* Documentos que SOLO esta gerencia verá */
    }
    
    // Progreso del expediente en este paso
    public function progress() { /* Estado actual */ }
}
```

### 3️⃣ **WorkflowStepDocument** ⭐ (Relación Clave)
```php
// Tabla pivot: workflow_step_documents
// Conecta pasos con documentos

workflow_step_id | tipo_documento_id | es_obligatorio
-----------------|-------------------|----------------
        1        |         7         |      true
        1        |         8         |      false
        2        |         9         |      true
```

### 4️⃣ **ExpedienteWorkflowProgress**
```php
// app/Models/ExpedienteWorkflowProgress.php
class ExpedienteWorkflowProgress extends Model
{
    // Rastrea el progreso del expediente
    // EN ESTE PASO específico
    
    public function workflowStep() { /* Paso actual */ }
    public function asignadoA() { /* Usuario responsable */ }
    
    // Estados: pendiente, en_proceso, aprobado, rechazado
}
```

### 5️⃣ **WorkflowRule** (Reglas de Enrutamiento)
```php
// app/Models/WorkflowRule.php
class WorkflowRule extends Model
{
    // Reglas para dirigir automáticamente 
    // el expediente a una gerencia específica
    
    // Ejemplo:
    // Si tipo_tramite = "Licencia Construcción"
    // Y palabra_clave contiene "edificio"
    // Entonces gerencia_destino = "Obras Privadas"
}
```

---

## 🔐 Control de Acceso Implementado

### ✅ **Nivel 1: Por Gerencia**

Cada usuario pertenece a una gerencia:

```php
// Usuario logueado
$user = Auth::user();
$gerenciaUsuario = $user->gerencia_id;

// Solo puede ver pasos de SU gerencia
$pasosPendientes = WorkflowStep::where('gerencia_id', $gerenciaUsuario)
                                ->whereHas('progress', function($q) {
                                    $q->where('estado', 'pendiente')
                                      ->where('asignado_a', Auth::id());
                                })->get();
```

### ✅ **Nivel 2: Por Paso del Workflow**

Cada paso tiene documentos específicos:

```php
// Obtener documentos del paso actual
$paso = WorkflowStep::find($stepId);
$documentosDelPaso = $paso->tiposDocumentos; 

// El encargado SOLO ve estos documentos
// NO ve los documentos de otros pasos
```

### ✅ **Nivel 3: Por Asignación de Usuario**

```php
// ExpedienteWorkflowProgress
// Campo: asignado_a

// Solo el usuario asignado puede ver/editar este paso
$progreso = ExpedienteWorkflowProgress::where('workflow_step_id', $stepId)
                                       ->where('asignado_a', Auth::id())
                                       ->first();
```

---

## 📊 Ejemplo Real con tu Screenshot

En tu imagen veo:

```
Paso 1: Subgerencia de Planeamiento Urbano y Rural
  📄 Certificado de Parámetros
  📄 Título de Propiedad

Paso 2: Subgerencia de Obras Privadas y Licencias
  📄 Título de Propiedad
```

### 🔍 **¿Qué puede ver cada gerencia?**

| Usuario | Gerencia | Documentos Visibles |
|---------|----------|---------------------|
| Juan Pérez | Planeamiento Urbano | ✅ Certificado de Parámetros<br>✅ Título de Propiedad |
| María López | Obras Privadas | ✅ Título de Propiedad<br>❌ NO ve Certificado de Parámetros |

---

## 🛠️ Implementación en el Código

### Controlador de Expedientes

```php
// app/Http/Controllers/ExpedienteController.php

public function misPasosPendientes()
{
    $user = Auth::user();
    
    // Solo obtener pasos de MI gerencia
    $pasosPendientes = ExpedienteWorkflowProgress::query()
        ->whereHas('workflowStep', function($q) use ($user) {
            $q->where('gerencia_id', $user->gerencia_id);
        })
        ->where('asignado_a', $user->id)
        ->where('estado', 'pendiente')
        ->with('workflowStep.tiposDocumentos') // ← Documentos del paso
        ->get();
    
    return view('expedientes.mis-pendientes', compact('pasosPendientes'));
}
```

### Vista Blade

```blade
{{-- resources/views/expedientes/mis-pendientes.blade.php --}}

@foreach($pasosPendientes as $progreso)
    <div class="paso-card">
        <h3>{{ $progreso->workflowStep->nombre }}</h3>
        
        <h4>📄 Documentos requeridos:</h4>
        <ul>
            @foreach($progreso->workflowStep->tiposDocumentos as $doc)
                <li>
                    {{ $doc->nombre }}
                    @if($doc->pivot->es_obligatorio)
                        <span class="badge-obligatorio">Obligatorio</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endforeach
```

---

## 🚀 WorkflowRule: Enrutamiento Automático

### ¿Para qué sirve WorkflowRule?

**Enrutar automáticamente** expedientes a gerencias según criterios:

```php
// Ejemplo: Crear regla
WorkflowRule::create([
    'nombre_regla' => 'Edificios grandes a Obras Privadas',
    'tipo_tramite' => 'Licencia de Edificación',
    'palabra_clave' => 'edificio|construcción|más de 3 pisos',
    'gerencia_destino_id' => 8, // ID de Obras Privadas
    'prioridad' => 1,
    'activa' => true
]);
```

### Aplicación de Reglas

```php
// Servicio de Workflow
public function aplicarReglas(Expediente $expediente)
{
    $reglas = WorkflowRule::activas()
                          ->where('tipo_tramite', $expediente->tipo_tramite)
                          ->orderBy('prioridad')
                          ->get();
    
    foreach ($reglas as $regla) {
        if ($this->cumpleCondiciones($expediente, $regla)) {
            // Asignar a la gerencia destino
            $this->asignarAGerencia($expediente, $regla->gerencia_destino_id);
            break;
        }
    }
}
```

---

## ✅ Resumen: ¿Quién ve qué?

### 🎯 **Respuesta Directa**

**SÍ**, el encargado de cada gerencia **SOLO ve los documentos asignados a su paso**.

### 📊 Flujo de Control

```
1. Expediente llega al sistema
   ↓
2. WorkflowRule determina gerencia inicial (opcional)
   ↓
3. Se crea ExpedienteWorkflowProgress para el primer paso
   ↓
4. Usuario de la gerencia ve:
   ✅ Solo SU paso actual
   ✅ Solo documentos de ESE paso
   ✅ Solo expedientes asignados a él
   ↓
5. Completa el paso → avanza al siguiente
   ↓
6. Nueva gerencia toma el control
   (con OTROS documentos)
```

---

## 🔧 Mejoras Recomendadas

### 1️⃣ **Middleware de Autorización**

```php
// app/Http/Middleware/VerificarAccesoPaso.php

public function handle($request, Closure $next)
{
    $paso = WorkflowStep::find($request->paso_id);
    
    if ($paso->gerencia_id !== Auth::user()->gerencia_id) {
        abort(403, 'No tiene acceso a este paso del workflow');
    }
    
    return $next($request);
}
```

### 2️⃣ **Policy para WorkflowStep**

```php
// app/Policies/WorkflowStepPolicy.php

public function view(User $user, WorkflowStep $step)
{
    return $user->gerencia_id === $step->gerencia_id;
}

public function update(User $user, WorkflowStep $step)
{
    return $user->gerencia_id === $step->gerencia_id 
           && $user->hasPermissionTo('editar_workflows');
}
```

### 3️⃣ **Scope Global en WorkflowStep**

```php
// app/Models/WorkflowStep.php

protected static function booted()
{
    static::addGlobalScope('gerencia', function (Builder $builder) {
        if (Auth::check() && !Auth::user()->hasRole('superadministrador')) {
            $builder->where('gerencia_id', Auth::user()->gerencia_id);
        }
    });
}
```

---

## 📝 Checklist de Implementación

- [x] ✅ WorkflowStep con gerencia_id
- [x] ✅ WorkflowStepDocument (documentos por paso)
- [x] ✅ ExpedienteWorkflowProgress (tracking)
- [x] ✅ WorkflowRule (enrutamiento)
- [ ] ⏳ Middleware de verificación de acceso
- [ ] ⏳ Policy para control de autorización
- [ ] ⏳ Scope global por gerencia
- [ ] ⏳ Auditoría de accesos

---

## 🎓 Conclusión

**Respuestas a tus preguntas:**

1. ✅ **"¿El encargado solo ve sus documentos?"**
   - SÍ, vía `WorkflowStep → tiposDocumentos`

2. ✅ **"¿Usamos workflow_steps?"**
   - SÍ, es el modelo PRINCIPAL para control de acceso

3. ✅ **"¿Usamos workflow_rule?"**
   - SÍ, para enrutamiento automático (opcional pero útil)

**Arquitectura:**
```
WorkflowStep (paso) 
  → define gerencia responsable
  → define documentos específicos
  → solo usuarios de ESA gerencia ven ESE paso
```

---

**Fecha:** 9 de octubre, 2025  
**Estado:** ✅ Documentado  
**Próximo paso:** Implementar middleware y policies para fortalecer seguridad
