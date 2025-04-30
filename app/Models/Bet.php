<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'match_id',
        'predicted_home_score',
        'predicted_away_score',
        'points_earned',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'predicted_home_score' => 'integer',
        'predicted_away_score' => 'integer',
        'points_earned' => 'integer',
    ];

    /**
     * Get the user that owns the bet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the match that the bet is for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(Match::class);
    }

    /**
     * Calculate and update the points earned based on the match result.
     *
     * @return void
     */
    public function calculatePoints(): void
    {
        $match = $this->match;
        
        // Ensure match is finished and has scores
        if (!$match->isFinished() || $match->home_score === null || $match->away_score === null) {
            return;
        }

        // 3 points for exact match result
        if ($this->predicted_home_score === $match->home_score && 
            $this->predicted_away_score === $match->away_score) {
            $this->points_earned = 3;
        } 
        // 2 points for correctly predicting a draw
        elseif ($this->isPredictedDraw() && $match->getWinner() === 'draw') {
            $this->points_earned = 2;
        }
        // 1 point for correctly predicting the winner
        elseif ($this->getPredictedWinner() === $match->getWinner() && $match->getWinner() !== 'draw') {
            $this->points_earned = 1;
        }
        // 0 points for incorrect prediction
        else {
            $this->points_earned = 0;
        }

        $this->save();
    }

    /**
     * Check if the bet predicts a draw.
     *
     * @return bool
     */
    public function isPredictedDraw(): bool
    {
        return $this->predicted_home_score === $this->predicted_away_score;
    }

    /**
     * Get the predicted winner (home, away, or draw).
     *
     * @return string
     */
    public function getPredictedWinner(): string
    {
        if ($this->predicted_home_score > $this->predicted_away_score) {
            return 'home';
        } elseif ($this->predicted_home_score < $this->predicted_away_score) {
            return 'away';
        } else {
            return 'draw';
        }
    }

    /**
     * Check if the prediction was correct.
     *
     * @return bool
     */
    public function wasCorrect(): bool
    {
        return $this->points_earned > 0;
    }

    /**
     * Check if the bet was for an exact score.
     *
     * @return bool
     */
    public function wasExactScore(): bool
    {
        return $this->points_earned === 3;
    }

    /**
     * Get a formatted representation of the prediction.
     *
     * @return string
     */
    public function getFormattedPredictionAttribute(): string
    {
        return "{$this->predicted_home_score} : {$this->predicted_away_score}";
    }

    /**
     * Validate that a bet can be placed on a match.
     *
     * @param int $matchId
     * @return bool
     */
    public static function canBePlaced(int $matchId): bool
    {
        $match = Match::find($matchId);
        
        if (!$match) {
            return false;
        }
        
        // Bets can only be placed on scheduled matches
        if (!$match->isScheduled()) {
            return false;
        }
        
        // Bets can only be placed before the match starts
        if ($match->match_time->isPast()) {
            return false;
        }
        
        return true;
    }
}

