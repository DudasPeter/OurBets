<?php

use App\Http\Controllers\BetController;
use App\Http\Controllers\GameMatchController;
use Illuminate\Support\Facades\Route;
use App\Models\Zuby;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome-ninka');
})->name('home');

Route::get('/zuby', function () {
    return Inertia::render('zuby');
})->name('zuby');

Route::get('/zubyForm', function () {
    return Inertia::render('zubyForm');
})->name('zubyForm');

Route::post('/zubyForm', function () {
    $validatedData = request()->validate([
        'name' => 'required|string|max:255',
    ]);

    Zuby::create([
        'name' => $validatedData['name'],
    ]);
    return redirect()->route('zuby')->with('success', 'Tvoj návrh bol úspěšně uložený! Doma sa n ato pozrieme a uvidíme, čo s tým ďalej. Ďakujeme za tvoju kreativitu!');
})->name('zubyForm');


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
