<?php

use App\Http\Controllers\GameMatchController;
use App\Http\Middleware\IsAdmin;
use App\Models\GameMatch;
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
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('matches/create', [GameMatchController::class, 'create'])->name('matches.create');
    Route::post('match/store', [GameMatchController::class, 'store'])->name('match.store');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
