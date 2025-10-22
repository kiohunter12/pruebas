<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gerencia;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkflowCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_workflow_successfully()
    {
        $this->withoutMiddleware(); // ignorar auth y otros middlewares

        // 1) Crear gerencia requerida por workflow
        $gerencia = Gerencia::create([
            'codigo' => 'G-TEST',
            'nombre' => 'Gerencia de Pruebas',
            'descripcion' => 'Solo para test',
            'activo' => true,
        ]);

       // 2) Crear usuario para created_by
        $user = User::create([
        'name' => 'Usuario Test',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        ]);

        $this->actingAs($user); // <<< AUTENTICAMOS

        // 3) Datos del workflow a crear
        $data = [
        'nombre' => 'Workflow de Test',
        'codigo' => 'WF-001',
        'descripcion' => 'Flujo de prueba',
         'tipo' => 'expediente',
         'configuracion' => [],
         'activo' => true,
         'gerencia_id' => $gerencia->id,
            // 'created_by' => $user->id,  <-- ELIMINADO
        ];

        // 4) Consumir el endpoint (ajusta ruta si no es esta)
        $response = $this->postJson('/api/workflows', $data);
        \App\Models\Workflow::query()->update(['created_by' => $user->id]);

        // 5) Validar respuesta HTTP
        $response->assertStatus(201);

        // 6) Validar que se guardó en base de datos
        $this->assertDatabaseHas('workflows', [
            'nombre' => 'Workflow de Test',
            'codigo' => 'WF-001',
        ]);
    }
}
