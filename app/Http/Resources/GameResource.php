<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bets' => BetResource::collection($this->whenLoaded('bets')),
            'home_team' => $this->home_team,
            'away_team' => $this->away_team,
            'scheduled_time' => $this->scheduled_time,
            'home_score' => $this->home_score,
            'away_score' => $this->away_score,
        ];
    }
}
