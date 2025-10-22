<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Workflow;
use App\Models\User;
use App\Models\Gerencia;
use App\Repositories\WorkflowRepository;
use App\Actions\CreateWorkflowAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;

class WorkflowControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $workflowRepo;
    protected $createAction;
    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->workflowRepo = Mockery::mock(WorkflowRepository::class);
        $this->createAction = Mockery::mock(CreateWorkflowAction::class);
        
        $this->app->instance(WorkflowRepository::class, $this->workflowRepo);
        $this->app->instance(CreateWorkflowAction::class, $this->createAction);

        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'administrador']);
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'name' => 'Admin Test',
            'password' => bcrypt('password')
        ]);

        $this->admin->assignRole('administrador');
        $this->admin->givePermissionTo('gestionar_workflows');
        
        $token = $this->admin->createToken('test-token')->plainTextToken;
        $this->actingAs($this->admin);
        $this->withToken($token);
    }

    /** @test */
    public function it_can_list_workflows()
    {
        $workflows = Workflow::factory()->count(3)->create();
        
        $this->workflowRepo->shouldReceive('paginate')
            ->once()
            ->with([], 10)
            ->andReturn(new \Illuminate\Pagination\LengthAwarePaginator($workflows, $workflows->count(), 10));

        $this->workflowRepo->shouldReceive('getOptions')
            ->once()
            ->andReturn(['tipos' => [], 'gerencias' => []]);

        $response = $this->get('/api/workflows');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data',
                    'current_page',
                    'per_page'
                ]
            ]);
    }

    /** @test */
    public function it_can_create_workflow()
    {
        $gerencia = Gerencia::factory()->create();
        $workflowData = [
            'nombre' => 'Test Workflow',
            'codigo' => 'TEST-001',
            'tipo' => 'tramite',
            'descripcion' => 'Test description',
            'gerencia_id' => $gerencia->id,
            'activo' => true,
            'created_by' => $this->admin->id
        ];

        $workflow = Workflow::factory()->make($workflowData);

        $this->createAction->shouldReceive('execute')
            ->once()
            ->with($workflowData)
            ->andReturn($workflow);

        $response = $this->post('/api/workflows', $workflowData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Workflow creado exitosamente'
            ]);
    }

    /** @test */
    public function it_can_show_workflow()
    {
        $workflow = Workflow::factory()->create();

        $response = $this->get("/api/workflows/{$workflow->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'workflow'
            ]);
    }

    /** @test */
    public function it_can_update_workflow()
    {
        $workflow = Workflow::factory()->create(['created_by' => $this->admin->id]);
        $updatedData = [
            'nombre' => 'Updated Workflow',
            'codigo' => $workflow->codigo,
            'tipo' => 'tramite',
            'descripcion' => 'Updated description',
            'gerencia_id' => $workflow->gerencia_id,
            'activo' => true
        ];

        $this->workflowRepo->shouldReceive('update')
            ->once()
            ->with($workflow->id, $updatedData)
            ->andReturn($workflow);

        $response = $this->put("/api/workflows/{$workflow->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Workflow actualizado exitosamente'
            ]);
    }

    /** @test */
    public function it_can_delete_workflow()
    {
        $workflow = Workflow::factory()->create(['created_by' => $this->admin->id]);

        $this->workflowRepo->shouldReceive('delete')
            ->once()
            ->with($workflow->id)
            ->andReturn(true);

        $response = $this->delete("/api/workflows/{$workflow->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Workflow eliminado exitosamente'
            ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_workflow()
    {
        $workflowData = [
            'tipo' => 'tramite',
            'activo' => true
        ];

        $response = $this->post('/api/workflows', $workflowData, [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nombre', 'codigo']);
    }

    /** @test */
    public function it_can_duplicate_workflow()
    {
        $workflow = Workflow::factory()->create(['created_by' => $this->admin->id]);
        $newWorkflow = Workflow::factory()->make();

        $this->workflowRepo->shouldReceive('duplicate')
            ->once()
            ->with($workflow->id)
            ->andReturn($newWorkflow);

        $response = $this->post("/api/workflows/{$workflow->id}/clone");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Workflow duplicado exitosamente'
            ]);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}