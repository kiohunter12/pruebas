<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkflowStep;
use App\Models\Gerencia;
use App\Models\User;

class WorkflowStepsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Creando etapas de flujo por gerencia...');

        $adminUser = User::where('email', 'admin@muni.gob.pe')->first();
        $gerencias = Gerencia::all()->keyBy('nombre');

        $etapasPorGerencia = [
            'Sub Gerencia de Desarrollo Urbano' => [
                [
                    'nombre_etapa' => 'Revisión de documentos',
                    'descripcion' => 'Verificar que la documentación esté completa y cumpla los requisitos',
                    'orden' => 1,
                    'rol_requerido' => 'gerente',
                    'dias_limite' => 3,
                    'requiere_documento' => false
                ],
                [
                    'nombre_etapa' => 'Evaluación técnica',
                    'descripcion' => 'Evaluación técnica de la solicitud por especialista',
                    'orden' => 2,
                    'rol_requerido' => 'gerente',
                    'dias_limite' => 7,
                    'requiere_documento' => true
                ],
                [
                    'nombre_etapa' => 'Aprobación jefe de área',
                    'descripcion' => 'Aprobación final del jefe de la sub gerencia',
                    'orden' => 3,
                    'rol_requerido' => 'jefe_gerencia',
                    'dias_limite' => 2,
                    'requiere_documento' => false
                ]
            ],

            'Sub Gerencia de Licencias y Autorizaciones' => [
                [
                    'nombre_etapa' => 'Verificación de requisitos',
                    'descripcion' => 'Verificar documentos y requisitos establecidos en TUPA',
                    'orden' => 1,
                    'rol_requerido' => 'gerente',
                    'dias_limite' => 2,
                    'requiere_documento' => false
                ],
                [
                    'nombre_etapa' => 'Inspección técnica',
                    'descripcion' => 'Inspección en campo si es requerida',
                    'orden' => 2,
                    'rol_requerido' => 'gerente',
                    'dias_limite' => 5,
                    'requiere_documento' => true,
                    'es_opcional' => true
                ],
                [
                    'nombre_etapa' => 'Emisión de licencia',
                    'descripcion' => 'Emisión de la licencia o autorización correspondiente',
                    'orden' => 3,
                    'rol_requerido' => 'jefe_gerencia',
                    'dias_limite' => 3,
                    'requiere_documento' => true
                ]
            ],

            'Sub Gerencia de Servicios Públicos' => [
                [
                    'nombre_etapa' => 'Evaluación inicial',
                    'descripcion' => 'Evaluación inicial de la solicitud de servicio',
                    'orden' => 1,
                    'rol_requerido' => 'gerente',
                    'dias_limite' => 1,
                    'requiere_documento' => false
                ],
                [
                    'nombre_etapa' => 'Programación de servicio',
                    'descripcion' => 'Programar el servicio solicitado según disponibilidad',
                    'orden' => 2,
                    'rol_requerido' => 'gerente',
                    'dias_limite' => 3,
                    'requiere_documento' => false
                ],
                [
                    'nombre_etapa' => 'Autorización final',
                    'descripcion' => 'Autorización final del jefe de servicios públicos',
                    'orden' => 3,
                    'rol_requerido' => 'jefe_gerencia',
                    'dias_limite' => 1,
                    'requiere_documento' => false
                ]
            ],

            'Sub Gerencia de Registro Civil' => [
                [
                    'nombre_etapa' => 'Verificación de identidad',
                    'descripcion' => 'Verificar identidad del solicitante y documentos',
                    'orden' => 1,
                    'rol_requerido' => 'gerente',
                    'dias_limite' => 1,
                    'requiere_documento' => false
                ],
                [
                    'nombre_etapa' => 'Emisión de documento',
                    'descripcion' => 'Emitir el documento solicitado (certificado, constancia, etc.)',
                    'orden' => 2,
                    'rol_requerido' => 'gerente',
                    'dias_limite' => 2,
                    'requiere_documento' => true
                ]
            ],

            'Sub Gerencia de Rentas' => [
                [
                    'nombre_etapa' => 'Verificación tributaria',
                    'descripcion' => 'Verificar estado tributario del contribuyente',
                    'orden' => 1,
                    'rol_requerido' => 'gerente',
                    'dias_limite' => 2,
                    'requiere_documento' => false
                ],
                [
                    'nombre_etapa' => 'Cálculo de tributos',
                    'descripcion' => 'Calcular impuestos y tasas correspondientes',
                    'orden' => 2,
                    'rol_requerido' => 'gerente',
                    'dias_limite' => 3,
                    'requiere_documento' => true
                ],
                [
                    'nombre_etapa' => 'Aprobación supervisor',
                    'descripcion' => 'Aprobación del supervisor de rentas',
                    'orden' => 3,
                    'rol_requerido' => 'supervisor',
                    'dias_limite' => 2,
                    'requiere_documento' => false
                ]
            ]
        ];

        $totalEtapas = 0;
        foreach ($etapasPorGerencia as $nombreGerencia => $etapas) {
            $gerencia = $gerencias[$nombreGerencia] ?? null;
            if (!$gerencia) {
                continue;
            }

            foreach ($etapas as $etapaData) {
                WorkflowStep::create([
                    'gerencia_id' => $gerencia->id,
                    'nombre_etapa' => $etapaData['nombre_etapa'],
                    'descripcion' => $etapaData['descripcion'],
                    'orden' => $etapaData['orden'],
                    'rol_requerido' => $etapaData['rol_requerido'],
                    'dias_limite' => $etapaData['dias_limite'],
                    'es_opcional' => $etapaData['es_opcional'] ?? false,
                    'requiere_documento' => $etapaData['requiere_documento'],
                    'activa' => true,
                    'created_by' => $adminUser->id ?? null
                ]);
                $totalEtapas++;
            }
        }

        $this->command->info("Etapas de flujo creadas exitosamente");
        $this->command->info("Total de etapas: {$totalEtapas}");
        $this->command->info("Gerencias con flujos configurados: " . count($etapasPorGerencia));
    }
}
