<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Expediente;
use App\Models\User;

$exp = Expediente::with(['workflow','workflowProgress'])->orderBy('created_at','desc')->first();
if (!$exp) {
    echo "NO_EXPEDIENTE\n";
    exit(0);
}

$pro = $exp->workflowProgress->whereIn('estado',['pendiente','en_proceso'])->first();
if (!$pro) {
    echo "NO_PROGRESS_PENDING\n";
    exit(0);
}

$gerenciaId = $exp->gerencia_id ?? null;
if (!$gerenciaId) {
    echo "EXPEDIENTE_SIN_GERENCIA\n";
    exit(0);
}

$user = User::where('gerencia_id', $gerenciaId)
    ->whereHas('roles', function($q){
        $q->whereIn('name', ['gerente','subgerente','jefe_gerencia']);
    })->first();

if (!$user) {
    echo "NO_USER_IN_GERENCIA (gerencia_id: {$gerenciaId})\n";
    exit(0);
}

// Assign
$pro->asignado_a = $user->id;
$pro->save();

echo "OK: expediente {$exp->numero} progreso_id {$pro->id} asignado_a {$user->id} ({$user->name})\n";
exit(0);
