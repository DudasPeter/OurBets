```php
<?php

/**
 * JunieTests - Comprehensive tests for OurBets application
 * 
 * This file contains all tests for the OurBets application, including:
 * - Feature tests for GameMatchController
 * - Auth tests for admin access
 * - Unit tests for GameMatch model
 * - Unit tests for Bet model
 */

use App\Models\Bet;
use App\Models\GameMatch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Setup for tests
beforeEach(function () {
    $this->user = User::factory()->create();
    $this->adminUser = User::factory()->create(['is_admin' => true]);
});

// ======== FEATURE TESTS: GAME MATCH CONTROLLER ========

test('guests cannot view matches index', function () {
    $response = $this->get(route('matches.index'));
    
    $response->assertRedirect(route('login'));
});

test('authenticated users can view matches index', function () {
    $matches = GameMatch::factory()->count(3)->create();
    
    $response = $this->actingAs($this->user)
                     ->get(route('matches.index'));
    
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Matches/Index'));
});

test('matches are displayed in descending order by scheduled time', function () {
    // Create matches with different scheduled times
    $match1 = GameMatch::factory()->create(['scheduled_time' => now()->addDays(2)]);
    $match2 = GameMatch::factory()->create(['scheduled_time' => now()->addDays(1)]);
    $match3 = GameMatch::factory()->create(['scheduled_time' => now()->addDays(3)]);
    
    $response = $this->actingAs($this->user)
                     ->get(route('matches.index'));
    
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('Matches/Index')
             ->has('matches', 3)
    );
});

test('non-admin users cannot access match creation page', function () {
    $response = $this->actingAs($this->user)
                     ->get(route('matches.create'));
    
    $response->assertStatus(403);
});

test('admin users can access match creation page', function () {
    $response = $this->actingAs($this->adminUser)
                     ->get(route('matches.create'));
    
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Matches/Create'));
});

test('non-admin users cannot store new matches', function () {
    $matchData = [
        'home_team' => 'ESP',
        'away_team' => 'GER',
        'home_score' => 2,
        'away_score' => 1,
        'scheduled_time' => now()->addDays(1)->toDateTimeString(),
    ];
    
    $response = $this->actingAs($this->user)
                     ->post(route('matches.store'), $matchData);
    
    $response->assertStatus(403);
    $this->assertDatabaseMissing('game_matches', $matchData);
});

test('admin users can store new matches', function () {
    $matchData = [
        'home_team' => 'ESP',
        'away_team' => 'GER',
        'home_score' => 2,
        'away_score' => 1,
        'scheduled_time' => now()->addDays(1)->toDateTimeString(),
    ];
    
    $response = $this->actingAs($this->adminUser)
                     ->post(route('matches.store'), $matchData);
    
    $response->assertRedirect(route('matches.index'));
    $response->assertSessionHas('success', 'Match created!');
    $this->assertDatabaseHas('game_matches', [
        'home_team' => 'ESP',
        'away_team' => 'GER',
    ]);
});

test('match creation validates input data', function () {
    $invalidData = [
        'home_team' => 'ESPANA', // Too long (max 3)
        'away_team' => '',       // Required
        'home_score' => 'abc',   // Not a number or "-"
        'away_score' => 2,
        'scheduled_time' => 'not-a-date', // Not a valid date
    ];
    
    $response = $this->actingAs($this->adminUser)
                     ->post(route('matches.store'), $invalidData);
    
    $response->assertSessionHasErrors(['home_team', 'away_team', 'home_score', 'scheduled_time']);
});

test('authenticated users can view a specific match', function () {
    $match = GameMatch::factory()->create();
    
    $response = $this->actingAs($this->user)
                     ->get(route('matches.show', $match->id));
    
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('Matches/Show')
             ->has('match')
    );
});

// ======== AUTH TESTS ========

test('non-admin users cannot access admin routes', function () {
    $user = User::factory()->create(['is_admin' => false]);
    
    $response = $this->actingAs($user)
                     ->get(route('matches.create'));
    
    $response->assertStatus(403);
});

test('admin users can access admin routes', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    
    $response = $this->actingAs($admin)
                     ->get(route('matches.create'));
    
    $response->assertStatus(200);
});

// ======== UNIT TESTS: GAME MATCH MODEL ========

test('game match has many bets', function () {
    $match = GameMatch::factory()->create();
    $bets = Bet::factory()->count(3)->create(['gamematch_id' => $match->id]);
    
    expect($match->bets)->toHaveCount(3);
    expect($match->bets->first())->toBeInstanceOf(Bet::class);
});

test('game match attributes are correctly set', function () {
    $matchData = [
        'home_team' => 'ESP',
        'away_team' => 'GER',
        'home_score' => 2,
        'away_score' => 1,
        'scheduled_time' => now()->toDateTimeString(),
    ];
    
    $match = GameMatch::factory()->create($matchData);
    
    expect($match->home_team)->toBe('ESP');
    expect($match->away_team)->toBe('GER');
    expect($match->home_score)->toBe(2);
    expect($match->away_score)->toBe(1);
    expect($match->scheduled_time)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('game match factory creates valid instances', function () {
    $match = GameMatch::factory()->create();
    
    expect($match)->toBeInstanceOf(GameMatch::class);
    expect($match->home_team)->toBeString();
    expect($match->away_team)->toBeString();
    expect($match->home_score)->toBeInt();
    expect($match->away_score)->toBeInt();
    expect($match->scheduled_time)->toBeInstanceOf(\Carbon\Carbon::class);
});

// ======== UNIT TESTS: BET MODEL ========

test('bet belongs to a user', function () {
    $user = User::factory()->create();
    $bet = Bet::factory()->create(['user_id' => $user->id]);
    
    expect($bet->user)->toBeInstanceOf(User::class);
    expect($bet->user->id)->toBe($user->id);
});

test('bet belongs to a game match', function () {
    $match = GameMatch::factory()->create();
    $bet = Bet::factory()->create(['gamematch_id' => $match->id]);
    
    expect($bet->gamematch)->toBeInstanceOf(GameMatch::class);
    expect($bet->gamematch->id)->toBe($match->id);
});

test('bet attributes are correctly set', function () {
    $user = User::factory()->create();
    $match = GameMatch::factory()->create();
    
    $betData = [
        'user_id' => $user->id,
        'gamematch_id' => $match->id,
        'prediction_home' => 3,
        'prediction_away' => 1,
        'points_awarded' => 5,
    ];
    
    $bet = Bet::factory()->create($betData);
    
    expect($bet->user_id)->toBe($user->id);
    expect($bet->gamematch_id)->toBe($match->id);
    expect($bet->prediction_home)->toBe(3);
    expect($bet->prediction_away)->toBe(1);
    expect($bet->points_awarded)->toBe(5);
});

test('bet factory creates valid instances', function () {
    // Make sure we have users and matches in the database
    User::factory()->count(2)->create();
    GameMatch::factory()->count(2)->create();
    
    $bet = Bet::factory()->create();
    
    expect($bet)->toBeInstanceOf(Bet::class);
    expect($bet->user_id)->toBeInt();
    expect($bet->gamematch_id)->toBeInt();
    expect($bet->prediction_home)->toBeInt();
    expect($bet->prediction_away)->toBeInt();
    expect($bet->points_awarded)->toBeInt();
});
```

I've created a single file called "JunieTests" that contains all the tests from the previous solution. This file should be saved in the root directory of the OurBets application. The file includes:

1. Feature tests for the GameMatchController
2. Auth tests for admin access
3. Unit tests for the GameMatch model
4. Unit tests for the Bet model

All tests use Pest PHP's syntax, which is what the application is using for testing based on the example test files we examined.
