<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== ESTADO ACTUAL DEL EXPEDIENTE ===\n\n";

$expediente = \App\Models\Expediente::where('numero', 'EXP-2025-000026')->first();

if (!$expediente) {
    echo "❌ Expediente no encontrado\n";
    exit;
}

echo "Expediente: {$expediente->numero}\n";
echo "Estado: {$expediente->estado}\n";
echo "Workflow: " . ($expediente->workflow->nombre ?? 'NULL') . "\n";
echo "Current Step ID: " . ($expediente->current_step_id ?? 'NULL') . "\n";
echo "Current Step: " . ($expediente->currentStep->nombre ?? 'NULL') . "\n\n";

// Ver TODOS los progresos
$progresos = \App\Models\ExpedienteWorkflowProgress::where('expediente_id', $expediente->id)
    ->with(['workflowStep', 'asignado'])
    ->orderBy('created_at', 'desc')
    ->get();

echo "Total de progresos: {$progresos->count()}\n\n";

foreach ($progresos as $progreso) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Progreso ID: {$progreso->id}\n";
    echo "Paso: " . ($progreso->workflowStep->nombre ?? 'N/A') . " (Orden: " . ($progreso->workflowStep->orden ?? 'N/A') . ")\n";
    echo "Estado: {$progreso->estado}\n";
    echo "Asignado a: " . ($progreso->asignado->name ?? 'NULL') . " (ID: " . ($progreso->asignado_a ?? 'NULL') . ")\n";
    if ($progreso->asignado) {
        echo "Email: {$progreso->asignado->email}\n";
        echo "Gerencia: " . ($progreso->asignado->gerencia->nombre ?? 'N/A') . "\n";
    }
    echo "Creado: {$progreso->created_at}\n";
    echo "Actualizado: {$progreso->updated_at}\n";
    echo "\n";
}

// Verificar qué ve Laura
echo "\n=== LO QUE VE LAURA (ID: 9) ===\n\n";

$laura = \App\Models\User::find(9);
if ($laura) {
    echo "Usuario: {$laura->name}\n";
    echo "Email: {$laura->email}\n";
    echo "Gerencia: " . ($laura->gerencia->nombre ?? 'N/A') . "\n\n";
    
    // Buscar expedientes asignados a Laura
    $expedientesLaura = \App\Models\Expediente::whereHas('workflowProgress', function($query) {
        $query->where('asignado_a', 9)
              ->whereIn('estado', ['pendiente', 'en_proceso']);
    })
    ->whereIn('estado', ['pendiente', 'en_proceso', 'en_revision', 'observado'])
    ->get();
    
    echo "Expedientes asignados a Laura: {$expedientesLaura->count()}\n";
    foreach ($expedientesLaura as $exp) {
        echo "  - {$exp->numero} (Estado: {$exp->estado})\n";
    }
}

// Verificar qué ve Ricardo
echo "\n=== LO QUE VE RICARDO (ID: 17) ===\n\n";

$ricardo = \App\Models\User::find(17);
if ($ricardo) {
    echo "Usuario: {$ricardo->name}\n";
    echo "Email: {$ricardo->email}\n";
    echo "Gerencia: " . ($ricardo->gerencia->nombre ?? 'N/A') . "\n\n";
    
    // Buscar expedientes asignados a Ricardo
    $expedientesRicardo = \App\Models\Expediente::whereHas('workflowProgress', function($query) {
        $query->where('asignado_a', 17)
              ->whereIn('estado', ['pendiente', 'en_proceso']);
    })
    ->whereIn('estado', ['pendiente', 'en_proceso', 'en_revision', 'observado'])
    ->get();
    
    echo "Expedientes asignados a Ricardo: {$expedientesRicardo->count()}\n";
    foreach ($expedientesRicardo as $exp) {
        echo "  - {$exp->numero} (Estado: {$exp->estado})\n";
    }
}
