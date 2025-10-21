<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoTramite;
use App\Models\Workflow;

class AsignarWorkflowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asignar workflows a tipos de trámite basándose en su gerencia
        $tiposTramite = TipoTramite::whereNull('workflow_id')->get();
        
        foreach ($tiposTramite as $tipo) {
            if ($tipo->gerencia_id) {
                // Buscar workflow de la misma gerencia
                $workflow = Workflow::where('gerencia_id', $tipo->gerencia_id)
                    ->where('activo', true)
                    ->first();
                
                if (!$workflow) {
                    // Si no hay workflow específico de la gerencia, usar el primero activo
                    $workflow = Workflow::where('activo', true)->first();
                }
                
                if ($workflow) {
                    $tipo->workflow_id = $workflow->id;
                    $tipo->save();
                    $this->command->info("Asignado workflow '{$workflow->nombre}' al tipo de trámite '{$tipo->nombre}'");
                }
            }
        }
        
        $this->command->info('Workflows asignados correctamente!');
    }
}
