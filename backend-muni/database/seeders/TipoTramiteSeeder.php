<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoTramite;
use App\Models\TipoDocumento;
use App\Models\Gerencia;

class TipoTramiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener solo gerencias principales (no subgerencias)
        $gerenciasPrincipales = Gerencia::whereNull('gerencia_padre_id')->get()->keyBy('nombre');
        $documentos = TipoDocumento::all()->keyBy('codigo');

        // Obtener solo gerencias principales (no subgerencias)
        $gerenciasPrincipales = Gerencia::whereNull('gerencia_padre_id')->get()->keyBy('nombre');
        
        $tipos = [
            [
                'nombre' => 'Licencia de Funcionamiento',
                'descripcion' => 'Autorización para el funcionamiento de establecimientos comerciales',
                'codigo' => 'LF-001',
                'gerencia_id' => $gerenciasPrincipales['Gerencia de Desarrollo Económico Local']->id,
                'documentos' => ['SOL-001', 'RUC-001', 'DJ-001', 'DNI-001'], // códigos de TipoDocumento
                'costo' => 125.50,
                'tiempo_estimado_dias' => 15,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Licencia de Edificación',
                'descripcion' => 'Autorización para construcción de edificaciones',
                'codigo' => 'LE-001',
                'gerencia_id' => $gerenciasPrincipales['Gerencia de Desarrollo Urbano y Rural']->id,
                'documentos' => ['SOL-001', 'PLA-001', 'TP-001', 'CP-001', 'DNI-001'],
                'costo' => 450.00,
                'tiempo_estimado_dias' => 30,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Certificado de Numeración',
                'descripcion' => 'Asignación de numeración predial',
                'codigo' => 'CN-001',
                'gerencia_id' => $gerenciasPrincipales['Gerencia de Desarrollo Urbano y Rural']->id,
                'documentos' => ['SOL-001', 'DNI-001', 'TP-001'],
                'costo' => 35.00,
                'tiempo_estimado_dias' => 7,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Matrimonio Civil',
                'descripcion' => 'Ceremonia de matrimonio civil',
                'codigo' => 'MC-001',
                'gerencia_id' => $gerenciasPrincipales['Gerencia de Desarrollo Social']->id,
                'documentos' => ['SOL-001', 'DNI-001', 'DJ-001'],
                'costo' => 85.00,
                'tiempo_estimado_dias' => 15,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Inscripción de Defunción',
                'descripcion' => 'Registro de defunción en el registro civil',
                'codigo' => 'ID-001',
                'gerencia_id' => $gerenciasPrincipales['Gerencia de Desarrollo Social']->id,
                'documentos' => ['DNI-001'],
                'costo' => 25.00,
                'tiempo_estimado_dias' => 3,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Constancia de Posesión',
                'descripcion' => 'Constancia de posesión de terreno',
                'codigo' => 'CP-001',
                'gerencia_id' => $gerenciasPrincipales['Gerencia de Desarrollo Urbano y Rural']->id,
                'documentos' => ['SOL-001', 'DJ-001', 'DNI-001'],
                'costo' => 75.00,
                'tiempo_estimado_dias' => 20,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Licencia de Obra Menor',
                'descripcion' => 'Autorización para obras menores',
                'codigo' => 'LOM-001',
                'gerencia_id' => $gerenciasPrincipales['Gerencia de Desarrollo Urbano y Rural']->id,
                'documentos' => ['SOL-001', 'PLA-001', 'DNI-001'],
                'costo' => 180.00,
                'tiempo_estimado_dias' => 10,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Certificado de Seguridad',
                'descripcion' => 'Certificado de seguridad en defensa civil',
                'codigo' => 'CS-001',
                'gerencia_id' => $gerenciasPrincipales['Gerencia de Seguridad Ciudadana y Defensa Civil']->id,
                'documentos' => ['SOL-001', 'PLA-001', 'RUC-001'],
                'costo' => 95.00,
                'tiempo_estimado_dias' => 12,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Permiso de Obra en Vía Pública',
                'descripcion' => 'Permiso para ejecutar trabajos en la vía pública',
                'codigo' => 'POVP-001',
                'gerencia_id' => $gerenciasPrincipales['Gerencia de Desarrollo Urbano y Rural']->id,
                'documentos' => ['SOL-001', 'PLA-001', 'MEM-001', 'RUC-001'],
                'costo' => 220.00,
                'tiempo_estimado_dias' => 8,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Registro de Vendedor Ambulante',
                'descripcion' => 'Registro municipal para vendedores ambulantes',
                'codigo' => 'RVA-001',
                'gerencia_id' => $gerenciasPrincipales['Gerencia de Desarrollo Económico Local']->id,
                'documentos' => ['SOL-001', 'DNI-001', 'DJ-001'],
                'costo' => 45.00,
                'tiempo_estimado_dias' => 5,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Certificado de Compatibilidad de Uso',
                'descripcion' => 'Certificado de compatibilidad de uso de suelo',
                'codigo' => 'CCU-001',
                'gerencia_id' => $gerenciasPrincipales['Gerencia de Desarrollo Urbano y Rural']->id,
                'documentos' => ['SOL-001', 'MEM-001', 'TP-001', 'DNI-001'],
                'costo' => 120.00,
                'tiempo_estimado_dias' => 10,
                'requiere_pago' => true,
                'activo' => true
            ]
        ];

        foreach ($tipos as $tipo) {
            // Extraer códigos de documentos antes de crear
            $documentosCodigos = $tipo['documentos'] ?? [];
            unset($tipo['documentos']);
            
            // Crear o actualizar el tipo de trámite
            $tipoTramite = TipoTramite::updateOrCreate(
                ['codigo' => $tipo['codigo']],
                $tipo
            );

            // Sincronizar documentos requeridos via pivot
            $documentosIds = [];
            foreach ($documentosCodigos as $codigo) {
                if (isset($documentos[$codigo])) {
                    $documentosIds[] = $documentos[$codigo]->id;
                }
            }
            
            if (!empty($documentosIds)) {
                $tipoTramite->documentos()->sync($documentosIds);
            }
        }

        $this->command?->info('TipoTramiteSeeder: ' . count($tipos) . ' tipos de trámite creados/actualizados con documentos requeridos asociados.');
    }
}
