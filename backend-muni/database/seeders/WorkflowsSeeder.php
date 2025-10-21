<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use App\Models\WorkflowTransition;
use App\Models\Gerencia;
use App\Models\User;

class WorkflowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Creando workflows completos para cada gerencia...');

        $adminUser = User::where('email', 'admin@muni.gob.pe')->first();
        if (!$adminUser) {
            $adminUser = User::first();
        }

        $gerencias = Gerencia::all();

        foreach ($gerencias as $gerencia) {
            $this->command->info("📋 Creando workflow para: {$gerencia->nombre}");
            
            // Crear workflow principal
            $workflow = Workflow::firstOrCreate(
                [
                    'codigo' => 'WF-' . strtoupper(str_replace(' ', '-', $gerencia->nombre))
                ],
                [
                    'nombre' => 'Flujo de ' . $gerencia->nombre,
                    'descripcion' => 'Workflow estándar para trámites de ' . $gerencia->nombre,
                    'tipo' => 'expediente',
                    'activo' => true,
                    'gerencia_id' => $gerencia->id,
                    'created_by' => $adminUser->id ?? 1
                ]
            );

            // Definir pasos según el tipo de gerencia
            $pasos = $this->getPasosParaGerencia($gerencia->nombre);

            $stepsCreated = [];
            foreach ($pasos as $index => $paso) {
                $step = WorkflowStep::firstOrCreate(
                    [
                        'workflow_id' => $workflow->id,
                        'codigo' => $workflow->codigo . '-PASO-' . ($index + 1)
                    ],
                    [
                        'nombre' => $paso['nombre'],
                        'descripcion' => $paso['descripcion'],
                        'orden' => $index + 1,
                        'tipo' => $paso['tipo'] ?? 'normal',
                        'requiere_aprobacion' => $paso['requiere_aprobacion'] ?? false,
                        'tiempo_estimado' => $paso['tiempo_estimado'] ?? 1440, // 1 día por defecto
                        'responsable_tipo' => 'gerencia',
                        'responsable_id' => $gerencia->id,
                        'activo' => true
                    ]
                );

                $stepsCreated[] = $step;
                $orden = $index + 1;
                $this->command->info("  ✓ Paso {$orden}: {$paso['nombre']}");
            }

            // Crear transiciones entre pasos consecutivos
            for ($i = 0; $i < count($stepsCreated) - 1; $i++) {
                WorkflowTransition::firstOrCreate(
                    [
                        'workflow_id' => $workflow->id,
                        'from_step_id' => $stepsCreated[$i]->id,
                        'to_step_id' => $stepsCreated[$i + 1]->id
                    ],
                    [
                        'nombre' => 'De ' . $stepsCreated[$i]->nombre . ' a ' . $stepsCreated[$i + 1]->nombre,
                        'condicion' => null,
                        'activo' => true
                    ]
                );
            }

            $this->command->info("  ✓ Workflow '{$workflow->nombre}' creado con " . count($stepsCreated) . " pasos");
        }

        $this->command->info('✅ Workflows creados exitosamente!');
    }

    /**
     * Obtener pasos específicos para cada tipo de gerencia
     */
    private function getPasosParaGerencia(string $gerenciaNombre): array
    {
        // Pasos genéricos que aplican a todas las gerencias
        $pasosGenericos = [
            [
                'nombre' => 'Recepción y Registro',
                'descripcion' => 'Recepción del trámite y registro inicial',
                'tipo' => 'inicio',
                'requiere_aprobacion' => false,
                'tiempo_estimado' => 60 // 1 hora
            ],
            [
                'nombre' => 'Revisión de Documentos',
                'descripcion' => 'Verificar que la documentación esté completa',
                'tipo' => 'normal',
                'requiere_aprobacion' => false,
                'tiempo_estimado' => 1440 // 1 día
            ],
            [
                'nombre' => 'Evaluación Técnica',
                'descripcion' => 'Evaluación técnica por especialista',
                'tipo' => 'normal',
                'requiere_aprobacion' => true,
                'tiempo_estimado' => 4320 // 3 días
            ],
            [
                'nombre' => 'Aprobación',
                'descripcion' => 'Aprobación final del responsable',
                'tipo' => 'normal',
                'requiere_aprobacion' => true,
                'tiempo_estimado' => 1440 // 1 día
            ],
            [
                'nombre' => 'Notificación',
                'descripcion' => 'Notificar al ciudadano sobre la resolución',
                'tipo' => 'fin',
                'requiere_aprobacion' => false,
                'tiempo_estimado' => 720 // 12 horas
            ]
        ];

        // Pasos específicos según el tipo de gerencia
        if (str_contains(strtolower($gerenciaNombre), 'licencia') || 
            str_contains(strtolower($gerenciaNombre), 'urbanis')) {
            return [
                [
                    'nombre' => 'Recepción y Registro',
                    'descripcion' => 'Recepción del trámite y registro inicial',
                    'tipo' => 'inicio',
                    'tiempo_estimado' => 60
                ],
                [
                    'nombre' => 'Verificación de Requisitos',
                    'descripcion' => 'Verificar documentos según TUPA',
                    'tipo' => 'normal',
                    'tiempo_estimado' => 1440
                ],
                [
                    'nombre' => 'Inspección Técnica',
                    'descripcion' => 'Inspección en campo si es requerida',
                    'tipo' => 'normal',
                    'requiere_aprobacion' => true,
                    'tiempo_estimado' => 4320
                ],
                [
                    'nombre' => 'Evaluación Legal',
                    'descripcion' => 'Revisión de aspectos legales',
                    'tipo' => 'normal',
                    'requiere_aprobacion' => true,
                    'tiempo_estimado' => 2880
                ],
                [
                    'nombre' => 'Emisión de Licencia',
                    'descripcion' => 'Emisión de la licencia o autorización',
                    'tipo' => 'normal',
                    'requiere_aprobacion' => true,
                    'tiempo_estimado' => 1440
                ],
                [
                    'nombre' => 'Entrega al Ciudadano',
                    'descripcion' => 'Entrega de la licencia al solicitante',
                    'tipo' => 'fin',
                    'tiempo_estimado' => 720
                ]
            ];
        }

        if (str_contains(strtolower($gerenciaNombre), 'servicio')) {
            return [
                [
                    'nombre' => 'Recepción de Solicitud',
                    'descripcion' => 'Recepción y registro de la solicitud',
                    'tipo' => 'inicio',
                    'tiempo_estimado' => 60
                ],
                [
                    'nombre' => 'Evaluación Inicial',
                    'descripcion' => 'Evaluación inicial de viabilidad',
                    'tipo' => 'normal',
                    'tiempo_estimado' => 720
                ],
                [
                    'nombre' => 'Programación de Servicio',
                    'descripcion' => 'Programar el servicio solicitado',
                    'tipo' => 'normal',
                    'tiempo_estimado' => 2880
                ],
                [
                    'nombre' => 'Autorización',
                    'descripcion' => 'Autorización del jefe de servicios',
                    'tipo' => 'normal',
                    'requiere_aprobacion' => true,
                    'tiempo_estimado' => 1440
                ],
                [
                    'nombre' => 'Notificación',
                    'descripcion' => 'Notificar al ciudadano',
                    'tipo' => 'fin',
                    'tiempo_estimado' => 360
                ]
            ];
        }

        // Retornar pasos genéricos para otras gerencias
        return $pasosGenericos;
    }
}
