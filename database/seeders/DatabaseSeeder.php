<?php

namespace Database\Seeders;

use App\Models\Bet;
use App\Models\Game;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\BetFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $games = Game::factory(10)->create();

        $users = User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('admin'),
            'is_admin' => true,
        ]);

        foreach ($users as $user) {
            foreach ($games as $game) {
                Bet::factory()->create([
                    'user_id' => $user->id,
                    'game_id' => $game->id,
                    'prediction_home' => rand(0, 9),
                    'prediction_away' => rand(0, 9),
                ]);
            }
        }
    }
}
