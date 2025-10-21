# 🔄 FLUJO DE DERIVACIÓN ENTRE GERENCIAS

## 📋 Estado Actual

**NO IMPLEMENTADO** - Actualmente un expediente se queda en la misma gerencia desde inicio hasta fin.

## 🎯 ¿Cuándo se necesita derivar?

1. **Trámites multi-gerencia:** Un expediente que requiere aprobación de varias gerencias
2. **Corrección de asignación:** Expediente llegó a gerencia equivocada
3. **Consultas entre gerencias:** Una gerencia necesita opinión de otra

## 🔧 Implementación Propuesta

### Opción 1: DERIVACIÓN COMPLETA (Cambia de gerencia)

```php
public function derivar(Request $request, $id)
{
    $validated = $request->validate([
        'nueva_gerencia_id' => 'required|exists:gerencias,id',
        'motivo' => 'required|string|max:500',
        'mantener_workflow' => 'boolean'
    ]);

    $expediente = Expediente::findOrFail($id);
    $gerenciaAnterior = $expediente->gerencia;
    
    // Guardar gerencia anterior
    $expediente->gerencia_padre_id = $expediente->gerencia_id;
    
    // Cambiar a nueva gerencia
    $expediente->gerencia_id = $validated['nueva_gerencia_id'];
    
    if (!$validated['mantener_workflow']) {
        // Buscar workflow de la nueva gerencia
        $nuevoWorkflow = Workflow::where('gerencia_id', $validated['nueva_gerencia_id'])
            ->where('activo', true)
            ->first();
        
        if ($nuevoWorkflow) {
            // Reiniciar workflow
            $expediente->workflow_id = $nuevoWorkflow->id;
            $primerPaso = $nuevoWorkflow->steps()->orderBy('orden')->first();
            $expediente->current_step_id = $primerPaso->id;
            
            // Crear progreso del nuevo workflow
            ExpedienteWorkflowProgress::create([
                'expediente_id' => $expediente->id,
                'workflow_step_id' => $primerPaso->id,
                'estado' => 'pendiente',
                'fecha_inicio' => now(),
                'fecha_limite' => now()->addDays($primerPaso->dias_limite),
            ]);
        }
    }
    
    $expediente->save();
    
    // Registrar derivación
    HistorialExpediente::create([
        'expediente_id' => $expediente->id,
        'accion' => 'derivar_gerencia',
        'descripcion' => "Derivado de {$gerenciaAnterior->nombre} a {$expediente->gerencia->nombre}. Motivo: {$validated['motivo']}",
        'usuario_id' => auth()->id(),
    ]);
    
    // Notificar a la nueva gerencia
    $this->notificarNuevaGerencia($expediente);
    
    return redirect()->back()->with('success', 'Expediente derivado correctamente');
}
```

### Opción 2: CONSULTA INTER-GERENCIAS (No cambia de gerencia)

```php
public function solicitarOpinion(Request $request, $id)
{
    $validated = $request->validate([
        'gerencia_consultada_id' => 'required|exists:gerencias,id',
        'consulta' => 'required|string|max:1000',
    ]);

    $expediente = Expediente::findOrFail($id);
    
    // Crear consulta
    ConsultaInterGerencias::create([
        'expediente_id' => $expediente->id,
        'gerencia_solicitante_id' => $expediente->gerencia_id,
        'gerencia_consultada_id' => $validated['gerencia_consultada_id'],
        'consulta' => $validated['consulta'],
        'estado' => 'pendiente',
        'usuario_solicita_id' => auth()->id(),
    ]);
    
    // Cambiar estado del expediente temporalmente
    $expediente->estado = 'en_consulta';
    $expediente->save();
    
    return redirect()->back()->with('success', 'Consulta enviada correctamente');
}
```

## 🗂️ Tabla Necesaria: `consultas_inter_gerencias`

```sql
CREATE TABLE consultas_inter_gerencias (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    expediente_id BIGINT NOT NULL,
    gerencia_solicitante_id BIGINT NOT NULL,
    gerencia_consultada_id BIGINT NOT NULL,
    consulta TEXT NOT NULL,
    respuesta TEXT NULL,
    estado ENUM('pendiente', 'respondida', 'cancelada') DEFAULT 'pendiente',
    usuario_solicita_id BIGINT NOT NULL,
    usuario_responde_id BIGINT NULL,
    fecha_consulta DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta DATETIME NULL,
    FOREIGN KEY (expediente_id) REFERENCES expedientes(id),
    FOREIGN KEY (gerencia_solicitante_id) REFERENCES gerencias(id),
    FOREIGN KEY (gerencia_consultada_id) REFERENCES gerencias(id),
    FOREIGN KEY (usuario_solicita_id) REFERENCES users(id),
    FOREIGN KEY (usuario_responde_id) REFERENCES users(id)
);
```

## 📊 Estados del Expediente durante derivación

- **`en_derivacion`**: Expediente en tránsito entre gerencias
- **`en_consulta`**: Esperando opinión de otra gerencia
- **`pendiente_devolucion`**: Gerencia destino completó y devuelve

## 🎨 UI: Modal de Derivación

```html
<!-- Modal Derivar -->
<div id="modalDerivar" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold mb-4">Derivar Expediente</h3>
        
        <form action="{{ route('gerencia.tramites.derivar', $expediente->id) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Gerencia Destino *</label>
                <select name="nueva_gerencia_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Seleccionar gerencia...</option>
                    @foreach($gerencias as $gerencia)
                        @if($gerencia->id != $expediente->gerencia_id)
                            <option value="{{ $gerencia->id }}">{{ $gerencia->nombre }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Motivo de Derivación *</label>
                <textarea name="motivo" required rows="3" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="mantener_workflow" value="1" class="mr-2">
                    <span class="text-sm">Mantener workflow actual</span>
                </label>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="cerrarModalDerivar()" class="px-4 py-2 bg-gray-300 rounded">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">
                    Derivar
                </button>
            </div>
        </form>
    </div>
</div>
```

## 🔐 Permisos Necesarios

```php
// En RolesAndPermissionsSeeder.php
'derivar_expedientes',        // Derivar a otra gerencia
'solicitar_opinion',           // Pedir opinión a otra gerencia
'responder_consultas',         // Responder consultas de otras gerencias
```

## ⚡ Notificaciones

- **Gerencia origen:** "Expediente derivado a [Gerencia]"
- **Gerencia destino:** "Nuevo expediente recibido por derivación"
- **Ciudadano:** "Su trámite ha sido derivado a [Gerencia]"

## 🎯 Resumen

| Escenario | Acción | Cambia Gerencia | Cambia Workflow |
|-----------|--------|-----------------|-----------------|
| Aprobar paso | aprobar() | ❌ NO | ❌ NO (solo avanza) |
| Derivar completo | derivar() | ✅ SÍ | ✅ SÍ (opcional) |
| Solicitar opinión | solicitarOpinion() | ❌ NO | ❌ NO |
