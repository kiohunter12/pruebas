<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== ASIGNAR WORKFLOW A EXPEDIENTE ===\n\n";

// Buscar el workflow que creaste
$workflow = \App\Models\Workflow::where('codigo', 'WF-D8915870')->with('steps')->first();

if (!$workflow) {
    echo "❌ Workflow no encontrado\n";
    exit;
}

echo "Workflow encontrado: {$workflow->nombre} (ID: {$workflow->id})\n";
echo "Total de pasos: {$workflow->steps->count()}\n\n";

// Mostrar los pasos
foreach ($workflow->steps->sortBy('orden') as $step) {
    echo "  Paso {$step->orden}: {$step->nombre}\n";
    echo "    Responsable: {$step->responsable_tipo} #{$step->responsable_id}\n";
    
    if ($step->responsable_tipo === 'gerencia' && $step->responsable_id) {
        $gerencia = \App\Models\Gerencia::find($step->responsable_id);
        if ($gerencia) {
            echo "    Gerencia: {$gerencia->nombre}\n";
        }
    }
    echo "\n";
}

// Buscar un expediente sin workflow o con el workflow genérico
$expediente = \App\Models\Expediente::where('numero', 'EXP-2025-000026')->first();

if (!$expediente) {
    echo "❌ Expediente no encontrado\n";
    exit;
}

echo "Expediente seleccionado: {$expediente->numero}\n";
echo "  Estado actual: {$expediente->estado}\n";
echo "  Workflow actual: " . ($expediente->workflow->nombre ?? 'NULL') . "\n\n";

// Cambiar al nuevo workflow
$expediente->workflow_id = $workflow->id;
$expediente->current_step_id = $workflow->steps->sortBy('orden')->first()->id; // Primer paso
$expediente->estado = 'en_proceso';
$expediente->save();

echo "✅ Workflow actualizado exitosamente\n\n";

// Crear progreso para el primer paso
$primerPaso = $workflow->steps->sortBy('orden')->first();

// Determinar responsable
$responsableId = null;
if ($primerPaso->responsable_tipo === 'gerencia' && $primerPaso->responsable_id) {
    $gerencia = \App\Models\Gerencia::find($primerPaso->responsable_id);
    if ($gerencia) {
        $usuario = \App\Models\User::where('gerencia_id', $gerencia->id)
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['gerente', 'subgerente', 'jefe_gerencia']);
            })
            ->first();
        
        $responsableId = $usuario ? $usuario->id : null;
    }
}

// Limpiar progresos anteriores
\App\Models\ExpedienteWorkflowProgress::where('expediente_id', $expediente->id)->delete();

// Crear progreso inicial
$progreso = \App\Models\ExpedienteWorkflowProgress::create([
    'expediente_id' => $expediente->id,
    'workflow_step_id' => $primerPaso->id,
    'estado' => 'en_proceso',
    'fecha_inicio' => now(),
    'fecha_limite' => now()->addDays(3),
    'asignado_a' => $responsableId,
]);

echo "✅ Progreso creado para el primer paso\n";
echo "  Paso: {$primerPaso->nombre}\n";
echo "  Asignado a ID: " . ($responsableId ?? 'NULL') . "\n";

if ($responsableId) {
    $usuario = \App\Models\User::find($responsableId);
    if ($usuario) {
        echo "  Usuario: {$usuario->name}\n";
        echo "  Rol: " . $usuario->roles->pluck('name')->join(', ') . "\n";
        if ($usuario->gerencia) {
            echo "  Gerencia: {$usuario->gerencia->nombre}\n";
        }
    }
}

echo "\n✅ Expediente listo para aprobar y probar el flujo\n";
echo "\nAhora inicia sesión como {$usuario->name} ({$usuario->email}) y aprueba el expediente {$expediente->numero}\n";
echo "Después de aprobar, debería avanzar al paso 2 (Subgerencia de Promoción Empresarial y MYPE)\n";
