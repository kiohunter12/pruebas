<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== ACTUALIZAR RESPONSABLES DEL WORKFLOW ===\n\n";

// Buscar gerencias
$gerenciaDesarrolloEconomico = \App\Models\Gerencia::where('codigo', 'GDEL')->first();
$subgerenciaPromocion = \App\Models\Gerencia::where('nombre', 'LIKE', '%Promoción Empresarial%')->first();
$subgerenciaComercio = \App\Models\Gerencia::where('nombre', 'LIKE', '%Comercio%')->first();

echo "Gerencias encontradas:\n";
echo "  1. Desarrollo Económico: " . ($gerenciaDesarrolloEconomico ? $gerenciaDesarrolloEconomico->nombre . " (ID: {$gerenciaDesarrolloEconomico->id})" : 'NO ENCONTRADA') . "\n";
echo "  2. Sub Promoción: " . ($subgerenciaPromocion ? $subgerenciaPromocion->nombre . " (ID: {$subgerenciaPromocion->id})" : 'NO ENCONTRADA') . "\n";
echo "  3. Sub Comercio: " . ($subgerenciaComercio ? $subgerenciaComercio->nombre . " (ID: {$subgerenciaComercio->id})" : 'NO ENCONTRADA') . "\n\n";

// Buscar el workflow
$workflow = \App\Models\Workflow::where('codigo', 'WF-87A00C09')->first();

if (!$workflow) {
    echo "❌ Workflow no encontrado\n";
    exit;
}

echo "Actualizando workflow: {$workflow->nombre}\n\n";

// Obtener los pasos
$pasos = $workflow->steps()->orderBy('orden')->get();

if ($pasos->count() < 3) {
    echo "❌ El workflow no tiene 3 pasos\n";
    exit;
}

// Actualizar Paso 1: Gerencia de Desarrollo Económico
if ($gerenciaDesarrolloEconomico) {
    $paso1 = $pasos[0];
    $paso1->update([
        'responsable_tipo' => 'gerencia',
        'responsable_id' => $gerenciaDesarrolloEconomico->id
    ]);
    echo "✅ Paso 1 actualizado: {$paso1->nombre}\n";
    echo "   Responsable: Gerencia #{$gerenciaDesarrolloEconomico->id} - {$gerenciaDesarrolloEconomico->nombre}\n\n";
}

// Actualizar Paso 2: Subgerencia de Promoción
if ($subgerenciaPromocion) {
    $paso2 = $pasos[1];
    $paso2->update([
        'responsable_tipo' => 'gerencia',
        'responsable_id' => $subgerenciaPromocion->id
    ]);
    echo "✅ Paso 2 actualizado: {$paso2->nombre}\n";
    echo "   Responsable: Gerencia #{$subgerenciaPromocion->id} - {$subgerenciaPromocion->nombre}\n\n";
}

// Actualizar Paso 3: Subgerencia de Comercio
if ($subgerenciaComercio) {
    $paso3 = $pasos[2];
    $paso3->update([
        'responsable_tipo' => 'gerencia',
        'responsable_id' => $subgerenciaComercio->id
    ]);
    echo "✅ Paso 3 actualizado: {$paso3->nombre}\n";
    echo "   Responsable: Gerencia #{$subgerenciaComercio->id} - {$subgerenciaComercio->nombre}\n\n";
}

echo "\n=== VERIFICAR USUARIOS EN CADA GERENCIA ===\n\n";

foreach ([$gerenciaDesarrolloEconomico, $subgerenciaPromocion, $subgerenciaComercio] as $gerencia) {
    if ($gerencia) {
        $usuarios = \App\Models\User::where('gerencia_id', $gerencia->id)->with('roles')->get();
        echo "📁 {$gerencia->nombre}\n";
        echo "   Usuarios: {$usuarios->count()}\n";
        foreach ($usuarios as $u) {
            echo "   • {$u->name} - Roles: " . $u->roles->pluck('name')->join(', ') . "\n";
        }
        echo "\n";
    }
}

echo "\n✅ Workflow actualizado correctamente\n";
 