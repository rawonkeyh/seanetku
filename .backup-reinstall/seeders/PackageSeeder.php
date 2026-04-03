<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            // Time-based packages
            [
                'name' => 'Voucher 1 Jam',
                'type' => 'time',
                'value' => '1 hour',
                'value_numeric' => 1,
                'unit' => 'hour',
                'price' => 5000,
                'description' => 'Voucher internet untuk 1 jam pemakaian',
                'is_active' => true,
            ],
            [
                'name' => 'Voucher 3 Jam',
                'type' => 'time',
                'value' => '3 hours',
                'value_numeric' => 3,
                'unit' => 'hour',
                'price' => 12000,
                'description' => 'Voucher internet untuk 3 jam pemakaian',
                'is_active' => true,
            ],
            [
                'name' => 'Voucher 1 Hari',
                'type' => 'time',
                'value' => '1 day',
                'value_numeric' => 24,
                'unit' => 'hour',
                'price' => 25000,
                'description' => 'Voucher internet untuk 1 hari (24 jam) pemakaian',
                'is_active' => true,
            ],
            [
                'name' => 'Voucher 3 Hari',
                'type' => 'time',
                'value' => '3 days',
                'value_numeric' => 72,
                'unit' => 'hour',
                'price' => 65000,
                'description' => 'Voucher internet untuk 3 hari (72 jam) pemakaian',
                'is_active' => true,
            ],
            [
                'name' => 'Voucher 1 Minggu',
                'type' => 'time',
                'value' => '7 days',
                'value_numeric' => 168,
                'unit' => 'hour',
                'price' => 120000,
                'description' => 'Voucher internet untuk 1 minggu (7 hari) pemakaian',
                'is_active' => true,
            ],

            // Quota-based packages
            [
                'name' => 'Voucher 1 GB',
                'type' => 'quota',
                'value' => '1 GB',
                'value_numeric' => 1024,
                'unit' => 'MB',
                'price' => 10000,
                'description' => 'Voucher internet dengan kuota 1 GB',
                'is_active' => true,
            ],
            [
                'name' => 'Voucher 3 GB',
                'type' => 'quota',
                'value' => '3 GB',
                'value_numeric' => 3072,
                'unit' => 'MB',
                'price' => 25000,
                'description' => 'Voucher internet dengan kuota 3 GB',
                'is_active' => true,
            ],
            [
                'name' => 'Voucher 5 GB',
                'type' => 'quota',
                'value' => '5 GB',
                'value_numeric' => 5120,
                'unit' => 'MB',
                'price' => 40000,
                'description' => 'Voucher internet dengan kuota 5 GB',
                'is_active' => true,
            ],
            [
                'name' => 'Voucher 10 GB',
                'type' => 'quota',
                'value' => '10 GB',
                'value_numeric' => 10240,
                'unit' => 'MB',
                'price' => 70000,
                'description' => 'Voucher internet dengan kuota 10 GB',
                'is_active' => true,
            ],
            [
                'name' => 'Voucher 20 GB',
                'type' => 'quota',
                'value' => '20 GB',
                'value_numeric' => 20480,
                'unit' => 'MB',
                'price' => 130000,
                'description' => 'Voucher internet dengan kuota 20 GB',
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }

        $this->command->info('Packages seeded successfully!');
    }
}
