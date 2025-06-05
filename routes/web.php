<?php

use App\Http\Controllers\BetController;
use App\Http\Controllers\GameMatchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('matches/results', [GameMatchController::class, 'index'])->name('matches.index');
    Route::get('matches/results/{id}', [GameMatchController::class, 'show'])->name('matches.show');
    Route::get('bets', [BetController::class, 'index'])->name('bets.index');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('matches/create', [GameMatchController::class, 'create'])->name('matches.create');
    Route::post('matches/store', [GameMatchController::class, 'store'])->name('matches.store');
    Route::delete('matches/destroy/{id}', [GameMatchController::class, 'destroy'])->name('matches.destroy');
    Route::get('matches/results/{id}/edit', [GameMatchController::class, 'edit'])->name('matches.edit');
    Route::patch('matches/results/{id}/edit', [GameMatchController::class, 'update'])->name('matches.update');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
