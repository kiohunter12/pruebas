<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Expediente;

$exp = Expediente::with(['workflow','workflowProgress'])->orderBy('created_at','desc')->first();
if (!$exp) { echo "NO_EXPEDIENTE\n"; exit(0); }

$pro = $exp->workflowProgress->whereIn('estado',['pendiente','en_proceso'])->first();
if (!$pro) { echo "NO_PROGRESS\n"; exit(0); }

// Simular aprobacion
$pro->aprobar('Aprobado por script de prueba', null);

echo "APROBADO: expediente {$exp->numero} progreso {$pro->id}\n";

// Mostrar nuevo progreso activo
$exp = $exp->fresh();
foreach ($exp->workflowProgress as $p) {
    echo "- id {$p->id} step {$p->workflow_step_id} estado {$p->estado} asignado_a " . ($p->asignado_a ?? 'NULL') . "\n";
}

exit(0);
