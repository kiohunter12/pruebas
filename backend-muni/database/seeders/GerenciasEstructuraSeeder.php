<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gerencia;

class GerenciasEstructuraSeeder extends Seeder
{
    public function run(): void
    {
        // Estructura de gerencias municipales
        $estructura = [
            // DESPACHO DE ALCALDÍA
            [
                'nombre' => 'Despacho de Alcaldía',
                'descripcion' => 'Despacho del Alcalde Municipal',
                'tipo' => 'gerencia',
                'subgerencias' => []
            ],

            // SECRETARÍA GENERAL
            [
                'nombre' => 'Secretaría General',
                'descripcion' => 'Secretaría General y Mesa de Partes',
                'tipo' => 'gerencia',
                'subgerencias' => [
                    [
                        'nombre' => 'Mesa de Partes',
                        'descripcion' => 'Recepción y derivación de documentos'
                    ]
                ]
            ],

            // REGISTRO CIVIL
            [
                'nombre' => 'Oficina de Registro Civil',
                'descripcion' => 'Registro de hechos vitales',
                'tipo' => 'gerencia',
                'subgerencias' => [
                    [
                        'nombre' => 'Área de Nacimientos',
                        'descripcion' => 'Inscripción de nacimientos y emisión de partidas'
                    ],
                    [
                        'nombre' => 'Área de Defunciones',
                        'descripcion' => 'Inscripción de defunciones y emisión de partidas'
                    ],
                    [
                        'nombre' => 'Área de Matrimonios',
                        'descripcion' => 'Inscripción de matrimonios y emisión de partidas'
                    ]
                ]
            ],

            // DESARROLLO URBANO
            [
                'nombre' => 'Gerencia de Desarrollo Urbano',
                'descripcion' => 'Desarrollo urbano, catastro y obras privadas',
                'tipo' => 'gerencia',
                'subgerencias' => [
                    [
                        'nombre' => 'Sub Gerencia de Obras Privadas',
                        'descripcion' => 'Licencias de edificación y construcción'
                    ],
                    [
                        'nombre' => 'Sub Gerencia de Catastro',
                        'descripcion' => 'Catastro urbano y certificaciones'
                    ]
                ]
            ],

            // LICENCIAS Y COMERCIO
            [
                'nombre' => 'Gerencia de Licencias y Comercio',
                'descripcion' => 'Licencias de funcionamiento y comercio',
                'tipo' => 'gerencia',
                'subgerencias' => [
                    [
                        'nombre' => 'Sub Gerencia de Comercio',
                        'descripcion' => 'Licencias comerciales y autorizaciones'
                    ]
                ]
            ],

            // ADMINISTRACIÓN TRIBUTARIA
            [
                'nombre' => 'Gerencia de Administración Tributaria',
                'descripcion' => 'Administración de tributos municipales',
                'tipo' => 'gerencia',
                'subgerencias' => [
                    [
                        'nombre' => 'Sub Gerencia de Recaudación',
                        'descripcion' => 'Recaudación de tributos municipales'
                    ],
                    [
                        'nombre' => 'Sub Gerencia de Fiscalización',
                        'descripcion' => 'Fiscalización tributaria'
                    ]
                ]
            ],

            // MEDIO AMBIENTE
            [
                'nombre' => 'Gerencia de Medio Ambiente',
                'descripcion' => 'Gestión ambiental y servicios públicos',
                'tipo' => 'gerencia',
                'subgerencias' => [
                    [
                        'nombre' => 'Sub Gerencia de Ecología',
                        'descripcion' => 'Protección y conservación ambiental'
                    ]
                ]
            ],

            // SEGURIDAD CIUDADANA
            [
                'nombre' => 'Gerencia de Seguridad Ciudadana',
                'descripcion' => 'Seguridad y tránsito municipal',
                'tipo' => 'gerencia',
                'subgerencias' => [
                    [
                        'nombre' => 'Sub Gerencia de Tránsito',
                        'descripcion' => 'Control de tránsito y transporte'
                    ]
                ]
            ]
        ];

        // Crear las gerencias principales y subgerencias
        foreach ($estructura as $gerenciaData) {
            $gerencia = Gerencia::firstOrCreate(
                ['nombre' => $gerenciaData['nombre']],
                [
                    'descripcion' => $gerenciaData['descripcion'],
                    'tipo' => $gerenciaData['tipo'],
                    'activo' => true,
                    'codigo' => $this->generateCodigo($gerenciaData['nombre']),
                    'gerencia_padre_id' => null
                ]
            );

            // Crear subgerencias
            foreach ($gerenciaData['subgerencias'] as $subData) {
                Gerencia::firstOrCreate(
                    ['nombre' => $subData['nombre']],
                    [
                        'descripcion' => $subData['descripcion'],
                        'tipo' => 'subgerencia',
                        'gerencia_padre_id' => $gerencia->id,
                        'activo' => true,
                        'codigo' => $this->generateCodigo($subData['nombre'])
                    ]
                );
            }
        }
    }

    private function generateCodigo($nombre)
    {
        // Remover acentos y caracteres especiales
        $nombre = $this->removeAccents($nombre);
        
        // Remover palabras comunes
        $palabrasIgnorar = ['de', 'del', 'la', 'el', 'y', 'sub', 'gerencia', 'area', 'oficina'];
        $palabras = explode(' ', strtolower($nombre));
        
        // Filtrar palabras importantes
        $palabrasImportantes = array_filter($palabras, function($palabra) use ($palabrasIgnorar) {
            return !in_array($palabra, $palabrasIgnorar) && strlen($palabra) > 2;
        });
        
        // Si no quedan palabras importantes, usar las primeras palabras
        if (empty($palabrasImportantes)) {
            $palabrasImportantes = array_slice($palabras, 0, 2);
        }
        
        $codigo = '';
        foreach ($palabrasImportantes as $palabra) {
            $codigo .= strtoupper(substr($palabra, 0, 3));
        }
        
        // Asegurar que no sea muy largo
        $codigo = substr($codigo, 0, 6);
        
        // Si está vacío, usar las primeras letras del nombre completo
        if (empty($codigo)) {
            $codigo = strtoupper(substr(str_replace(' ', '', $this->removeAccents($nombre)), 0, 6));
        }
        
        return $codigo;
    }
    
    private function removeAccents($string)
    {
        $accents = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N'
        ];
        
        return str_replace(array_keys($accents), array_values($accents), $string);
    }
}