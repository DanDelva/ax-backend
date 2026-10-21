<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
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
            'group_name' => $this->group_name,
            'student' => new UserResource($this->whenLoaded('user')),
            'specialty' => new SpecialtyResource($this->whenLoaded('specialty')),
            'created_at' => $this->created_at,
        ];
    }
}
