<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Boot the framework (without HTTP)
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$e = App\Models\Expediente::find(2);
if (!$e) {
    echo "Expediente not found\n";
    exit(1);
}
$step = $e->currentStep;
if (!$step) {
    echo "No current step\n";
    exit(1);
}

// Call activarSiguienteEtapa on the current step
$e->activarSiguienteEtapa($step);

// Show the progress row
$p = App\Models\ExpedienteWorkflowProgress::where('expediente_id', 2)
    ->where('workflow_step_id', $step->id)
    ->first();

if (!$p) {
    echo "No progress row found\n";
    exit(1);
}

echo json_encode($p->toArray(), JSON_PRETTY_PRINT) . "\n";
