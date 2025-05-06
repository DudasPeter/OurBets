<?php

namespace Database\Factories;

use App\Models\GameMatch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bet>
 */
class BetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomElement(User::all()->pluck('id')->toArray()),
            'gamematch_id' => $this->faker->randomElement(GameMatch::all()->pluck('id')->toArray()),
            'prediction_home' => $this->faker->randomDigit(),
            'prediction_away' => $this->faker->randomDigit(),
        ];
    }
}
