<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BetResource extends JsonResource
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
            'game'=> GameResource::make($this->whenLoaded('game')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'prediction_home' => $this->prediction_home,
            'prediction_away' => $this->prediction_away,
            'points_awarded' => $this->points_awarded,
        ];
    }
}
