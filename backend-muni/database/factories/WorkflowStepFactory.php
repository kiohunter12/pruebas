<?php

namespace Database\Factories;

use App\Models\WorkflowStep;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowStepFactory extends Factory
{
    protected $model = WorkflowStep::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->sentence(2),
            'codigo' => 'STEP-' . strtoupper($this->faker->unique()->lexify('???')),
            'descripcion' => $this->faker->paragraph(),
            'orden' => $this->faker->numberBetween(1, 10),
            'tipo' => $this->faker->randomElement(['inicio', 'normal', 'fin']),
            'tiempo_estimado' => $this->faker->numberBetween(60, 4320), // Entre 1 hora y 3 días en minutos
            'responsable_tipo' => 'gerencia',
            'responsable_id' => null, // Se debe asignar después
            'workflow_id' => null, // Se debe asignar después
            'activo' => $this->faker->boolean(90)
        ];
    }
}