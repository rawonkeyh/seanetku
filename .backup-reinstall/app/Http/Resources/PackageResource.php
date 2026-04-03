<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $availableCount = $this->when(
            $this->relationLoaded('vouchers'),
            fn() => $this->vouchers->where('status', 'available')->count(),
            0
        );

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'value' => $this->value,
            'value_numeric' => $this->value_numeric,
            'unit' => $this->unit,
            'price' => (float) $this->price,
            'formatted_price' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
            'stock' => [
                'available' => $availableCount,
                'is_available' => $availableCount > 0,
            ],
            'available_vouchers_count' => $availableCount, // Kept for backward compatibility
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
