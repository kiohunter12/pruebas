# Cambios: Documentos por Tipo de Trámite

## Resumen
Los documentos ahora se cargan dinámicamente según el **tipo de trámite** seleccionado, en lugar de mostrar todos los documentos disponibles en el sistema.

---

## Cambios Realizados

### 1. **WorkflowController.php** - Método `create()`

#### Antes:
```php
$tiposTramite = \App\Models\TipoTramite::with('gerencia')->get();

// Cargar todos los tipos de documentos disponibles
$tiposDocumento = \App\Models\TipoDocumento::orderBy('nombre')->get();

return view('workflows.create', compact('options', 'tiposTramite', 'gerenciasPrincipales', 'todasGerencias', 'usuarios', 'tiposDocumento'));
```

#### Después:
```php
$tiposTramite = \App\Models\TipoTramite::with(['gerencia', 'tiposDocumentos'])->get();

// NO cargar todos los documentos, se cargarán dinámicamente según el tipo de trámite seleccionado

return view('workflows.create', compact('options', 'tiposTramite', 'gerenciasPrincipales', 'todasGerencias', 'usuarios'));
```

**Explicación:**
- Se agregó eager loading de `tiposDocumentos` a la relación many-to-many
- Se eliminó la carga global de `$tiposDocumento`
- Ya no se pasa `$tiposDocumento` a la vista

---

### 2. **create.blade.php** - Alpine.js Data

#### Antes:
```javascript
tiposDocumento: @json($tiposDocumento ?? []),
```

#### Después:
```javascript
tiposDocumento: [], // Se cargarán dinámicamente desde el tipo de trámite seleccionado
```

**Explicación:**
- Inicializa el array vacío
- Los documentos se cargarán cuando el usuario seleccione un tipo de trámite

---

### 3. **create.blade.php** - Función `loadGerenciasForTramite()`

#### Antes:
```javascript
loadGerenciasForTramite() {
    if (!this.selectedTipoTramite) {
        this.gerenciasDisponibles = [];
        this.selectedTramiteInfo = null;
        this.areasSeleccionadas = [];
        return;
    }

    const tipoTramite = this.tiposTramite.find(t => t.id == this.selectedTipoTramite);
    if (!tipoTramite) return;

    this.selectedTramiteInfo = {
        // ... info del trámite
    };
    this.tramiteName = tipoTramite.nombre;
    
    // ... lógica de gerencias
}
```

#### Después:
```javascript
loadGerenciasForTramite() {
    if (!this.selectedTipoTramite) {
        this.gerenciasDisponibles = [];
        this.selectedTramiteInfo = null;
        this.areasSeleccionadas = [];
        this.tiposDocumento = []; // Limpiar documentos cuando no hay trámite
        return;
    }

    const tipoTramite = this.tiposTramite.find(t => t.id == this.selectedTipoTramite);
    if (!tipoTramite) return;

    // Cargar los documentos asociados a este tipo de trámite
    this.tiposDocumento = tipoTramite.tipos_documentos || [];
    console.log('Documentos cargados para el trámite:', this.tiposDocumento);

    this.selectedTramiteInfo = {
        // ... info del trámite
    };
    this.tramiteName = tipoTramite.nombre;
    
    // ... lógica de gerencias
}
```

**Explicación:**
- Cuando no hay trámite seleccionado, limpia los documentos
- Cuando se selecciona un trámite, carga `tipoTramite.tipos_documentos`
- Los documentos ahora son específicos del tipo de trámite seleccionado

---

## Flujo de Funcionamiento

```
1. Usuario abre "Crear Flujo"
   └─> tiposDocumento = []  (vacío)

2. Usuario selecciona "Tipo de Trámite"
   └─> loadGerenciasForTramite() ejecuta
       └─> this.tiposDocumento = tipoTramite.tipos_documentos
           └─> Se cargan solo los documentos de ese tipo de trámite

3. Usuario selecciona gerencias y marca documentos
   └─> Los checkboxes muestran solo documentos del tipo de trámite

4. Usuario envía el formulario
   └─> Se guardan los documentos seleccionados con su flag "Obligatorio"
```

---

## Relaciones de Base de Datos

### Relación Tipo Trámite → Documentos
```php
// En TipoTramite.php
public function tiposDocumentos()
{
    return $this->belongsToMany(TipoDocumento::class, 'tipo_tramite_tipo_documento');
}
```

### Relación Workflow Step → Documentos
```php
// En WorkflowStep.php
public function tiposDocumentos()
{
    return $this->belongsToMany(TipoDocumento::class, 'workflow_step_documents')
                ->withPivot('es_obligatorio', 'orden', 'descripcion')
                ->orderBy('orden');
}
```

---

## Ventajas de este Cambio

✅ **Relevancia**: Solo muestra documentos relevantes al tipo de trámite
✅ **Menos confusión**: El usuario no ve documentos de otros trámites
✅ **Mejor UX**: Interfaz más limpia y específica
✅ **Consistencia**: Los documentos corresponden a la lógica del negocio
✅ **Performance**: Eager loading eficiente con `with(['gerencia', 'tiposDocumentos'])`

---

## Archivos Modificados

1. `app/Http/Controllers/WorkflowController.php`
   - Método `create()`: Eager loading de documentos por trámite

2. `resources/views/workflows/create.blade.php`
   - Alpine.js: Carga dinámica de documentos según tipo de trámite seleccionado

---

## Próximos Pasos Recomendados

- ✅ Probar creación de flujo con diferentes tipos de trámite
- ✅ Verificar que los documentos se filtren correctamente
- ✅ Confirmar que el guardado funciona correctamente
- ⚠️ Considerar si `edit.blade.php` necesita el mismo cambio (probablemente no, ya que en edición es razonable mostrar todos los documentos)

---

## Comandos de Verificación

```bash
# Verificar sintaxis PHP
php -l app/Http/Controllers/WorkflowController.php

# Ver eager loading en acción (en tinker)
php artisan tinker
>>> $tramite = \App\Models\TipoTramite::with('tiposDocumentos')->first();
>>> $tramite->tiposDocumentos;
```

---

**Fecha:** 2025
**Versión:** Laravel 12.27.1
**Estado:** ✅ Implementado y verificado
