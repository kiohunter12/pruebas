<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== CREAR NUEVO EXPEDIENTE DE PRUEBA ===\n\n";

// Buscar el workflow
$workflow = \App\Models\Workflow::where('codigo', 'WF-D8915870')->first();

if (!$workflow) {
    echo "❌ Workflow no encontrado\n";
    exit;
}

// Buscar un ciudadano
$ciudadano = \App\Models\User::whereHas('roles', function($query) {
    $query->where('name', 'ciudadano');
})->first();

if (!$ciudadano) {
    echo "❌ No hay ciudadanos en el sistema\n";
    exit;
}

// Crear expediente
$expediente = \App\Models\Expediente::create([
    'numero' => 'EXP-2025-' . str_pad(rand(100, 999), 6, '0', STR_PAD_LEFT),
    'tipo_tramite' => 'Licencia de Funcionamiento',
    'asunto' => 'Solicitud de licencia para restaurante',
    'descripcion' => 'Solicito licencia de funcionamiento para restaurante ubicado en Av. Principal 456',
    'estado' => 'en_proceso',
    'prioridad' => 'normal',
    'workflow_id' => $workflow->id,
    'gerencia_id' => 8, // Gerencia de Desarrollo Económico Local
    'usuario_id' => $ciudadano->id,
    'fecha_inicio' => now(),
]);

echo "✅ Expediente creado: {$expediente->numero}\n";
echo "  Ciudadano: {$ciudadano->name}\n";
echo "  Workflow: {$workflow->nombre}\n\n";

// Obtener el primer paso
$primerPaso = $workflow->steps->sortBy('orden')->first();

// Determinar responsable
$responsableId = null;
if ($primerPaso->responsable_tipo === 'gerencia' && $primerPaso->responsable_id) {
    $gerencia = \App\Models\Gerencia::find($primerPaso->responsable_id);
    if ($gerencia) {
        $usuario = \App\Models\User::where('gerencia_id', $gerencia->id)
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['gerente', 'subgerente', 'jefe_gerencia']);
            })
            ->first();
        
        $responsableId = $usuario ? $usuario->id : null;
    }
}

// Actualizar current_step_id
$expediente->current_step_id = $primerPaso->id;
$expediente->save();

// Crear progreso inicial
$progreso = \App\Models\ExpedienteWorkflowProgress::create([
    'expediente_id' => $expediente->id,
    'workflow_step_id' => $primerPaso->id,
    'estado' => 'en_proceso',
    'fecha_inicio' => now(),
    'fecha_limite' => now()->addDays(3),
    'asignado_a' => $responsableId,
]);

echo "✅ Progreso creado\n";
echo "  Paso: {$primerPaso->nombre}\n";
echo "  Asignado a: " . ($responsableId ? \App\Models\User::find($responsableId)->name : 'NULL') . " (ID: {$responsableId})\n\n";

echo "✅ LISTO PARA PROBAR\n\n";
echo "1. Inicia sesión como María González (jefe.economico@muni.gob.pe / economico123)\n";
echo "2. Ve a 'Mis Asignados' y verás el expediente {$expediente->numero}\n";
echo "3. Aprueba el expediente\n";
echo "4. Inicia sesión como Laura Ruiz (laura.obrasprivadas@muni.gob.pe / obras123)\n";
echo "5. Ve a 'Mis Asignados' y deberías ver el expediente\n";
echo "6. Aprueba el expediente\n";
echo "7. Inicia sesión como Ricardo Morales (ricardo.seguridad@muni.gob.pe / seguridad123)\n";
echo "8. Ve a 'Mis Asignados' y deberías ver el expediente\n";
