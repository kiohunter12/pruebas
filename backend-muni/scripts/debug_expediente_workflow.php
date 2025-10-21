<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== VERIFICAR ESTADO DE EXPEDIENTES ===\n\n";

// Buscar expedientes del workflow de Licencia
$workflow = \App\Models\Workflow::where('codigo', 'WF-D8915870')->first();

if (!$workflow) {
    echo "❌ Workflow no encontrado\n";
    exit;
}

echo "Workflow: {$workflow->nombre}\n\n";

// Obtener expedientes en este workflow
$expedientes = \App\Models\Expediente::where('workflow_id', $workflow->id)
    ->with(['currentStep', 'gerencia'])
    ->get();

if ($expedientes->isEmpty()) {
    echo "No hay expedientes en este workflow\n";
    exit;
}

foreach ($expedientes as $exp) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📄 Expediente: {$exp->numero}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "  Estado: {$exp->estado}\n";
    echo "  Gerencia: " . ($exp->gerencia->nombre ?? 'N/A') . "\n";
    echo "  Paso actual ID: " . ($exp->current_step_id ?? 'NULL') . "\n";
    echo "  Paso actual: " . ($exp->currentStep->nombre ?? 'N/A') . "\n\n";
    
    // Obtener TODO el historial de progreso
    $progresos = \App\Models\ExpedienteWorkflowProgress::where('expediente_id', $exp->id)
        ->with(['workflowStep', 'asignadoA', 'completadoPor'])
        ->orderBy('created_at')
        ->get();
    
    echo "  📊 HISTORIAL DE PROGRESO ({$progresos->count()} registros):\n\n";
    
    foreach ($progresos as $progreso) {
        $paso = $progreso->workflowStep;
        echo "    Paso: " . ($paso->nombre ?? 'N/A') . " (ID: {$progreso->workflow_step_id})\n";
        echo "    Orden: " . ($paso->orden ?? 'N/A') . "\n";
        echo "    Estado: {$progreso->estado}\n";
        echo "    Asignado a: " . ($progreso->asignadoA->name ?? 'NULL') . " (ID: " . ($progreso->asignado_a ?? 'NULL') . ")\n";
        
        if ($progreso->completado_por) {
            echo "    Completado por: " . ($progreso->completadoPor->name ?? 'N/A') . "\n";
            echo "    Fecha completado: {$progreso->fecha_completado}\n";
        }
        
        echo "    Creado: {$progreso->created_at}\n";
        echo "    Actualizado: {$progreso->updated_at}\n";
        echo "\n";
    }
    
    // Verificar el paso actual
    echo "  🎯 PASO ACTUAL:\n";
    if ($exp->current_step_id) {
        $progresoActual = \App\Models\ExpedienteWorkflowProgress::where('expediente_id', $exp->id)
            ->where('workflow_step_id', $exp->current_step_id)
            ->first();
        
        if ($progresoActual) {
            echo "    Estado: {$progresoActual->estado}\n";
            echo "    Asignado a: " . ($progresoActual->asignadoA->name ?? 'NULL') . " (ID: " . ($progresoActual->asignado_a ?? 'NULL') . ")\n";
            
            if ($progresoActual->asignado_a) {
                $usuario = \App\Models\User::find($progresoActual->asignado_a);
                if ($usuario && $usuario->gerencia) {
                    echo "    Gerencia del usuario: {$usuario->gerencia->nombre}\n";
                    echo "    Rol: " . $usuario->roles->pluck('name')->join(', ') . "\n";
                }
            }
        } else {
            echo "    ⚠️  No hay progreso para el paso actual\n";
        }
    } else {
        echo "    ⚠️  No hay paso actual definido\n";
    }
    
    echo "\n";
}

// Verificar los pasos del workflow
echo "\n=== PASOS DEL WORKFLOW ===\n\n";
$pasos = $workflow->steps()->orderBy('orden')->get();

foreach ($pasos as $paso) {
    echo "Paso {$paso->orden}: {$paso->nombre}\n";
    echo "  Responsable: {$paso->responsable_tipo} #{$paso->responsable_id}\n";
    
    if ($paso->responsable_tipo === 'gerencia' && $paso->responsable_id) {
        $gerencia = \App\Models\Gerencia::find($paso->responsable_id);
        if ($gerencia) {
            echo "  Gerencia: {$gerencia->nombre}\n";
            
            // Buscar usuarios
            $usuarios = \App\Models\User::where('gerencia_id', $gerencia->id)
                ->with('roles')
                ->get();
            
            echo "  Usuarios ({$usuarios->count()}):\n";
            foreach ($usuarios as $u) {
                echo "    • {$u->name} - " . $u->roles->pluck('name')->join(', ') . "\n";
            }
        }
    }
    echo "\n";
}
