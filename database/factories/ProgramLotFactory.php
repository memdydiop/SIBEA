<?php

namespace Database\Factories;

use App\Models\Program;
use App\Models\ProgramLot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProgramLot>
 */
class ProgramLotFactory extends Factory
{
    protected $model = ProgramLot::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_id' => Program::factory(),
            'reference' => fake()->unique()->bothify('Lot ####'),
            'surface' => fake()->randomFloat(2, 50, 500),
            'price' => fake()->randomFloat(2, 5000000, 50000000),
            'status' => fake()->randomElement(['disponible', 'option', 'reserve', 'vendu']),
        ];
    }
}
