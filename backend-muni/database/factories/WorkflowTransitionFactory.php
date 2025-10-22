<?php

namespace Database\Factories;

use App\Models\WorkflowTransition;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowTransitionFactory extends Factory
{
    protected $model = WorkflowTransition::class;

    public function definition()
    {
        return [
            'workflow_id' => null, // Se debe asignar después
            'from_step_id' => null, // Se debe asignar después
            'to_step_id' => null, // Se debe asignar después
            'nombre' => $this->faker->sentence(2),
            'descripcion' => $this->faker->paragraph(),
            'condicion' => $this->faker->word(),
            'reglas' => null,
            'automatica' => $this->faker->boolean(20),
            'orden' => $this->faker->numberBetween(0, 10),
            'activo' => $this->faker->boolean(90)
        ];
    }
}