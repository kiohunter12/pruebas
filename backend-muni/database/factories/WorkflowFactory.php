<?php

namespace Database\Factories;

use App\Models\Workflow;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowFactory extends Factory
{
    protected $model = Workflow::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->sentence(3),
            'codigo' => 'WF-' . strtoupper($this->faker->unique()->lexify('??-????')),
            'descripcion' => $this->faker->paragraph(),
            'tipo' => $this->faker->randomElement(['tramite', 'proceso', 'expediente']),
            'configuracion' => [
                'tipo_tramite_id' => rand(1, 10),
                'tipo_tramite_nombre' => $this->faker->word()
            ],
            'activo' => $this->faker->boolean(80),
            'gerencia_id' => fn() => \App\Models\Gerencia::factory(),
            'created_by' => fn() => \App\Models\User::factory()
        ];
    }
}