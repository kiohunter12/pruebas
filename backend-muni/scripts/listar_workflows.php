<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== LISTAR TODOS LOS WORKFLOWS ===\n\n";

$workflows = \App\Models\Workflow::all();

echo "Total de workflows: {$workflows->count()}\n\n";

foreach ($workflows as $w) {
    echo "ID: {$w->id} | Código: {$w->codigo} | Nombre: {$w->nombre}\n";
}

echo "\n=== EXPEDIENTES EN PROCESO ===\n\n";

$expedientes = \App\Models\Expediente::whereIn('estado', ['pendiente', 'en_proceso'])
    ->with(['workflow', 'currentStep', 'gerencia'])
    ->get();

foreach ($expedientes as $exp) {
    echo "Expediente: {$exp->numero}\n";
    echo "  Estado: {$exp->estado}\n";
    echo "  Workflow: " . ($exp->workflow->nombre ?? 'NULL') . "\n";
    echo "  Paso actual: " . ($exp->currentStep->nombre ?? 'NULL') . "\n";
    echo "  Gerencia: " . ($exp->gerencia->nombre ?? 'NULL') . "\n\n";
}
