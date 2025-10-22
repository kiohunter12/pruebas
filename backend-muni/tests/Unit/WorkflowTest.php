<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use App\Models\WorkflowTransition;
use App\Models\User;
use App\Models\Gerencia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WorkflowTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $workflow;
    protected $user;
    protected $gerencia;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear datos base necesarios
        $this->user = User::factory()->create();
        $this->gerencia = Gerencia::factory()->create();
        
        // Crear un workflow de prueba
        $this->workflow = Workflow::create([
            'nombre' => 'Workflow de Prueba',
            'codigo' => 'WF-TEST-001',
            'descripcion' => 'Workflow para pruebas unitarias',
            'tipo' => 'tramite',
            'configuracion' => ['testing' => true],
            'activo' => true,
            'gerencia_id' => $this->gerencia->id,
            'created_by' => $this->user->id
        ]);
    }

    /** @test */
    public function can_create_workflow()
    {
        $this->assertDatabaseHas('workflows', [
            'nombre' => 'Workflow de Prueba',
            'codigo' => 'WF-TEST-001'
        ]);
    }

    /** @test */
    public function can_add_steps_to_workflow()
    {
        // Crear pasos de prueba
        $step1 = WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'nombre' => 'Paso 1',
            'orden' => 1,
            'tipo' => 'inicio'
        ]);

        $step2 = WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'nombre' => 'Paso 2',
            'orden' => 2,
            'tipo' => 'proceso'
        ]);

        $this->assertEquals(2, $this->workflow->steps()->count());
        $this->assertEquals('Paso 1', $this->workflow->getInitialStep()->nombre);
    }

    /** @test */
    public function can_create_transitions_between_steps()
    {
        // Crear pasos
        $step1 = WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'nombre' => 'Inicio',
            'orden' => 1,
            'tipo' => 'inicio'
        ]);

        $step2 = WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'nombre' => 'Fin',
            'orden' => 2,
            'tipo' => 'fin'
        ]);

        // Crear transición
        $transition = WorkflowTransition::create([
            'workflow_id' => $this->workflow->id,
            'from_step_id' => $step1->id,
            'to_step_id' => $step2->id,
        ]);

        $nextSteps = $this->workflow->getNextSteps($step1->id);
        $this->assertCount(1, $nextSteps);
        $this->assertEquals($step2->id, $nextSteps->first()->id);
    }

    /** @test */
    public function can_get_final_steps()
    {
        // Crear pasos incluyendo finales
        $step1 = WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'nombre' => 'Inicio',
            'orden' => 1,
            'tipo' => 'inicio'
        ]);

        $step2 = WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'nombre' => 'Fin 1',
            'orden' => 2,
            'tipo' => 'fin'
        ]);

        $step3 = WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'nombre' => 'Fin 2',
            'orden' => 3,
            'tipo' => 'fin'
        ]);

        $finalSteps = $this->workflow->getFinalSteps();
        $this->assertCount(2, $finalSteps);
    }

    /** @test */
    public function workflow_scopes_work_correctly()
    {
        // Crear workflow inactivo
        $inactiveWorkflow = Workflow::create([
            'nombre' => 'Workflow Inactivo',
            'codigo' => 'WF-INACTIVE',
            'tipo' => 'tramite',
            'activo' => false,
            'gerencia_id' => $this->gerencia->id,
            'created_by' => $this->user->id
        ]);

        // Probar scope activos
        $this->assertEquals(1, Workflow::activos()->count());

        // Probar scope porTipo
        $this->assertEquals(2, Workflow::porTipo('tramite')->count());

        // Probar scope porGerencia
        $this->assertEquals(2, Workflow::porGerencia($this->gerencia->id)->count());
    }

    /** @test */
    public function can_get_workflow_progress()
    {
        $step1 = WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'nombre' => 'Paso 1',
            'orden' => 1
        ]);

        $step2 = WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'nombre' => 'Paso 2',
            'orden' => 2
        ]);

        // Verificar que los pasos estén en orden correcto
        $steps = $this->workflow->steps;
        $this->assertEquals('Paso 1', $steps->first()->nombre);
        $this->assertEquals('Paso 2', $steps->last()->nombre);
    }
}