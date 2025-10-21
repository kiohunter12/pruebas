<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoDocumento;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'nombre' => 'Solicitud',
                'descripcion' => 'Documento de solicitud general',
                'codigo' => 'SOL-001',
                'campos_requeridos' => ['remitente', 'asunto', 'fundamento'],
                'requiere_firma' => true,
                'vigencia_dias' => null,
                'activo' => true
            ],
            [
                'nombre' => 'Memorial',
                'descripcion' => 'Memorial dirigido a autoridad',
                'codigo' => 'MEM-001',
                'campos_requeridos' => ['remitente', 'asunto'],
                'requiere_firma' => true,
                'vigencia_dias' => null,
                'activo' => true
            ],
            [
                'nombre' => 'Declaración Jurada',
                'descripcion' => 'Declaración jurada',
                'codigo' => 'DJ-001',
                'campos_requeridos' => ['remitente', 'declaracion'],
                'requiere_firma' => true,
                'vigencia_dias' => 365,
                'activo' => true
            ],
            [
                'nombre' => 'Copia de DNI',
                'descripcion' => 'Copia de Documento Nacional de Identidad',
                'codigo' => 'DNI-001',
                'campos_requeridos' => [],
                'requiere_firma' => false,
                'vigencia_dias' => 90,
                'activo' => true
            ],
            [
                'nombre' => 'Copia de RUC',
                'descripcion' => 'Copia de Registro Único de Contribuyentes',
                'codigo' => 'RUC-001',
                'campos_requeridos' => [],
                'requiere_firma' => false,
                'vigencia_dias' => 180,
                'activo' => true
            ],
            [
                'nombre' => 'Planos',
                'descripcion' => 'Planos técnicos del proyecto',
                'codigo' => 'PLA-001',
                'campos_requeridos' => ['escala', 'fecha_elaboracion'],
                'requiere_firma' => true,
                'vigencia_dias' => 365,
                'activo' => true
            ],
            [
                'nombre' => 'Certificado de Parámetros',
                'descripcion' => 'Certificado de parámetros urbanísticos',
                'codigo' => 'CP-001',
                'campos_requeridos' => [],
                'requiere_firma' => false,
                'vigencia_dias' => 180,
                'activo' => true
            ],
            [
                'nombre' => 'Título de Propiedad',
                'descripcion' => 'Título de propiedad del inmueble',
                'codigo' => 'TP-001',
                'campos_requeridos' => ['numero_partida'],
                'requiere_firma' => false,
                'vigencia_dias' => null,
                'activo' => true
            ]
        ];

        foreach ($tipos as $tipo) {
            TipoDocumento::updateOrCreate(
                ['codigo' => $tipo['codigo']],
                $tipo
            );
        }

        $this->command?->info('TipoDocumentoSeeder: upsert completado (' . count($tipos) . ' registros).');
    }
}
