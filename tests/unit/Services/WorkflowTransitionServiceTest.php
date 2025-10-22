<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\WorkflowTransitionService;
use App\Models\Expediente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repositories\UserRepository;

class WorkflowTransitionServiceTest extends TestCase
{
    use RefreshDatabase;

    private WorkflowTransitionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new WorkflowTransitionService(new UserRepository());
    }

    /** @test */
    public function it_validates_user_permissions_before_transition()
    {
        // Arrange
        $expediente = Expediente::factory()->create(['estado' => 'pendiente']);
        $user = User::factory()->create();
        $this->actingAs($user);

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No tiene permisos para aprobar expedientes');

        // Act
        $this->service->transition($expediente, 'approve');
    }

    /** @test */
    public function it_updates_status_and_dates_on_valid_transition()
    {
        // Arrange
        $expediente = Expediente::factory()->create(['estado' => 'pendiente']);
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act
        $result = $this->service->transition($expediente, 'transfer', ['comentario' => 'Prueba']);

        // Assert
        $this->assertEquals('en_progreso', $result->estado);
        $this->assertNotNull($result->fecha_actualizacion);
    }
}
