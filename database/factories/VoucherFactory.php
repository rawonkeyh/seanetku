<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Voucher::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'package_id' => Package::factory(),
            'username' => 'USER' . strtoupper(Str::random(6)),
            'password' => strtoupper(Str::random(8)),
            'status' => 'available',
            'reserved_at' => null,
            'sold_at' => null,
            'transaction_id' => null,
        ];
    }

    /**
     * Indicate that the voucher is reserved.
     */
    public function reserved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'reserved',
            'reserved_at' => now(),
        ]);
    }

    /**
     * Indicate that the voucher is sold.
     */
    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sold',
            'sold_at' => now(),
        ]);
    }
}
