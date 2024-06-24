<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripRequestDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = null;
        $userType = null;

        if ($this->customer && $this->customer->users2) {
            $user = $this->customer->users2;
            $userType = 'Customer';
        } elseif ($this->transporter && $this->transporter->users2) {
            $user = $this->transporter->users2;
            $userType = 'Transporter';
        }
        //dd($this->customer);
        return [
            'id' => $this->id,
            'source' => $this->source,
            'destination' => $this->destination,
            'amount' => $this->amount,
            'status' => $this->status,
            'customer' => $this->customer ? [
                'id' => $this->customer->id,
                'phone_number' => $this->customer->phone_number,
                'user' => $this->customer->users2 ? [
                    'user_id' => $this->customer->users2->user_id,
                    'name' => $this->customer->users2->name,
                ] : null,
            ] : null,
            'transporter' => $this->transporter ? [
                'id' => $this->transporter->id,
                'phone_number' => $this->transporter->phone_number,
                'user' => $this->transporter->users2 ? [
                    'user_id' => $this->transporter->users2->id,
                    'name' => $this->transporter->users2->name,
                    'email' => $this->transporter->users2->email,
                ] : null,
            ] : null,
            // 'user' => $user ? [
            //     'id' => $user->user_id,
            //     'name' => $user->name,
            //     'phone_number' => $user->resource->phone_number,
            //     'resource_type' => $userType,
            // ] : null,
        ];
    }
}
           