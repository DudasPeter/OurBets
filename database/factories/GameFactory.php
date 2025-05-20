<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'home_team' => fake()->countryCode(),
            'away_team' => fake()->countryCode(),
            'scheduled_time' => fake()->dateTime(),
            'home_score' => fake()->randomDigit(),
            'away_score' => fake()->randomDigit(),
        ];
    }
}
