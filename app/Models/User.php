<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    /**
     * Get the bets that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class);
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the total points earned by the user.
     *
     * @return int
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->bets->sum('points_earned');
    }

    /**
     * Get the user's rank based on total points.
     *
     * @return int
     */
    public function getRankAttribute(): int
    {
        $userPoints = $this->total_points;
        $higherRankedUsers = User::where('id', '!=', $this->id)
            ->withCount(['bets as total_points' => function ($query) {
                $query->select(\DB::raw('SUM(points_earned)'));
            }])
            ->having('total_points', '>', $userPoints)
            ->count();

        return $higherRankedUsers + 1;
    }
}
