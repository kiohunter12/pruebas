<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Expediente;
use App\Models\Procedure;
use App\Models\Gerencia;
use Carbon\Carbon;

class ExpedientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios ciudadanos
        $ciudadanos = User::whereHas('roles', function($query) {
            $query->where('name', 'ciudadano');
        })->get();

        // Obtener procedimientos
        $procedures = Procedure::all();

        // Obtener gerencias
        $gerencias = Gerencia::all();

        // Obtener gerentes y subgerentes para asignar
        $gerentes = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['gerente', 'subgerente', 'supervisor', 'jefe_gerencia']);
        })->get();

        $expedientes = [
            [
                'numero' => 'EXP-2025-001',
                'solicitante_nombre' => $ciudadanos[0]->name ?? 'Juan Ciudadano Pérez',
                'solicitante_dni' => '98765432',
                'solicitante_email' => $ciudadanos[0]->email ?? 'juan.ciudadano@gmail.com',
                'solicitante_telefono' => '912345678',
                'tipo_tramite' => 'Licencia de Funcionamiento',
                'asunto' => 'Licencia de funcionamiento para tienda de abarrotes',
                'descripcion' => 'Solicito licencia de funcionamiento para tienda de abarrotes ubicada en Jr. Comercio 123',
                'estado' => 'finalizado',
                'fecha_registro' => Carbon::now()->subDays(20),
                'fecha_resolucion' => Carbon::now()->subDays(5),
                'gerencia_id' => $gerencias->where('codigo', 'GDEL')->first()->id ?? 1,
                'usuario_registro_id' => $ciudadanos[0]->id ?? 1,
                'numero_resolucion' => 'RES-001-2025',
                'notificado_ciudadano' => true,
                'fecha_notificacion' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'numero' => 'EXP-2025-002',
                'solicitante_nombre' => $ciudadanos[1]->name ?? 'Rosa Martínez Ciudadana',
                'solicitante_dni' => '98765433',
                'solicitante_email' => $ciudadanos[1]->email ?? 'rosa.martinez@hotmail.com',
                'solicitante_telefono' => '912345679',
                'tipo_tramite' => 'Licencia de Edificación',
                'asunto' => 'Licencia de edificación para vivienda unifamiliar',
                'descripcion' => 'Solicito licencia para construir vivienda unifamiliar de 2 pisos',
                'estado' => 'en_proceso',
                'fecha_registro' => Carbon::now()->subDays(15),
                'fecha_derivacion' => Carbon::now()->subDays(14),
                'gerencia_id' => $gerencias->where('codigo', 'GDUR')->first()->id ?? 2,
                'usuario_registro_id' => $gerentes->where('gerencia_id', $gerencias->where('codigo', 'GDUR')->first()->id)->first()->id ?? 1,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'numero' => 'EXP-2025-003',
                'solicitante_nombre' => 'Carlos Empresario López',
                'solicitante_dni' => '12345678',
                'solicitante_email' => 'carlos.empresario@empresa.com',
                'solicitante_telefono' => '987654321',
                'tipo_tramite' => 'Autorización de Eventos',
                'asunto' => 'Autorización para evento cultural en plaza principal',
                'descripcion' => 'Solicito autorización para realizar evento cultural el próximo mes',
                'estado' => 'observado',
                'fecha_registro' => Carbon::now()->subDays(10),
                'fecha_derivacion' => Carbon::now()->subDays(9),
                'gerencia_id' => $gerencias->where('codigo', 'GSCDC')->first()->id ?? 3,
                'observaciones' => 'Falta presentar póliza de seguro y plan de contingencia',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'numero' => 'EXP-2025-004',
                'solicitante_nombre' => 'Ana Comerciante Pérez',
                'solicitante_dni' => '87654321',
                'solicitante_email' => 'ana.comerciante@gmail.com',
                'solicitante_telefono' => '945678123',
                'tipo_tramite' => 'Ocupación de Vía Pública',
                'asunto' => 'Permiso para ocupación temporal de vía pública',
                'descripcion' => 'Solicito permiso para colocar material de construcción en vía pública por 3 días',
                'estado' => 'pendiente',
                'fecha_registro' => Carbon::now()->subDays(3),
                'gerencia_id' => $gerencias->where('codigo', 'GSPMA')->first()->id ?? 4,
                'usuario_registro_id' => $gerentes->where('gerencia_id', $gerencias->where('codigo', 'GSPMA')->first()->id)->first()->id ?? 1,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'numero' => 'EXP-2025-005',
                'solicitante_nombre' => 'Pedro Transportista Ruiz',
                'solicitante_dni' => '76543210',
                'solicitante_email' => 'pedro.transportista@hotmail.com',
                'solicitante_telefono' => '923456789',
                'tipo_tramite' => 'Autorización de Transporte',
                'asunto' => 'Autorización de transporte escolar',
                'descripcion' => 'Solicito autorización para prestar servicio de transporte escolar',
                'estado' => 'rechazado',
                'fecha_registro' => Carbon::now()->subDays(25),
                'fecha_resolucion' => Carbon::now()->subDays(8),
                'gerencia_id' => $gerencias->where('codigo', 'GSPMA')->first()->id ?? 5,
                'motivo_rechazo' => 'No cumple con los requisitos de seguridad vehicular',
                'notificado_ciudadano' => true,
                'fecha_notificacion' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now()->subDays(7),
            ],
        ];

        foreach ($expedientes as $expedienteData) {
            Expediente::updateOrCreate(
                ['numero' => $expedienteData['numero']], // Buscar por número
                $expedienteData // Actualizar o crear con estos datos
            );
        }

        $this->command->info('Expedientes de prueba creados exitosamente');
        $this->command->info('Total de expedientes: ' . Expediente::count());
        $this->command->info('Expedientes en diferentes estados para testing');
    }
}
