<?php

namespace Database\Factories\Reunion;

use App\Models\Reunion\Reservation;
use App\Models\Reunion\Salle;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reunion\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $salle = Salle::count() < 10 ? Salle::factory() : Salle::inRandomOrder()->first();
        $user = User::count() < 10 ? User::factory() : User::inRandomOrder()->first();
        return [
            'salle_id' => $salle,
            'user_id' => $user,
            'start_time' => $this->faker->dateTimeBetween('now', '+1 week')->setTime($this->faker->numberBetween(8, 18), 0),
            'end_time' => function (array $attributes) {
                return (clone $attributes['start_time'])->modify('+2 hours');
            },
        ];
    }
}
