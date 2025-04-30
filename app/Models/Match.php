<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Match extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'home_team',
        'away_team',
        'match_time',
        'home_score',
        'away_score',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'match_time' => 'datetime',
        'home_score' => 'integer',
        'away_score' => 'integer',
    ];

    /**
     * Get the bets for the match.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class);
    }

    /**
     * Check if the match is scheduled.
     *
     * @return bool
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Check if the match is in progress.
     *
     * @return bool
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if the match is finished.
     *
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    /**
     * Set the match as in progress.
     *
     * @return void
     */
    public function setInProgress(): void
    {
        $this->status = 'in_progress';
        $this->save();
    }

    /**
     * Set the match as finished and update scores.
     *
     * @param int $homeScore
     * @param int $awayScore
     * @return void
     */
    public function setFinished(int $homeScore, int $awayScore): void
    {
        $this->home_score = $homeScore;
        $this->away_score = $awayScore;
        $this->status = 'finished';
        $this->save();

        // Update points for all bets associated with this match
        $this->calculatePointsForAllBets();
    }

    /**
     * Calculate points for all bets of this match.
     *
     * @return void
     */
    public function calculatePointsForAllBets(): void
    {
        if (!$this->isFinished() || $this->home_score === null || $this->away_score === null) {
            return;
        }

        foreach ($this->bets as $bet) {
            $bet->calculatePoints();
        }
    }

    /**
     * Get the winner of the match (home, away, or draw).
     *
     * @return string|null
     */
    public function getWinner(): ?string
    {
        if (!$this->isFinished() || $this->home_score === null || $this->away_score === null) {
            return null;
        }

        if ($this->home_score > $this->away_score) {
            return 'home';
        } elseif ($this->home_score < $this->away_score) {
            return 'away';
        } else {
            return 'draw';
        }
    }

    /**
     * Get a formatted representation of the match.
     *
     * @return string
     */
    public function getFormattedMatchAttribute(): string
    {
        $result = "{$this->home_team} vs {$this->away_team}";
        
        if ($this->isFinished() && $this->home_score !== null && $this->away_score !== null) {
            $result .= " ({$this->home_score}:{$this->away_score})";
        }
        
        return $result;
    }

    /**
     * Get upcoming matches.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function upcoming(int $limit = 10)
    {
        return self::where('status', 'scheduled')
            ->where('match_time', '>', now())
            ->orderBy('match_time')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent matches.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function recent(int $limit = 10)
    {
        return self::where('status', 'finished')
            ->orderBy('match_time', 'desc')
            ->limit($limit)
            ->get();
    }
}

