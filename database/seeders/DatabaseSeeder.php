<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Bet;
use App\Models\Match;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create regular users
        $users = User::factory(10)->create();

        // Create IIHF2025 matches
        $teams = [
            'Finland', 'Sweden', 'Canada', 'Russia', 'USA', 
            'Czech Republic', 'Germany', 'Switzerland', 'Slovakia', 'Latvia'
        ];

        $now = now();

        // Create upcoming matches
        for ($i = 0; $i < 15; $i++) {
            // Ensure we don't have the same team playing against itself
            do {
                $homeTeamIndex = array_rand($teams);
                $awayTeamIndex = array_rand($teams);
            } while ($homeTeamIndex === $awayTeamIndex);

            Match::create([
                'home_team' => $teams[$homeTeamIndex],
                'away_team' => $teams[$awayTeamIndex],
                'match_time' => $now->addDays(rand(1, 30))->addHours(rand(1, 24)),
                'status' => 'scheduled',
            ]);
        }

        // Create in-progress matches
        for ($i = 0; $i < 2; $i++) {
            do {
                $homeTeamIndex = array_rand($teams);
                $awayTeamIndex = array_rand($teams);
            } while ($homeTeamIndex === $awayTeamIndex);

            Match::create([
                'home_team' => $teams[$homeTeamIndex],
                'away_team' => $teams[$awayTeamIndex],
                'match_time' => $now->subHours(rand(1, 3)),
                'home_score' => rand(0, 3),
                'away_score' => rand(0, 3),
                'status' => 'in_progress',
            ]);
        }

        // Create finished matches
        for ($i = 0; $i < 10; $i++) {
            do {
                $homeTeamIndex = array_rand($teams);
                $awayTeamIndex = array_rand($teams);
            } while ($homeTeamIndex === $awayTeamIndex);

            Match::create([
                'home_team' => $teams[$homeTeamIndex],
                'away_team' => $teams[$awayTeamIndex],
                'match_time' => $now->subDays(rand(1, 30)),
                'home_score' => rand(0, 5),
                'away_score' => rand(0, 5),
                'status' => 'finished',
            ]);
        }

        // Create some bets for regular users
        $matches = Match::all();

        foreach ($users as $user) {
            foreach ($matches as $match) {
                // 70% chance to place a bet on a match
                if (rand(1, 10) <= 7) {
                    $bet = Bet::create([
                        'user_id' => $user->id,
                        'match_id' => $match->id,
                        'predicted_home_score' => rand(0, 5),
                        'predicted_away_score' => rand(0, 5),
                        'points_earned' => 0,
                    ]);

                    // Calculate points for finished matches
                    if ($match->isFinished()) {
                        $bet->calculatePoints();
                    }
                }
            }
        }
    }
}
}
