<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Expediente;
use App\Models\User;

$exp = Expediente::with(['workflow','workflowProgress','historial'])->orderBy('created_at','desc')->first();
if (!$exp) {
    echo "NO_EXPEDIENTE\n";
    exit(0);
}

echo "ID: {$exp->id}\n";
echo "NUM: {$exp->numero}\n";
echo "WORKFLOW: " . ($exp->workflow->nombre ?? 'NULL') . " (id: " . ($exp->workflow->id ?? 'NULL') . ")\n";
echo "ESTADO: {$exp->estado}\n";

echo "\nPROGRESOS (workflowProgress):\n";
foreach ($exp->workflowProgress as $p) {
    $user = $p->asignado_a ? User::with('roles')->find($p->asignado_a) : null;
    $roles = $user ? $user->roles->pluck('name')->join(',') : 'NULL';
    $uname = $user ? $user->name : 'NULL';
    echo "- id: {$p->id} | step_id: {$p->workflow_step_id} | estado: {$p->estado} | asignado_a: " . ($p->asignado_a ?? 'NULL') . " | usuario: {$uname} | roles: {$roles}\n";
}

echo "\nHISTORIAL:\n";
foreach ($exp->historial as $h) {
    echo "- {$h->accion} | usuario_id: {$h->usuario_id} | descripcion: " . substr($h->descripcion,0,120) . "\n";
}

exit(0);
