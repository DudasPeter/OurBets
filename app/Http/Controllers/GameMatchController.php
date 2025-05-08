<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
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
        $games = GameMatch::orderBy('scheduled_time', 'desc')->get()
        ->map(function ($game) {
            $game->scheduled_time = Carbon::parse($game->scheduled_time)->isoFormat('DD.MM.  ddd   HH:mm');
            return $game;
        });

        return inertia('Matches/Index', [
            'matches' => $games
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
        //TODO dd($request is working);

        $data = $request->validate([
            'home_team' => 'required|string|max:3',
            'away_team' => 'required|string|max:3',
            'home_score' => 'required|integer',
            'away_score' => 'required|integer',
            'scheduled_time' => 'required',
        ]);

        //TODO dd($data); is not working, Maybe error ???? try to add inputerror in matches/create


        $match = GameMatch::create([
            ...$data
        ]);

        return to_route('matches.index')->with('success', 'Match created!');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
