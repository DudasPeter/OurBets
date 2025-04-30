<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bet;
use App\Models\Match;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LeaderboardController extends Controller
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
     * Display the global leaderboard.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Get users with their total points
        $leaderboard = User::withCount(['bets as total_points' => function ($query) {
                $query->select(DB::raw('SUM(points_earned)'));
            }])
            ->orderByDesc('total_points')
            ->paginate(20);

        // Get the current user's rank
        $currentUser = Auth::user();

        return Inertia::render('Leaderboard/Index', [
            'leaderboard' => $leaderboard,
            'userRank' => $currentUser->rank,
            'userPoints' => $currentUser->total_points,
        ]);
    }

    /**
     * Display detailed stats for the current user.
     *
     * @return \Inertia\Response
     */
    public function userStats()
    {
        $user = Auth::user();
        
        // Get all bets by the user with match details
        $bets = $user->bets()->with('match')->get();
        
        // Calculate various statistics
        $totalBets = $bets->count();
        $correctPredictions = $bets->where('points_earned', '>', 0)->count();
        $exactScorePredictions = $bets->where('points_earned', 3)->count();
        $correctWinnerPredictions = $bets->where('points_earned', 1)->count();
        $correctDrawPredictions = $bets->where('points_earned', 2)->count();
        
        // Get accuracy percentages
        $accuracy = $totalBets > 0 ? round(($correctPredictions / $totalBets) * 100) : 0;
        $exactScoreAccuracy = $totalBets > 0 ? round(($exactScorePredictions / $totalBets) * 100) : 0;
        
        // Recent performance (last 10 bets)
        $recentBets = $bets->sortByDesc('created_at')->take(10);
        
        // Upcoming bets
        $upcomingMatches = Match::where('status', 'scheduled')
            ->where('match_time', '>', now())
            ->whereHas('bets', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['bets' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('match_time')
            ->get();

        return Inertia::render('Leaderboard/UserStats', [
            'user' => $user,
            'totalPoints' => $user->total_points,
            'rank' => $user->rank,
            'stats' => [
                'totalBets' => $totalBets,
                'correctPredictions' => $correctPredictions,
                'exactScorePredictions' => $exactScorePredictions,
                'correctWinnerPredictions' => $correctWinnerPredictions,
                'correctDrawPredictions' => $correctDrawPredictions,
                'accuracy' => $accuracy,
                'exactScoreAccuracy' => $exactScoreAccuracy,
            ],
            'recentBets' => $recentBets,
            'upcomingBets' => $upcomingMatches,
        ]);
    }
}

