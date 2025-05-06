<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bet extends Model
{
    use HasFactory;

    protected $table = 'bets';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gamematch(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class);
    }
}
