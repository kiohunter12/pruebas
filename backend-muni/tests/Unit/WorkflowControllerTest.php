<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Workflow;
use App\Models\User;
use App\Models\Gerencia;
use App\Http\Controllers\WorkflowController;
use App\Repositories\WorkflowRepository;
use App\Actions\CreateWorkflowAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Mockery;

class WorkflowControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $controller;
    protected $workflowRepo;
    protected $createAction;
    protected $user;
    protected $gerencia;

    protected function setUp(): void
    {
        parent::setUp();

        $this->workflowRepo = Mockery::mock(WorkflowRepository::class);
        $this->createAction = Mockery::mock(CreateWorkflowAction::class);
        $this->controller = new WorkflowController($this->workflowRepo, $this->createAction);

        // Crear datos base necesarios
        $this->user = User::factory()->create();
        $this->gerencia = Gerencia::factory()->create();
        
        // Autenticar usuario
        $this->actingAs($this->user);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function index_returns_paginated_workflows()
    {
        // Crear algunos workflows de prueba
        $workflow1 = Workflow::create([
            'nombre' => 'Workflow 1',
            'codigo' => 'WF-001',
            'tipo' => 'tramite',
            'activo' => true,
            'gerencia_id' => $this->gerencia->id,
            'created_by' => $this->user->id
        ]);

        $workflow2 = Workflow::create([
            'nombre' => 'Workflow 2',
            'codigo' => 'WF-002',
            'tipo' => 'tramite',
            'activo' => true,
            'gerencia_id' => $this->gerencia->id,
            'created_by' => $this->user->id
        ]);

        // Configurar mock del repositorio
        $this->workflowRepo->shouldReceive('paginate')
            ->once()
            ->andReturn(Workflow::paginate(10));

        // Hacer la petición
        $request = Request::create('/workflows', 'GET');
        $response = $this->controller->index($request);

        // Verificar la respuesta
        $this->assertTrue($response->getData()->success);
        $this->assertEquals(2, $response->getData()->data->total);
    }

    /** @test */
    public function store_creates_new_workflow()
    {
        $workflowData = [
            'nombre' => 'Nuevo Workflow',
            'codigo' => 'WF-NEW',
            'tipo' => 'tramite',
            'activo' => true,
            'gerencia_id' => $this->gerencia->id
        ];

        // Configurar mock de CreateWorkflowAction
        $this->createAction->shouldReceive('execute')
            ->once()
            ->with(Mockery::subset($workflowData))
            ->andReturn(new Workflow($workflowData));

        // Hacer la petición
        $request = Request::create('/workflows', 'POST', $workflowData);
        $response = $this->controller->store($request);

        // Verificar la respuesta
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->getData()->success);
        $this->assertEquals('Nuevo Workflow', $response->getData()->data->nombre);
    }

    /** @test */
    public function store_validates_input()
    {
        // Datos incompletos/inválidos
        $workflowData = [
            'nombre' => '', // Requerido
            'codigo' => 'WF-INVALID',
            'tipo' => 'invalid_type', // Tipo inválido
        ];

        // Hacer la petición
        $request = Request::create('/workflows', 'POST', $workflowData);
        $response = $this->controller->store($request);

        // Verificar que hay errores de validación
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($response->getData()->success);
        $this->assertObjectHasAttribute('errors', $response->getData());
    }

    /** @test */
    public function show_returns_workflow_with_stats()
    {
        // Crear workflow de prueba
        $workflow = Workflow::create([
            'nombre' => 'Workflow Test',
            'codigo' => 'WF-TEST',
            'tipo' => 'tramite',
            'activo' => true,
            'gerencia_id' => $this->gerencia->id,
            'created_by' => $this->user->id
        ]);

        // Hacer la petición
        $request = Request::create("/workflows/{$workflow->id}", 'GET');
        $request->headers->set('Accept', 'application/json');
        
        $response = $this->controller->show($workflow);

        // Verificar la respuesta
        $this->assertTrue($response->getData()->success);
        $this->assertEquals($workflow->id, $response->getData()->data->id);
    }

    /** @test */
    public function update_modifies_existing_workflow()
    {
        // Crear workflow de prueba
        $workflow = Workflow::create([
            'nombre' => 'Workflow Original',
            'codigo' => 'WF-ORIG',
            'tipo' => 'tramite',
            'activo' => true,
            'gerencia_id' => $this->gerencia->id,
            'created_by' => $this->user->id
        ]);

        $updateData = [
            'nombre' => 'Workflow Actualizado',
            'descripcion' => 'Nueva descripción'
        ];

        // Configurar mock del repositorio
        $this->workflowRepo->shouldReceive('update')
            ->once()
            ->with($workflow->id, Mockery::subset($updateData))
            ->andReturn($workflow);

        // Hacer la petición
        $request = Request::create("/workflows/{$workflow->id}", 'PUT', $updateData);
        $response = $this->controller->update($request, $workflow);

        // Verificar la respuesta
        $this->assertTrue($response->getData()->success);
        $this->assertEquals('Workflow actualizado exitosamente', $response->getData()->message);
    }

    /** @test */
    public function destroy_deletes_workflow()
    {
        // Crear workflow de prueba
        $workflow = Workflow::create([
            'nombre' => 'Workflow a Eliminar',
            'codigo' => 'WF-DEL',
            'tipo' => 'tramite',
            'activo' => true,
            'gerencia_id' => $this->gerencia->id,
            'created_by' => $this->user->id
        ]);

        // Configurar mock del repositorio
        $this->workflowRepo->shouldReceive('delete')
            ->once()
            ->with($workflow->id)
            ->andReturn(true);

        // Hacer la petición
        $request = Request::create("/workflows/{$workflow->id}", 'DELETE');
        $response = $this->controller->destroy($workflow);

        // Verificar la respuesta
        $this->assertTrue($response->getData()->success);
        $this->assertEquals('Workflow eliminado exitosamente', $response->getData()->message);
    }

    /** @test */
    public function duplicate_creates_workflow_copy()
    {
        // Crear workflow original
        $workflow = Workflow::create([
            'nombre' => 'Workflow Original',
            'codigo' => 'WF-ORIG',
            'tipo' => 'tramite',
            'activo' => true,
            'gerencia_id' => $this->gerencia->id,
            'created_by' => $this->user->id
        ]);

        // Configurar mock del repositorio
        $this->workflowRepo->shouldReceive('duplicate')
            ->once()
            ->with($workflow->id)
            ->andReturn(new Workflow([
                'nombre' => 'Workflow Original (Copia)',
                'codigo' => 'WF-ORIG-COPY',
                'tipo' => 'tramite',
                'activo' => true,
                'gerencia_id' => $this->gerencia->id,
                'created_by' => $this->user->id
            ]));

        // Hacer la petición
        $response = $this->controller->duplicate($workflow->id);

        // Verificar la respuesta
        $this->assertTrue($response->getData()->success);
        $this->assertEquals('Workflow duplicado exitosamente', $response->getData()->message);
    }
}