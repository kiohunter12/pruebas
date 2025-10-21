<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Procedure;

class ProceduresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $procedures = [
            // Procedimientos de Licencias y Autorizaciones
            [
                'tupa_code' => 'LF-001',
                'name' => 'Licencia de Funcionamiento para Establecimientos Comerciales',
                'description' => 'Autorización municipal para el funcionamiento de establecimientos comerciales hasta 100 m²',
                'department' => 'Sub Gerencia de Licencias y Autorizaciones',
                'max_days' => 15,
                'fee' => 85.50,
                'is_active' => true,
                'silence_type' => 'positive',
                'requirements' => [
                    'Solicitud dirigida al alcalde',
                    'Copia simple del RUC',
                    'Copia simple del DNI del representante legal',
                    'Vigencia de poder del representante legal',
                    'Declaración jurada de observancia de condiciones de seguridad',
                    'Copia simple de la autorización sectorial respectiva'
                ]
            ],
            [
                'tupa_code' => 'LF-002',
                'name' => 'Licencia de Funcionamiento para Establecimientos Industriales',
                'description' => 'Autorización municipal para el funcionamiento de establecimientos industriales',
                'department' => 'Sub Gerencia de Licencias y Autorizaciones',
                'max_days' => 30,
                'fee' => 150.00,
                'is_active' => true,
                'silence_type' => 'negative',
                'requirements' => [
                    'Solicitud dirigida al alcalde',
                    'Copia simple del RUC',
                    'Copia simple del DNI del representante legal',
                    'Vigencia de poder del representante legal',
                    'Declaración de impacto ambiental',
                    'Plan de seguridad industrial',
                    'Autorización sectorial correspondiente'
                ]
            ],
            [
                'tupa_code' => 'LE-001',
                'name' => 'Licencia de Edificación para Vivienda Unifamiliar',
                'description' => 'Autorización para construir vivienda unifamiliar hasta 120 m²',
                'department' => 'Sub Gerencia de Desarrollo Urbano',
                'max_days' => 20,
                'fee' => 125.00,
                'is_active' => true,
                'silence_type' => 'negative',
                'requirements' => [
                    'Solicitud dirigida al alcalde',
                    'Planos arquitectónicos firmados por arquitecto colegiado',
                    'Planos de estructuras firmados por ingeniero civil colegiado',
                    'Memoria descriptiva',
                    'Certificado de parámetros urbanísticos',
                    'Copia del título de propiedad'
                ]
            ],
            
            // Procedimientos de Servicios Públicos
            [
                'tupa_code' => 'SP-001',
                'name' => 'Autorización para Ocupación de Vía Pública',
                'description' => 'Permiso temporal para ocupar espacios de la vía pública',
                'department' => 'Sub Gerencia de Servicios Públicos',
                'max_days' => 10,
                'fee' => 45.00,
                'is_active' => true,
                'silence_type' => 'negative',
                'requirements' => [
                    'Solicitud dirigida al alcalde',
                    'Croquis de ubicación',
                    'Declaración jurada de responsabilidad',
                    'Póliza de seguro de responsabilidad civil',
                    'Comprobante de pago de derechos'
                ]
            ],
            [
                'tupa_code' => 'SP-002',
                'name' => 'Autorización para Poda de Árboles',
                'description' => 'Permiso para poda o tala de árboles en espacios públicos',
                'department' => 'Sub Gerencia de Medio Ambiente',
                'max_days' => 7,
                'fee' => 25.00,
                'is_active' => true,
                'silence_type' => 'negative',
                'requirements' => [
                    'Solicitud dirigida al alcalde',
                    'Informe técnico justificatorio',
                    'Fotografías del árbol',
                    'Compromiso de reposición (si aplica)'
                ]
            ],
            
            // Procedimientos de Rentas
            [
                'tupa_code' => 'TR-001',
                'name' => 'Fraccionamiento de Deuda Tributaria',
                'description' => 'Solicitud de fraccionamiento de deudas por impuestos municipales',
                'department' => 'Sub Gerencia de Rentas',
                'max_days' => 15,
                'fee' => 35.00,
                'is_active' => true,
                'silence_type' => 'negative',
                'requirements' => [
                    'Solicitud dirigida al alcalde',
                    'Estado de cuenta de deuda tributaria',
                    'Declaración jurada de ingresos',
                    'Propuesta de cronograma de pagos',
                    'Garantía (si aplica)'
                ]
            ],
            
            // Procedimientos de Registro Civil
            [
                'tupa_code' => 'RC-001',
                'name' => 'Inscripción de Nacimiento',
                'description' => 'Registro de nacimiento en el registro civil municipal',
                'department' => 'Sub Gerencia de Registro Civil',
                'max_days' => 3,
                'fee' => 15.00,
                'is_active' => true,
                'silence_type' => 'positive',
                'requirements' => [
                    'Certificado de nacido vivo',
                    'DNI de los padres',
                    'Partida de matrimonio de los padres (si aplica)',
                    'Declaración jurada de domicilio'
                ]
            ],
            [
                'tupa_code' => 'RC-002',
                'name' => 'Certificado de Soltería',
                'description' => 'Expedición de certificado de estado civil soltero',
                'department' => 'Sub Gerencia de Registro Civil',
                'max_days' => 1,
                'fee' => 12.00,
                'is_active' => true,
                'silence_type' => 'positive',
                'requirements' => [
                    'Solicitud dirigida al alcalde',
                    'Copia del DNI',
                    'Declaración jurada de estado civil'
                ]
            ],
            
            // Procedimientos de Transportes
            [
                'tupa_code' => 'TR-002',
                'name' => 'Autorización de Transporte Escolar',
                'description' => 'Permiso para prestación de servicio de transporte escolar',
                'department' => 'Sub Gerencia de Transportes',
                'max_days' => 20,
                'fee' => 95.00,
                'is_active' => true,
                'silence_type' => 'negative',
                'requirements' => [
                    'Solicitud dirigida al alcalde',
                    'Licencia de conducir A-IIa vigente',
                    'Certificado médico del conductor',
                    'Tarjeta de propiedad vehicular',
                    'SOAT vigente',
                    'Certificado de inspección técnica vehicular',
                    'Antecedentes policiales y penales del conductor'
                ]
            ],
            
            // Procedimientos de Seguridad Ciudadana
            [
                'tupa_code' => 'SC-001',
                'name' => 'Autorización para Eventos Públicos',
                'description' => 'Permiso para realización de eventos públicos masivos',
                'department' => 'Sub Gerencia de Seguridad Ciudadana',
                'max_days' => 15,
                'fee' => 75.00,
                'is_active' => true,
                'silence_type' => 'negative',
                'requirements' => [
                    'Solicitud dirigida al alcalde',
                    'Plan de contingencia y seguridad',
                    'Póliza de seguros',
                    'Autorización de PNP',
                    'Plan de manejo de residuos',
                    'Comprobante de pago de derechos'
                ]
            ],
            
            // Procedimientos de Fiscalización
            [
                'tupa_code' => 'FS-001',
                'name' => 'Recurso de Reconsideración contra Multa',
                'description' => 'Impugnación de papeleta de infracción por incumplimiento de ordenanzas',
                'department' => 'Sub Gerencia de Fiscalización',
                'max_days' => 30,
                'fee' => 0.00,
                'is_active' => true,
                'silence_type' => 'negative',
                'requirements' => [
                    'Recurso dirigido al alcalde',
                    'Copia de la papeleta de infracción',
                    'Medios probatorios que sustenten el recurso',
                    'Copia del DNI del recurrente'
                ]
            ]
        ];

        foreach ($procedures as $procedureData) {
            Procedure::updateOrCreate(
                ['tupa_code' => $procedureData['tupa_code']], // Buscar por código TUPA
                $procedureData // Actualizar o crear con estos datos
            );
        }

        $this->command->info('Procedimientos TUPA creados exitosamente');
        $this->command->info('Total de procedimientos: ' . Procedure::count());
        $this->command->info('Procedimientos de diferentes gerencias configurados');
    }
}
