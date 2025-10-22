<?php

namespace Database\Factories;

use App\Models\Gerencia;
use Illuminate\Database\Eloquent\Factories\Factory;

class GerenciaFactory extends Factory
{
    protected $model = Gerencia::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->company(),
            'codigo' => 'GER-' . strtoupper($this->faker->unique()->lexify('??')),
            'descripcion' => $this->faker->paragraph(),
            'gerencia_padre_id' => null, // Se puede asignar después si se necesita
            'responsable_id' => null, // Se debe asignar después
            'activo' => $this->faker->boolean(90)
        ];
    }
}