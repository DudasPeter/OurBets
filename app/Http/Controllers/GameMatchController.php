<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class GameMatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Game::orderBy('scheduled_time', 'desc')->get()
        ->map(function ($game) {
            $game->scheduled_time = Carbon::parse($game->scheduled_time)->isoFormat('DD.MM.  ddd   HH:mm');
            return $game;
        });

        return inertia('Matches/Index', [
            'matches' => GameResource::collection($games),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Matches/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'home_team' => 'required|string|max:3',
            'away_team' => 'required|string|max:3',
            'home_score' => ['required',
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value) && $value !== '-') {
                        $fail('Home Score must be a number or -');
                    }
                }],
            'away_score' => ['required',
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value) && $value !== '-') {
                        $fail('Home Score must be a number or -');
                    }
                }],
            'scheduled_time' => 'required|date',
        ]);


        $match = Game::create([
            ...$data
        ]);

        $match->save();

        return to_route('matches.index')->with('success', 'Match created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $game = Game::with('bets.user')->findOrFail($id);

        return inertia('Matches/Show', [
            'match' => $game,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return inertia('Matches/Edit', [
            'match' => Game::where('id', $id)->firstOrFail(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'home_team' => 'required|string|max:3',
            'away_team' => 'required|string|max:3',
            'home_score' => ['required',
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value) && $value !== '-') {
                        $fail('Home Score must be a number or -');
                    }
                }],
            'away_score' => ['required',
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value) && $value !== '-') {
                        $fail('Home Score must be a number or -');
                    }
                }],
            'scheduled_time' => 'required|date',
        ]);

        $game = Game::where('id', $id)->firstOrFail();
        $game->update($data);

        return to_route('matches.index')->with('success', 'Match updated!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
