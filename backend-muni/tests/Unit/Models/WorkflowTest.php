<?php

namespace Tests\Unit\Models;

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

    /** @test */
    public function it_has_fillable_attributes()
    {
        $workflow = new Workflow();

        $fillable = [
            'nombre',
            'codigo',
            'descripcion',
            'tipo',
            'configuracion',
            'activo',
            'gerencia_id',
            'created_by'
        ];

        $this->assertEquals($fillable, $workflow->getFillable());
    }

    /** @test */
    public function it_has_correct_relationships()
    {
        // Arrange
        $user = User::factory()->create();
        $gerencia = Gerencia::factory()->create();
        
        $workflow = Workflow::factory()->create([
            'created_by' => $user->id,
            'gerencia_id' => $gerencia->id
        ]);

        $step = WorkflowStep::factory()->create([
            'workflow_id' => $workflow->id
        ]);

        // Act & Assert
        $this->assertTrue($workflow->gerencia()->exists());
        $this->assertTrue($workflow->creador()->exists());
        $this->assertTrue($workflow->steps()->exists());
        $this->assertEquals($user->id, $workflow->creador->id);
        $this->assertEquals($gerencia->id, $workflow->gerencia->id);
        $this->assertTrue($workflow->steps->contains($step));
    }

    /** @test */
    public function it_can_get_step_by_id()
    {
        // Arrange
        $workflow = Workflow::factory()->create();
        $step = WorkflowStep::factory()->create([
            'workflow_id' => $workflow->id
        ]);

        // Act
        $foundStep = $workflow->getStepById($step->id);

        // Assert
        $this->assertNotNull($foundStep);
        $this->assertEquals($step->id, $foundStep->id);
    }

    /** @test */
    public function it_can_get_initial_step()
    {
        // Arrange
        $workflow = Workflow::factory()->create();
        $step1 = WorkflowStep::factory()->create([
            'workflow_id' => $workflow->id,
            'tipo' => 'inicio',
            'orden' => 1
        ]);
        $step2 = WorkflowStep::factory()->create([
            'workflow_id' => $workflow->id,
            'tipo' => 'normal',
            'orden' => 2
        ]);

        // Act
        $initialStep = $workflow->getInitialStep();

        // Assert
        $this->assertEquals($step1->id, $initialStep->id);
    }

    /** @test */
    public function it_can_get_final_steps()
    {
        // Arrange
        $workflow = Workflow::factory()->create();
        $normalStep = WorkflowStep::factory()->create([
            'workflow_id' => $workflow->id,
            'tipo' => 'normal'
        ]);
        $finalStep = WorkflowStep::factory()->create([
            'workflow_id' => $workflow->id,
            'tipo' => 'fin'
        ]);

        // Act
        $finalSteps = $workflow->getFinalSteps();

        // Assert
        $this->assertEquals(1, $finalSteps->count());
        $this->assertEquals($finalStep->id, $finalSteps->first()->id);
    }

    /** @test */
    public function it_can_get_next_steps()
    {
        // Arrange
        $workflow = Workflow::factory()->create();
        $step1 = WorkflowStep::factory()->create(['workflow_id' => $workflow->id]);
        $step2 = WorkflowStep::factory()->create(['workflow_id' => $workflow->id]);
        $transition = WorkflowTransition::factory()->create([
            'workflow_id' => $workflow->id,
            'from_step_id' => $step1->id,
            'to_step_id' => $step2->id
        ]);

        // Act
        $nextSteps = $workflow->getNextSteps($step1->id);

        // Assert
        $this->assertEquals(1, $nextSteps->count());
        $this->assertEquals($step2->id, $nextSteps->first()->id);
    }

    /** @test */
    public function it_can_get_previous_steps()
    {
        // Arrange
        $workflow = Workflow::factory()->create();
        $step1 = WorkflowStep::factory()->create(['workflow_id' => $workflow->id]);
        $step2 = WorkflowStep::factory()->create(['workflow_id' => $workflow->id]);
        $transition = WorkflowTransition::factory()->create([
            'workflow_id' => $workflow->id,
            'from_step_id' => $step1->id,
            'to_step_id' => $step2->id
        ]);

        // Act
        $previousSteps = $workflow->getPreviousSteps($step2->id);

        // Assert
        $this->assertEquals(1, $previousSteps->count());
        $this->assertEquals($step1->id, $previousSteps->first()->id);
    }

    /** @test */
    public function it_can_filter_active_workflows()
    {
        // Arrange
        Workflow::factory()->create(['activo' => true]);
        Workflow::factory()->create(['activo' => false]);

        // Act
        $activeWorkflows = Workflow::activos()->get();

        // Assert
        $this->assertEquals(1, $activeWorkflows->count());
        $this->assertTrue($activeWorkflows->first()->activo);
    }

    /** @test */
    public function it_can_filter_by_type()
    {
        // Arrange
        Workflow::factory()->create(['tipo' => 'tramite']);
        Workflow::factory()->create(['tipo' => 'proceso']);

        // Act
        $tramiteWorkflows = Workflow::porTipo('tramite')->get();

        // Assert
        $this->assertEquals(1, $tramiteWorkflows->count());
        $this->assertEquals('tramite', $tramiteWorkflows->first()->tipo);
    }

    /** @test */
    public function it_can_filter_by_gerencia()
    {
        // Arrange
        $gerencia1 = Gerencia::factory()->create();
        $gerencia2 = Gerencia::factory()->create();
        Workflow::factory()->create(['gerencia_id' => $gerencia1->id]);
        Workflow::factory()->create(['gerencia_id' => $gerencia2->id]);

        // Act
        $gerencia1Workflows = Workflow::porGerencia($gerencia1->id)->get();

        // Assert
        $this->assertEquals(1, $gerencia1Workflows->count());
        $this->assertEquals($gerencia1->id, $gerencia1Workflows->first()->gerencia_id);
    }
}