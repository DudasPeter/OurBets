<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\Match;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class BetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's bets.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        $bets = $user->bets()
            ->with('match')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Bets/Index', [
            'bets' => $bets,
            'totalPoints' => $user->total_points,
            'rank' => $user->rank,
        ]);
    }

    /**
     * Display the specified bet.
     *
     * @param  \App\Models\Bet  $bet
     * @return \Inertia\Response
     */
    public function show(Bet $bet)
    {
        // Ensure users can only view their own bets
        if ($bet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $bet->load('match');

        return Inertia::render('Bets/Show', [
            'bet' => $bet,
            'match' => $bet->match,
        ]);
    }

    /**
     * Store a newly created bet in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'match_id' => 'required|exists:matches,id',
                'predicted_home_score' => 'required|integer|min:0',
                'predicted_away_score' => 'required|integer|min:0',
            ]);

            // Check if a bet can be placed on this match
            if (!Bet::canBePlaced($validated['match_id'])) {
                return redirect()->back()
                    ->with('error', 'Cannot place a bet on this match. It may have already started or finished.');
            }

            // Check if user already has a bet for this match
            $existingBet = Bet::where('user_id', Auth::id())
                ->where('match_id', $validated['match_id'])
                ->first();

            if ($existingBet) {
                // Update existing bet
                $existingBet->update([
                    'predicted_home_score' => $validated['predicted_home_score'],
                    'predicted_away_score' => $validated['predicted_away_score'],
                ]);

                return redirect()->route('matches.show', $validated['match_id'])
                    ->with('success', 'Your bet has been updated.');
            }

            // Create new bet
            Bet::create([
                'user_id' => Auth::id(),
                'match_id' => $validated['match_id'],
                'predicted_home_score' => $validated['predicted_home_score'],
                'predicted_away_score' => $validated['predicted_away_score'],
                'points_earned' => 0,
            ]);

            return redirect()->route('matches.show', $validated['match_id'])
                ->with('success', 'Your bet has been placed successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
}

