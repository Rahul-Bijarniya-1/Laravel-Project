<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPhoneNumberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Assuming phone_number is directly accessible from the User model
        return [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'phone_number' => $this->resource->phone_number, // Adjust as per your actual model structure
            'resource_type' => $this->resource_type,
            'resource_id' => $this->resource_id,
        ];
    }
}
