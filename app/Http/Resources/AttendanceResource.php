<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
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
            'subject' => [
                'id' => $this->subject->id,
                'title' => $this->subject->title,
            ],
            'date' => $this->date,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
