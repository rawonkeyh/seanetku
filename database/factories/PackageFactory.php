<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Package::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['time', 'quota'];
        $type = fake()->randomElement($types);

        if ($type === 'time') {
            return [
                'name' => 'Voucher ' . fake()->numberBetween(1, 24) . ' Jam',
                'type' => 'time',
                'value' => fake()->numberBetween(1, 24) . ' hours',
                'value_numeric' => fake()->numberBetween(1, 24),
                'unit' => 'hour',
                'price' => fake()->numberBetween(5000, 100000),
                'description' => fake()->sentence(),
                'is_active' => true,
            ];
        }

        return [
            'name' => 'Voucher ' . fake()->numberBetween(1, 20) . ' GB',
            'type' => 'quota',
            'value' => fake()->numberBetween(1, 20) . ' GB',
            'value_numeric' => fake()->numberBetween(1024, 20480),
            'unit' => 'MB',
            'price' => fake()->numberBetween(10000, 150000),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
