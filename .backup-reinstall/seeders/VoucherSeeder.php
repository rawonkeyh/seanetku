<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = Package::all();
        
        foreach ($packages as $package) {
            // Create 20 vouchers per package
            for ($i = 1; $i <= 20; $i++) {
                Voucher::create([
                    'package_id' => $package->id,
                    'username' => $this->generateUsername($package->id, $i),
                    'password' => $this->generatePassword(),
                    'status' => 'available',
                ]);
            }
        }

        $this->command->info('Vouchers seeded successfully!');
        $this->command->info('Generated ' . ($packages->count() * 20) . ' vouchers');
    }

    /**
     * Generate username
     */
    private function generateUsername(int $packageId, int $sequence): string
    {
        return sprintf('PKG%02d-%04d', $packageId, $sequence);
    }

    /**
     * Generate random password
     */
    private function generatePassword(int $length = 8): string
    {
        return strtoupper(Str::random($length));
    }
}
