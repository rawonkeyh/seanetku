<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
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
            'package' => new PackageResource($this->whenLoaded('package')),
            'username' => $this->username,
            // Only show password if voucher is sold/paid
            'password' => $this->when(
                $this->status === 'sold',
                $this->password
            ),
            'status' => $this->status,
            'reserved_at' => $this->reserved_at?->toIso8601String(),
            'sold_at' => $this->sold_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
