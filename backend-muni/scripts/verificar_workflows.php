<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Verificando primer paso de workflows...\n\n";

$workflows = \App\Models\Workflow::with('steps')->take(5)->get();

foreach ($workflows as $workflow) {
    echo "Workflow #{$workflow->id}: {$workflow->nombre}\n";
    
    $primerPaso = $workflow->steps()->orderBy('orden')->first();
    
    if ($primerPaso) {
        echo "  ✓ Primer paso: {$primerPaso->nombre}\n";
        echo "    - Tipo: {$primerPaso->tipo}\n";
        echo "    - Orden: {$primerPaso->orden}\n";
    } else {
        echo "  ✗ No tiene pasos\n";
    }
    
    echo "\n";
}

echo "\n✅ Verificación completa\n";
