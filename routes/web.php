<?php

use App\Http\Controllers\BetController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MatchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected routes that require authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Match routes - accessible to all authenticated users
    Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
    Route::get('/matches/{match}', [MatchController::class, 'show'])->name('matches.show');

    // Match routes - admin only
    Route::middleware('admin')->group(function () {
        Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');
        Route::put('/matches/{match}', [MatchController::class, 'update'])->name('matches.update');
        Route::delete('/matches/{match}', [MatchController::class, 'destroy'])->name('matches.destroy');
    });

    // Bet routes
    Route::get('/bets', [BetController::class, 'index'])->name('bets.index');
    Route::get('/bets/{bet}', [BetController::class, 'show'])->name('bets.show');
    Route::post('/bets', [BetController::class, 'store'])->name('bets.store');

    // Leaderboard routes
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('/leaderboard/user-stats', [LeaderboardController::class, 'userStats'])->name('leaderboard.user-stats');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
