<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkflowRule;
use App\Models\Gerencia;
use App\Models\User;

class WorkflowRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Creando reglas de flujo de trabajo automático...');

        // Obtener admin como creador de reglas
        $adminUser = User::where('email', 'admin@muni.gob.pe')->first();
        
        // Obtener gerencias
        $gerencias = Gerencia::all()->keyBy('nombre');

        $reglas = [
            // Reglas para Sub Gerencia de Desarrollo Urbano
            [
                'nombre_regla' => 'Licencias de Construcción',
                'tipo_tramite' => 'licencia_construccion',
                'palabra_clave' => 'licencia',
                'gerencia_destino' => $gerencias['Sub Gerencia de Desarrollo Urbano'] ?? null,
                'prioridad' => 100,
                'descripcion' => 'Deriva automáticamente todas las solicitudes de licencias de construcción a Desarrollo Urbano'
            ],
            [
                'nombre_regla' => 'Certificados de Parámetros',
                'tipo_tramite' => 'certificado_parametros',
                'palabra_clave' => 'parámetros',
                'gerencia_destino' => $gerencias['Sub Gerencia de Desarrollo Urbano'] ?? null,
                'prioridad' => 95,
                'descripcion' => 'Deriva certificados de parámetros urbanísticos a Desarrollo Urbano'
            ],

            // Reglas para Sub Gerencia de Licencias y Autorizaciones
            [
                'nombre_regla' => 'Licencias de Funcionamiento',
                'tipo_tramite' => 'licencia_funcionamiento',
                'palabra_clave' => 'funcionamiento',
                'gerencia_destino' => $gerencias['Sub Gerencia de Licencias y Autorizaciones'] ?? null,
                'prioridad' => 100,
                'descripcion' => 'Deriva licencias de funcionamiento comercial a Licencias y Autorizaciones'
            ],
            [
                'nombre_regla' => 'Permisos Comerciales',
                'tipo_tramite' => 'permiso_comercial',
                'palabra_clave' => 'comercial',
                'gerencia_destino' => $gerencias['Sub Gerencia de Licencias y Autorizaciones'] ?? null,
                'prioridad' => 90,
                'descripcion' => 'Deriva permisos para actividades comerciales'
            ],

            // Reglas para Sub Gerencia de Servicios Públicos
            [
                'nombre_regla' => 'Servicios de Agua y Saneamiento',
                'tipo_tramite' => 'servicio_agua',
                'palabra_clave' => 'agua|saneamiento|desagüe',
                'gerencia_destino' => $gerencias['Sub Gerencia de Servicios Públicos'] ?? null,
                'prioridad' => 100,
                'descripcion' => 'Deriva solicitudes relacionadas con agua potable y saneamiento'
            ],
            [
                'nombre_regla' => 'Limpieza Pública',
                'tipo_tramite' => 'limpieza_publica',
                'palabra_clave' => 'limpieza|residuos|basura',
                'gerencia_destino' => $gerencias['Sub Gerencia de Servicios Públicos'] ?? null,
                'prioridad' => 85,
                'descripcion' => 'Deriva servicios de limpieza pública y manejo de residuos'
            ],

            // Reglas para Sub Gerencia de Registro Civil
            [
                'nombre_regla' => 'Documentos de Registro Civil',
                'tipo_tramite' => 'documento_oficial',
                'palabra_clave' => 'certificado|constancia|copia|nacimiento|defunción',
                'gerencia_destino' => $gerencias['Sub Gerencia de Registro Civil'] ?? null,
                'prioridad' => 90,
                'descripcion' => 'Deriva solicitudes de documentos de registro civil'
            ],

            // Reglas para Sub Gerencia de Recursos Humanos
            [
                'nombre_regla' => 'Recursos Humanos',
                'tipo_tramite' => 'recursos_humanos',
                'palabra_clave' => 'personal|trabajador|empleado',
                'gerencia_destino' => $gerencias['Sub Gerencia de Recursos Humanos'] ?? null,
                'prioridad' => 80,
                'descripcion' => 'Deriva asuntos relacionados con personal municipal'
            ],

            // Reglas para Sub Gerencia de Medio Ambiente
            [
                'nombre_regla' => 'Evaluación Ambiental',
                'tipo_tramite' => 'evaluacion_ambiental',
                'palabra_clave' => 'ambiental|medio ambiente|impacto',
                'gerencia_destino' => $gerencias['Sub Gerencia de Medio Ambiente'] ?? null,
                'prioridad' => 95,
                'descripcion' => 'Deriva evaluaciones de impacto ambiental'
            ],

            // Reglas para Sub Gerencia de Rentas
            [
                'nombre_regla' => 'Trámites Tributarios',
                'tipo_tramite' => 'tramite_tributario',
                'palabra_clave' => 'impuesto|tributo|renta|catastro',
                'gerencia_destino' => $gerencias['Sub Gerencia de Rentas'] ?? null,
                'prioridad' => 85,
                'descripcion' => 'Deriva trámites relacionados con impuestos y rentas'
            ],

            // Reglas para Sub Gerencia de Transportes
            [
                'nombre_regla' => 'Permisos de Transporte',
                'tipo_tramite' => 'permiso_transporte',
                'palabra_clave' => 'transporte|vehículo|taxi|mototaxi',
                'gerencia_destino' => $gerencias['Sub Gerencia de Transportes'] ?? null,
                'prioridad' => 80,
                'descripcion' => 'Deriva permisos y autorizaciones de transporte'
            ],

            // Regla genérica de fallback
            [
                'nombre_regla' => 'Asignación General',
                'tipo_tramite' => 'general',
                'palabra_clave' => null,
                'gerencia_destino' => $gerencias['Gerencia Municipal'] ?? null,
                'prioridad' => 1,
                'descripcion' => 'Regla por defecto para trámites que no coinciden con otras reglas específicas'
            ]
        ];

        $createdCount = 0;
        foreach ($reglas as $reglaData) {
            if ($reglaData['gerencia_destino']) {
                WorkflowRule::create([
                    'nombre_regla' => $reglaData['nombre_regla'],
                    'tipo_tramite' => $reglaData['tipo_tramite'],
                    'palabra_clave' => $reglaData['palabra_clave'],
                    'gerencia_destino_id' => $reglaData['gerencia_destino']->id,
                    'prioridad' => $reglaData['prioridad'],
                    'activa' => true,
                    'descripcion' => $reglaData['descripcion'],
                    'created_by' => $adminUser->id ?? null
                ]);
                $createdCount++;
            }
        }

        $this->command->info("Reglas de flujo creadas exitosamente");
        $this->command->info("Total de reglas: {$createdCount}");
        $this->command->info("Sistema de asignación automática configurado");
    }
}
