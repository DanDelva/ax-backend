<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExcuseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'reason' => $this->reason,
            'status' => $this->status,
            'file_path' => $this->file_path,
            'date' => $this->date,
            'created_at' => $this->created_at,
        ];
    }
}
