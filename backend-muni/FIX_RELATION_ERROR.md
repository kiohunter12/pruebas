# ✅ Solución: Error RelationNotFoundException tiposDocumentos

## 🔴 Error Original
```
Illuminate\Database\Eloquent\RelationNotFoundException
Call to undefined relationship [tiposDocumentos] on model [App\Models\TipoTramite]
```

## 🔍 Causa del Problema
El modelo `TipoTramite` tenía la relación llamada `documentos()`, pero en el controller estábamos intentando hacer eager loading con `tiposDocumentos`:

```php
// En WorkflowController.php
$tiposTramite = \App\Models\TipoTramite::with(['gerencia', 'tiposDocumentos'])->get();
```

## ✅ Solución Implementada

### 1. Agregamos método alias en `TipoTramite.php`

```php
/**
 * Alias para la relación documentos() - usado en eager loading
 */
public function tiposDocumentos(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
{
    return $this->documentos();
}
```

**Ventaja:** Mantiene compatibilidad con código existente que usa `documentos()` y permite usar `tiposDocumentos()` en eager loading.

### 2. Actualizamos JavaScript en `create.blade.php`

```javascript
// Cargar los documentos asociados a este tipo de trámite
// Laravel devuelve las relaciones en camelCase: tiposDocumentos
this.tiposDocumento = tipoTramite.tipos_documentos || tipoTramite.tiposDocumentos || [];
```

**Explicación:** Intentamos ambos formatos (snake_case y camelCase) para mayor compatibilidad.

## 🧪 Prueba de Funcionamiento

Ejecutamos el script de prueba:

```bash
php test_relation.php
```

**Resultado:**
```
=== Probando relación tiposDocumentos ===

Tipo Trámite: Licencia de Funcionamiento
ID: 1
Documentos relacionados: 4

Lista de documentos:
  - Solicitud
  - Declaración Jurada
  - Copia de DNI
  - Copia de RUC

=== Prueba completada ===
```

✅ **La relación funciona correctamente**

## 📝 Archivos Modificados

1. **app/Models/TipoTramite.php**
   - ✅ Agregado método `tiposDocumentos()` como alias de `documentos()`

2. **resources/views/workflows/create.blade.php**
   - ✅ JavaScript actualizado para soportar ambos formatos de nombre

## 🎯 Cómo Funciona Ahora

```
1. Controller hace eager loading:
   TipoTramite::with(['gerencia', 'tiposDocumentos'])
   
2. Laravel carga la relación usando el método:
   public function tiposDocumentos() { return $this->documentos(); }
   
3. JSON incluye los documentos en la respuesta

4. JavaScript en Alpine.js accede a:
   tipoTramite.tiposDocumentos (camelCase)
   o
   tipoTramite.tipos_documentos (snake_case)
   
5. Se muestran solo los documentos del tipo de trámite seleccionado
```

## ✅ Estado Actual

- ✅ Relación `tiposDocumentos()` definida en modelo
- ✅ Eager loading funciona correctamente
- ✅ Prueba unitaria exitosa
- ✅ JavaScript compatible con ambos formatos
- ✅ Sin errores de sintaxis PHP

## 🚀 Próximo Paso

**Refrescar la página** `/workflows/create` en el navegador y verificar que:
1. No aparezca el error de RelationNotFoundException
2. Al seleccionar un tipo de trámite, se carguen sus documentos
3. Los checkboxes de documentos se muestren correctamente por paso

---

**Fecha:** 7 de octubre, 2025  
**Estado:** ✅ Resuelto  
**Archivo de prueba:** `test_relation.php` (se puede eliminar después de verificar)
