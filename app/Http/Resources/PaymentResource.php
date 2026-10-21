<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'amount' => (float) $this->amount,
            'concept' => $this->concept,
            'status' => $this->status,
            'payment_date' => $this->payment_date,
            'proof_path' => $this->proof_path,
            'student' => clone new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
        ];
    }
}
