<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando la siembra de datos del sistema de trámites municipales...');
        $this->command->info('');

        // Ejecutar seeders en orden específico debido a dependencias
        $this->call([
            //GerenciasSeeder::class,            // Primero: gerencias (necesario para usuarios)
            RolesAndPermissionsSeeder::class,  // Segundo: roles y permisos del sistema
            UsersSeeder::class,                // Tercero: usuarios adicionales del sistema
            //ProceduresSeeder::class,           // Cuarto: procedimientos TUPA (necesita gerencias)
            //WorkflowRulesSeeder::class,        // Quinto: reglas de flujo (necesita gerencias y usuarios)
            //WorkflowsSeeder::class,            // Sexto: workflows completos (necesita gerencias y usuarios)
            //ExpedientesSeeder::class,          // Séptimo: expedientes (necesita usuarios y procedimientos)
            TipoDocumentoSeeder::class,        // Octavo: tipos de documentos
            //TipoTramiteSeeder::class,          // Noveno: tipos de trámites (necesita gerencias y TipoDocumento)
            //AsignarWorkflowsSeeder::class,     // Décimo: asignar workflows a tipos de trámite
        ]);

       
    }
}
