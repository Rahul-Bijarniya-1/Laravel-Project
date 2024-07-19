<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'resource_type' => class_basename($this->resource_type), // Get the class name without namespace
            'resource_id' => $this->resource_id,
            'phone_number' => $this->resource->resource->phone_number,
        ];
    }
}
