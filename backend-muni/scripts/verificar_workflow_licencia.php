<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== VERIFICACIÓN WORKFLOW LICENCIA DE FUNCIONAMIENTO ===\n\n";

// Buscar el workflow de Licencia de Funcionamiento
$workflow = \App\Models\Workflow::where('codigo', 'WF-87A00C09')
    ->with(['steps' => function($query) {
        $query->orderBy('orden');
    }])
    ->first();

if (!$workflow) {
    echo "❌ No se encontró el workflow\n";
    exit;
}

echo "Workflow: {$workflow->nombre}\n";
echo "Código: {$workflow->codigo}\n";
echo "Total Pasos: {$workflow->steps->count()}\n\n";

foreach ($workflow->steps as $step) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "PASO {$step->orden}: {$step->nombre}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "  Responsable Tipo: {$step->responsable_tipo}\n";
    echo "  Responsable ID: " . ($step->responsable_id ?? 'NULL') . "\n";
    
    if ($step->responsable_tipo === 'gerencia' && $step->responsable_id) {
        $gerencia = \App\Models\Gerencia::find($step->responsable_id);
        if ($gerencia) {
            echo "  📁 Gerencia: {$gerencia->nombre}\n";
            echo "  📝 Código: {$gerencia->codigo}\n";
            echo "  📍 Tipo: {$gerencia->tipo}\n";
            
            // Buscar usuarios de esta gerencia
            $usuarios = \App\Models\User::where('gerencia_id', $gerencia->id)
                ->with('roles')
                ->get();
            
            echo "\n  👥 Usuarios disponibles: {$usuarios->count()}\n";
            foreach ($usuarios as $usuario) {
                $roles = $usuario->roles->pluck('name')->join(', ');
                echo "     • {$usuario->name}\n";
                echo "       Email: {$usuario->email}\n";
                echo "       Roles: {$roles}\n";
            }
        } else {
            echo "  ❌ Gerencia no encontrada\n";
        }
    } elseif ($step->responsable_tipo === 'usuario' && $step->responsable_id) {
        $usuario = \App\Models\User::find($step->responsable_id);
        if ($usuario) {
            echo "  👤 Usuario: {$usuario->name}\n";
            echo "  📧 Email: {$usuario->email}\n";
            $roles = $usuario->roles->pluck('name')->join(', ');
            echo "  🎭 Roles: {$roles}\n";
        } else {
            echo "  ❌ Usuario no encontrado\n";
        }
    } else {
        echo "  ⚠️  No hay responsable asignado\n";
    }
    echo "\n";
}

echo "\n=== EXPEDIENTES EN ESTE WORKFLOW ===\n\n";

// Buscar expedientes que usan este workflow
$expedientes = \App\Models\Expediente::where('workflow_id', $workflow->id)
    ->with(['currentStep', 'workflowProgress'])
    ->take(5)
    ->get();

foreach ($expedientes as $exp) {
    echo "Expediente: {$exp->numero}\n";
    echo "  Estado: {$exp->estado}\n";
    echo "  Paso actual: " . ($exp->currentStep->nombre ?? 'N/A') . "\n";
    
    // Obtener el progreso actual
    $progresoActual = $exp->workflowProgress()
        ->where('workflow_step_id', $exp->current_step_id)
        ->first();
    
    if ($progresoActual) {
        echo "  Estado progreso: {$progresoActual->estado}\n";
        echo "  Asignado a ID: " . ($progresoActual->asignado_a ?? 'NULL') . "\n";
        
        if ($progresoActual->asignado_a) {
            $usuario = \App\Models\User::find($progresoActual->asignado_a);
            if ($usuario) {
                echo "  👤 Usuario: {$usuario->name}\n";
                echo "  🎭 Rol: " . $usuario->roles->pluck('name')->join(', ') . "\n";
                if ($usuario->gerencia) {
                    echo "  📁 Gerencia: {$usuario->gerencia->nombre}\n";
                }
            }
        }
    }
    echo "\n";
}
