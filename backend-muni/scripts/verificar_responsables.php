<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== VERIFICACIÓN DE WORKFLOWS Y RESPONSABLES ===\n\n";

// Verificar workflows
$workflows = \App\Models\Workflow::with('steps')->get();

echo "Total Workflows: " . $workflows->count() . "\n\n";

foreach ($workflows as $workflow) {
    echo "Workflow: {$workflow->nombre} (Código: {$workflow->codigo})\n";
    echo "  Pasos: {$workflow->steps->count()}\n";
    
    foreach ($workflow->steps->sortBy('orden') as $step) {
        echo "  └─ Paso {$step->orden}: {$step->nombre}\n";
        echo "     Responsable Tipo: {$step->responsable_tipo}\n";
        echo "     Responsable ID: " . ($step->responsable_id ?? 'NULL') . "\n";
        
        if ($step->responsable_tipo === 'usuario' && $step->responsable_id) {
            $usuario = \App\Models\User::find($step->responsable_id);
            if ($usuario) {
                echo "     Usuario: {$usuario->name} ({$usuario->email})\n";
                echo "     Gerencia: " . ($usuario->gerencia->nombre ?? 'N/A') . "\n";
            }
        } elseif ($step->responsable_tipo === 'gerencia' && $step->responsable_id) {
            $gerencia = \App\Models\Gerencia::find($step->responsable_id);
            if ($gerencia) {
                echo "     Gerencia: {$gerencia->nombre}\n";
            }
        }
        echo "\n";
    }
    echo "\n";
}

// Verificar expedientes en proceso
echo "\n=== EXPEDIENTES EN PROCESO ===\n\n";
$expedientes = \App\Models\Expediente::with(['currentStep', 'workflowProgress'])
    ->whereIn('estado', ['pendiente', 'en_proceso'])
    ->take(5)
    ->get();

foreach ($expedientes as $exp) {
    echo "Expediente: {$exp->numero}\n";
    echo "  Estado: {$exp->estado}\n";
    echo "  Paso actual: " . ($exp->currentStep->nombre ?? 'N/A') . "\n";
    
    $progresoActual = $exp->workflowProgress()
        ->where('workflow_step_id', $exp->current_step_id)
        ->first();
    
    if ($progresoActual) {
        echo "  Asignado a: " . ($progresoActual->asignado_a ?? 'NULL') . "\n";
        if ($progresoActual->asignado_a) {
            $usuario = \App\Models\User::find($progresoActual->asignado_a);
            if ($usuario) {
                echo "  Usuario: {$usuario->name}\n";
                echo "  Rol: " . $usuario->roles->pluck('name')->join(', ') . "\n";
            }
        }
    }
    echo "\n";
}
