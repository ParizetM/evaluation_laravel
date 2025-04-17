<?php

namespace Database\Factories\Reunion;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reunion\Salle>
 */
class SalleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => $this->faker->word(),
            'capacite' => $this->faker->numberBetween(10, 100),
            'surface' => $this->faker->numberBetween(20, 500),
        ];
    }
}
