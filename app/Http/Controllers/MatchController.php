<?php

namespace App\Http\Controllers;

use App\Models\Match;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class MatchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Apply admin middleware to admin-only methods
        $this->middleware('admin')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the matches.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $upcomingMatches = Match::upcoming()->load('bets');
        $recentMatches = Match::recent()->load('bets');

        return Inertia::render('Matches/Index', [
            'upcomingMatches' => $upcomingMatches,
            'recentMatches' => $recentMatches,
            'isAdmin' => Auth::user()->isAdmin(),
        ]);
    }

    /**
     * Display the specified match.
     *
     * @param  \App\Models\Match  $match
     * @return \Inertia\Response
     */
    public function show(Match $match)
    {
        $match->load(['bets' => function ($query) {
            $query->where('user_id', Auth::id())->orderBy('created_at', 'desc');
        }]);

        // Get overall bet statistics for this match
        $totalBets = $match->bets()->count();
        $homeWinBets = $match->bets()->whereRaw('predicted_home_score > predicted_away_score')->count();
        $awayWinBets = $match->bets()->whereRaw('predicted_home_score < predicted_away_score')->count();
        $drawBets = $match->bets()->whereRaw('predicted_home_score = predicted_away_score')->count();

        return Inertia::render('Matches/Show', [
            'match' => $match,
            'userBet' => $match->bets->first(), // User's bet for this match, if any
            'stats' => [
                'totalBets' => $totalBets,
                'homeWinPercentage' => $totalBets > 0 ? round(($homeWinBets / $totalBets) * 100) : 0,
                'awayWinPercentage' => $totalBets > 0 ? round(($awayWinBets / $totalBets) * 100) : 0,
                'drawPercentage' => $totalBets > 0 ? round(($drawBets / $totalBets) * 100) : 0,
            ],
            'canBet' => $match->isScheduled() && !$match->match_time->isPast(),
            'isAdmin' => Auth::user()->isAdmin(),
        ]);
    }

    /**
     * Store a newly created match in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'home_team' => 'required|string|max:255',
                'away_team' => 'required|string|max:255',
                'match_time' => 'required|date|after:now',
            ]);

            Match::create([
                'home_team' => $validated['home_team'],
                'away_team' => $validated['away_team'],
                'match_time' => $validated['match_time'],
                'status' => 'scheduled',
            ]);

            return redirect()->route('matches.index')
                ->with('success', 'Match created successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Update the specified match in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Match $match)
    {
        try {
            // Different validation rules based on match status
            if ($match->isScheduled()) {
                $validated = $request->validate([
                    'home_team' => 'sometimes|required|string|max:255',
                    'away_team' => 'sometimes|required|string|max:255',
                    'match_time' => 'sometimes|required|date',
                    'status' => 'sometimes|required|in:scheduled,in_progress',
                ]);

                $match->update($validated);
            } elseif ($match->isInProgress() || $match->isFinished()) {
                $validated = $request->validate([
                    'home_score' => 'required|integer|min:0',
                    'away_score' => 'required|integer|min:0',
                    'status' => 'required|in:in_progress,finished',
                ]);

                if ($validated['status'] === 'finished' && $match->status !== 'finished') {
                    // If match is being marked as finished, call the setFinished method
                    // to update scores and calculate points
                    $match->setFinished($validated['home_score'], $validated['away_score']);
                } else {
                    // Otherwise just update the attributes
                    $match->update($validated);
                }
            }

            return redirect()->route('matches.show', $match)
                ->with('success', 'Match updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Remove the specified match from storage.
     *
     * @param  \App\Models\Match  $match
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Match $match)
    {
        // Only scheduled matches with no bets can be deleted
        if (!$match->isScheduled() || $match->bets()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete a match that is in progress, finished, or has bets placed on it.');
        }

        $match->delete();

        return redirect()->route('matches.index')
            ->with('success', 'Match deleted successfully.');
    }
}

