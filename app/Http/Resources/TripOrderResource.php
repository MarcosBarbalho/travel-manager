<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TripOrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'created_at' => $this->created_at,
            'departure_at' => $this->departure_at,
            'destination' => $this->destination,
            'id' => $this->id,
            'requester_name' => $this->requester_name,
            'return_at' => $this->return_at,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
        ];
    }
}
