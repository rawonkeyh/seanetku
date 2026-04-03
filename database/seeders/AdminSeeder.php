<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'username' => 'admin',
            'email' => 'admin@voucher.com',
            'password' => Hash::make('admin123'),
            'name' => 'Administrator',
        ]);

        $this->command->info('✅ Default admin created:');
        $this->command->info('   Username: admin');
        $this->command->info('   Password: admin123');
    }
}
