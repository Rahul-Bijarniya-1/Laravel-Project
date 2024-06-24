<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripRequestResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'transporter_id' => $this->transporter_id,
            'source' => $this->source,
            'destination' => $this->destination,
            'amount' => $this->amount,
            'status' => $this->status,
        ];
    }    
}
