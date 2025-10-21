<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gerencia;

class GerenciasSeeder extends Seeder
{
    public function run(): void
    {
        // Estructura organizacional completa de gerencias municipales
        $gerenciasEstructura = [
            // 1. GERENCIA DE DESARROLLO URBANO Y RURAL
            [
                'nombre' => 'Gerencia de Desarrollo Urbano y Rural',
                'descripcion' => 'Encargada del ordenamiento territorial, catastro y licencias',
                'codigo' => 'GDUR',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Subgerencia de Planeamiento Urbano y Rural',
                        'descripcion' => 'Planeamiento y zonificación territorial',
                        'codigo' => 'SGPUR'
                    ],
                    [
                        'nombre' => 'Subgerencia de Obras Privadas y Licencias de Construcción',
                        'descripcion' => 'Licencias de edificación y construcción',
                        'codigo' => 'SGOPLC'
                    ],
                    [
                        'nombre' => 'Subgerencia de Catastro',
                        'descripcion' => 'Registro y control catastral',
                        'codigo' => 'SGCAT'
                    ],
                    [
                        'nombre' => 'Subgerencia de Habilitaciones Urbanas',
                        'descripcion' => 'Aprobación de habilitaciones urbanas',
                        'codigo' => 'SGHU'
                    ],
                    [
                        'nombre' => 'Subgerencia de Control Urbano y Supervisión de Obras',
                        'descripcion' => 'Control y supervisión de obras',
                        'codigo' => 'SGCUSO'
                    ],
                    [
                        'nombre' => 'Oficina de Ordenamiento Territorial',
                        'descripcion' => 'Ordenamiento del territorio municipal',
                        'codigo' => 'OOT'
                    ]
                ]
            ],
            
            // 2. GERENCIA DE DESARROLLO ECONÓMICO LOCAL
            [
                'nombre' => 'Gerencia de Desarrollo Económico Local',
                'descripcion' => 'Promueve la actividad empresarial, ferias, turismo y empleo',
                'codigo' => 'GDEL',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Subgerencia de Promoción Empresarial y MYPE',
                        'descripcion' => 'Promoción de micro y pequeña empresa',
                        'codigo' => 'SGPEM'
                    ],
                    [
                        'nombre' => 'Subgerencia de Comercio, Industria y Turismo',
                        'descripcion' => 'Desarrollo comercial, industrial y turístico',
                        'codigo' => 'SGCIT'
                    ],
                    [
                        'nombre' => 'Subgerencia de Mercados y Abastos',
                        'descripcion' => 'Gestión de mercados municipales',
                        'codigo' => 'SGMA'
                    ],
                    [
                        'nombre' => 'Subgerencia de Promoción del Empleo y Capacitación Laboral',
                        'descripcion' => 'Promoción de empleo y formación laboral',
                        'codigo' => 'SGPECL'
                    ],
                    [
                        'nombre' => 'Oficina de Desarrollo Agrario',
                        'descripcion' => 'Desarrollo agrícola en zonas rurales',
                        'codigo' => 'ODA'
                    ]
                ]
            ],
            
            // 3. GERENCIA DE DESARROLLO SOCIAL
            [
                'nombre' => 'Gerencia de Desarrollo Social',
                'descripcion' => 'Programas sociales, educación, cultura y salud comunitaria',
                'codigo' => 'GDS',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Subgerencia de Programas Sociales',
                        'descripcion' => 'Vaso de Leche, Comedores Populares',
                        'codigo' => 'SGPS'
                    ],
                    [
                        'nombre' => 'Subgerencia de Educación, Cultura, Deporte y Juventud',
                        'descripcion' => 'Promoción cultural, educativa y deportiva',
                        'codigo' => 'SGECDJ'
                    ],
                    [
                        'nombre' => 'Subgerencia de Salud y Bienestar Social',
                        'descripcion' => 'Programas de salud comunitaria',
                        'codigo' => 'SGSBS'
                    ],
                    [
                        'nombre' => 'Subgerencia de la Mujer y Poblaciones Vulnerables',
                        'descripcion' => 'Atención a grupos vulnerables',
                        'codigo' => 'SGMPV'
                    ],
                    [
                        'nombre' => 'Oficina de Demuna',
                        'descripcion' => 'Defensoría Municipal del Niño y Adolescente',
                        'codigo' => 'ODEMUNA'
                    ],
                    [
                        'nombre' => 'Oficina de OMAPED',
                        'descripcion' => 'Atención a Personas con Discapacidad',
                        'codigo' => 'OOMAPED'
                    ]
                ]
            ],
            
            // 4. GERENCIA DE SERVICIOS PÚBLICOS Y MEDIO AMBIENTE
            [
                'nombre' => 'Gerencia de Servicios Públicos y Medio Ambiente',
                'descripcion' => 'Mantenimiento de la ciudad y servicios básicos',
                'codigo' => 'GSPMA',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Subgerencia de Limpieza Pública y Residuos Sólidos',
                        'descripcion' => 'Gestión de residuos sólidos',
                        'codigo' => 'SGLPRS'
                    ],
                    [
                        'nombre' => 'Subgerencia de Parques, Jardines y Ornato',
                        'descripcion' => 'Mantenimiento de áreas verdes',
                        'codigo' => 'SGPJO'
                    ],
                    [
                        'nombre' => 'Subgerencia de Cementerios Municipales',
                        'descripcion' => 'Administración de cementerios',
                        'codigo' => 'SGCM'
                    ],
                    [
                        'nombre' => 'Subgerencia de Transporte y Circulación Vial',
                        'descripcion' => 'Control de transporte y tránsito',
                        'codigo' => 'SGTCV'
                    ],
                    [
                        'nombre' => 'Subgerencia de Seguridad Ciudadana',
                        'descripcion' => 'Serenazgo y seguridad local',
                        'codigo' => 'SGSC'
                    ],
                    [
                        'nombre' => 'Subgerencia de Fiscalización Ambiental',
                        'descripcion' => 'Control ambiental municipal',
                        'codigo' => 'SGFA'
                    ]
                ]
            ],
            
            // 5. GERENCIA DE ADMINISTRACIÓN Y FINANZAS
            [
                'nombre' => 'Gerencia de Administración y Finanzas',
                'descripcion' => 'Administración del dinero, compras, almacén y contabilidad',
                'codigo' => 'GAF',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Subgerencia de Tesorería',
                        'descripcion' => 'Gestión de fondos municipales',
                        'codigo' => 'SGT'
                    ],
                    [
                        'nombre' => 'Subgerencia de Contabilidad',
                        'descripcion' => 'Registro contable municipal',
                        'codigo' => 'SGC'
                    ],
                    [
                        'nombre' => 'Subgerencia de Logística y Abastecimiento',
                        'descripcion' => 'Compras y abastecimiento',
                        'codigo' => 'SGLA'
                    ],
                    [
                        'nombre' => 'Subgerencia de Control Patrimonial',
                        'descripcion' => 'Control de bienes municipales',
                        'codigo' => 'SGCP'
                    ],
                    [
                        'nombre' => 'Oficina de Caja y Pagaduría',
                        'descripcion' => 'Caja y pagos municipales',
                        'codigo' => 'OCYP'
                    ]
                ]
            ],
            
            // 6. GERENCIA DE PLANEAMIENTO Y PRESUPUESTO
            [
                'nombre' => 'Gerencia de Planeamiento y Presupuesto',
                'descripcion' => 'Formula planes institucionales y presupuesto participativo',
                'codigo' => 'GPP',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Subgerencia de Planeamiento y Racionalización',
                        'descripcion' => 'Planeamiento institucional',
                        'codigo' => 'SGPR'
                    ],
                    [
                        'nombre' => 'Subgerencia de Presupuesto y Programación Multianual',
                        'descripcion' => 'Gestión presupuestal',
                        'codigo' => 'SGPPM'
                    ],
                    [
                        'nombre' => 'Subgerencia de Inversiones',
                        'descripcion' => 'Gestión de proyectos de inversión',
                        'codigo' => 'SGI'
                    ],
                    [
                        'nombre' => 'Oficina de Cooperación Técnica y Proyectos Especiales',
                        'descripcion' => 'Gestión de cooperación internacional',
                        'codigo' => 'OCTPE'
                    ],
                    [
                        'nombre' => 'Oficina de Seguimiento y Evaluación',
                        'descripcion' => 'Monitoreo y evaluación institucional',
                        'codigo' => 'OSYE'
                    ]
                ]
            ],
            
            // 7. GERENCIA DE RECURSOS HUMANOS
            [
                'nombre' => 'Gerencia de Recursos Humanos',
                'descripcion' => 'Gestión de personal, contrataciones y capacitación',
                'codigo' => 'GRH',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Subgerencia de Administración de Personal',
                        'descripcion' => 'Gestión del personal municipal',
                        'codigo' => 'SGAP'
                    ],
                    [
                        'nombre' => 'Subgerencia de Capacitación y Evaluación del Desempeño',
                        'descripcion' => 'Formación y evaluación del personal',
                        'codigo' => 'SGCED'
                    ],
                    [
                        'nombre' => 'Oficina de Bienestar del Personal',
                        'descripcion' => 'Bienestar laboral',
                        'codigo' => 'OBDP'
                    ],
                    [
                        'nombre' => 'Oficina de Planillas',
                        'descripcion' => 'Gestión de planillas',
                        'codigo' => 'OPLA'
                    ]
                ]
            ],
            
            // 8. GERENCIA DE ASESORÍA JURÍDICA
            [
                'nombre' => 'Gerencia de Asesoría Jurídica',
                'descripcion' => 'Apoyo legal a la gestión municipal',
                'codigo' => 'GAJ',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Subgerencia de Asuntos Contenciosos',
                        'descripcion' => 'Asuntos legales contenciosos',
                        'codigo' => 'SGAC'
                    ],
                    [
                        'nombre' => 'Subgerencia de Asuntos Administrativos y Normativos',
                        'descripcion' => 'Normativa administrativa',
                        'codigo' => 'SGAAN'
                    ],
                    [
                        'nombre' => 'Oficina de Ejecutoría Coactiva',
                        'descripcion' => 'Cobranza coactiva',
                        'codigo' => 'OEC'
                    ],
                    [
                        'nombre' => 'Oficina de Secretaría Técnica de Procedimientos Administrativos',
                        'descripcion' => 'Procedimientos administrativos',
                        'codigo' => 'OSTPA'
                    ]
                ]
            ],
            
            // 9. GERENCIA DE ADMINISTRACIÓN TRIBUTARIA
            [
                'nombre' => 'Gerencia de Administración Tributaria',
                'descripcion' => 'Recaudación de tributos municipales',
                'codigo' => 'GAT',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Subgerencia de Recaudación Tributaria',
                        'descripcion' => 'Recaudación de impuestos municipales',
                        'codigo' => 'SGRT'
                    ],
                    [
                        'nombre' => 'Subgerencia de Fiscalización Tributaria',
                        'descripcion' => 'Fiscalización tributaria',
                        'codigo' => 'SGFT'
                    ],
                    [
                        'nombre' => 'Subgerencia de Control de Deuda y Ejecución Coactiva',
                        'descripcion' => 'Control de deudas tributarias',
                        'codigo' => 'SGCDEC'
                    ],
                    [
                        'nombre' => 'Oficina de Atención al Contribuyente',
                        'descripcion' => 'Atención y orientación tributaria',
                        'codigo' => 'OAAC'
                    ]
                ]
            ],
            
            // 10. GERENCIA DE SEGURIDAD CIUDADANA Y DEFENSA CIVIL
            [
                'nombre' => 'Gerencia de Seguridad Ciudadana y Defensa Civil',
                'descripcion' => 'Seguridad, prevención y atención de emergencias',
                'codigo' => 'GSCDC',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Subgerencia de Serenazgo',
                        'descripcion' => 'Serenazgo municipal',
                        'codigo' => 'SGSER'
                    ],
                    [
                        'nombre' => 'Subgerencia de Defensa Civil y Gestión del Riesgo de Desastres',
                        'descripcion' => 'Prevención y atención de desastres',
                        'codigo' => 'SGDCGRD'
                    ],
                    [
                        'nombre' => 'Subgerencia de Fiscalización y Control Municipal',
                        'descripcion' => 'Fiscalización municipal',
                        'codigo' => 'SGFCM'
                    ],
                    [
                        'nombre' => 'Oficina de Seguridad Vial',
                        'descripcion' => 'Seguridad en vías públicas',
                        'codigo' => 'OSV'
                    ]
                ]
            ],
            
            // 11. GERENCIA DE TECNOLOGÍAS DE LA INFORMACIÓN
            [
                'nombre' => 'Gerencia de Tecnologías de la Información',
                'descripcion' => 'Modernización de sistemas municipales',
                'codigo' => 'GTI',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Subgerencia de Gobierno Digital e Innovación',
                        'descripcion' => 'Transformación digital municipal',
                        'codigo' => 'SGGDI'
                    ],
                    [
                        'nombre' => 'Subgerencia de Infraestructura Tecnológica',
                        'descripcion' => 'Infraestructura TI',
                        'codigo' => 'SGIT'
                    ],
                    [
                        'nombre' => 'Subgerencia de Soporte Técnico y Desarrollo de Software',
                        'descripcion' => 'Soporte y desarrollo de sistemas',
                        'codigo' => 'SGSTDS'
                    ],
                    [
                        'nombre' => 'Oficina de Archivo Digital y Trámite Documentario',
                        'descripcion' => 'Gestión documental digital',
                        'codigo' => 'OADTD'
                    ]
                ]
            ],
            
            // 12. GERENCIA DE CONTROL INTERNO
            [
                'nombre' => 'Gerencia de Control Interno',
                'descripcion' => 'Vigilancia de la legalidad y transparencia de la gestión',
                'codigo' => 'GCI',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Oficina de Control Interno',
                        'descripcion' => 'Control interno institucional (OCI)',
                        'codigo' => 'OCI'
                    ],
                    [
                        'nombre' => 'Oficina de Auditoría Interna',
                        'descripcion' => 'Auditoría interna municipal',
                        'codigo' => 'OAI'
                    ]
                ]
            ]
        ];

        // Crear gerencias principales y subgerencias
        foreach ($gerenciasEstructura as $gerenciaData) {
            // Crear gerencia principal
            $gerencia = Gerencia::firstOrCreate(
                ['codigo' => $gerenciaData['codigo']],
                [
                    'nombre' => $gerenciaData['nombre'],
                    'descripcion' => $gerenciaData['descripcion'],
                    'tipo' => $gerenciaData['tipo'],
                    'activo' => $gerenciaData['activo'],
                    'gerencia_padre_id' => null
                ]
            );

            // Crear subgerencias si existen
            if (isset($gerenciaData['subgerencias']) && !empty($gerenciaData['subgerencias'])) {
                foreach ($gerenciaData['subgerencias'] as $subData) {
                    Gerencia::firstOrCreate(
                        ['codigo' => $subData['codigo']],
                        [
                            'nombre' => $subData['nombre'],
                            'descripcion' => $subData['descripcion'],
                            'tipo' => 'subgerencia',
                            'activo' => true,
                            'gerencia_padre_id' => $gerencia->id
                        ]
                    );
                }
            }
        }

        $gerenciasPrincipales = Gerencia::whereNull('gerencia_padre_id')->count();
        $subgerencias = Gerencia::whereNotNull('gerencia_padre_id')->count();
        
        $this->command->info("Gerencias creadas exitosamente:");
        $this->command->info("- {$gerenciasPrincipales} gerencias principales");
        $this->command->info("- {$subgerencias} subgerencias");
        $this->command->info("- Total: " . Gerencia::count() . " gerencias");
    }
}