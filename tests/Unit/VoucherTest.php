<?php

namespace Tests\Unit;

use App\Models\Package;
use App\Models\Voucher;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoucherTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test voucher status methods.
     */
    public function test_voucher_status_methods(): void
    {
        $package = Package::factory()->create();
        $voucher = Voucher::factory()->create([
            'package_id' => $package->id,
            'status' => 'available',
        ]);

        $this->assertTrue($voucher->isAvailable());
        $this->assertFalse($voucher->isReserved());
        $this->assertFalse($voucher->isSold());
    }

    /**
     * Test voucher reservation.
     */
    public function test_voucher_can_be_reserved(): void
    {
        $package = Package::factory()->create();
        $voucher = Voucher::factory()->create([
            'package_id' => $package->id,
            'status' => 'available',
        ]);

        $result = $voucher->reserve(1);

        $this->assertTrue($result);
        $this->assertEquals('reserved', $voucher->fresh()->status);
        $this->assertNotNull($voucher->fresh()->reserved_at);
    }
}
