<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoucherService
{
    /**
     * Reserve an available voucher for a transaction
     * Uses database locking to prevent double assignment
     */
    public function reserveVoucher(int $packageId, int $transactionId): ?Voucher
    {
        return DB::transaction(function () use ($packageId, $transactionId) {
            // Lock and get first available voucher
            $voucher = Voucher::where('package_id', $packageId)
                ->where('status', 'available')
                ->lockForUpdate()
                ->first();

            if (!$voucher) {
                Log::warning('No available voucher for package', [
                    'package_id' => $packageId,
                    'transaction_id' => $transactionId,
                ]);
                return null;
            }

            // Reserve the voucher
            $voucher->reserve();

            Log::info('Voucher Reserved', [
                'voucher_id' => $voucher->id,
                'transaction_id' => $transactionId,
                'reserved_at' => $voucher->reserved_at,
            ]);

            return $voucher;
        });
    }

    /**
     * Release expired reservations
     * Called by scheduler every minute
     */
    public function releaseExpiredReservations(): int
    {
        $vouchers = Voucher::expiredReservations()->get();
        $count = 0;

        foreach ($vouchers as $voucher) {
            if ($voucher->release()) {
                $count++;
                
                Log::info('Voucher Released (Timeout)', [
                    'voucher_id' => $voucher->id,
                    'release_reason' => 'timeout',
                ]);
            }
        }

        if ($count > 0) {
            Log::info("Released {$count} expired voucher reservations");
        }

        return $count;
    }

    /**
     * Mark voucher as sold
     */
    public function markVoucherAsSold(int $voucherId): bool
    {
        $voucher = Voucher::find($voucherId);
        
        if (!$voucher) {
            Log::error('Voucher not found when marking as sold', [
                'voucher_id' => $voucherId,
            ]);
            return false;
        }

        $result = $voucher->markAsSold();

        if ($result) {
            Log::info('Voucher Sold', [
                'voucher_id' => $voucher->id,
                'sold_at' => $voucher->sold_at,
            ]);
        }

        return $result;
    }

    /**
     * Release a specific voucher
     */
    public function releaseVoucher(int $voucherId, string $reason = 'manual'): bool
    {
        $voucher = Voucher::find($voucherId);
        
        if (!$voucher) {
            return false;
        }

        $result = $voucher->release();

        if ($result) {
            Log::info('Voucher Released', [
                'voucher_id' => $voucher->id,
                'release_reason' => $reason,
            ]);
        }

        return $result;
    }

    /**
     * Check low stock for all packages
     */
    public function checkLowStock(): array
    {
        $lowStockPackages = [];
        $packages = Package::active()->get();

        foreach ($packages as $package) {
            if ($package->isLowStock()) {
                $lowStockPackages[] = [
                    'package_id' => $package->id,
                    'package_name' => $package->name,
                    'available_count' => $package->availableVouchersCount(),
                    'threshold' => config('voucher.low_stock_threshold'),
                ];

                Log::warning('Low Stock Alert', [
                    'package_id' => $package->id,
                    'package_name' => $package->name,
                    'available_count' => $package->availableVouchersCount(),
                ]);
            }
        }

        return $lowStockPackages;
    }

    /**
     * Get stock summary for a package
     */
    public function getPackageStockSummary(int $packageId): array
    {
        $package = Package::find($packageId);
        
        if (!$package) {
            return [];
        }

        return [
            'package_id' => $package->id,
            'package_name' => $package->name,
            'total' => $package->vouchers()->count(),
            'available' => $package->availableVouchersCount(),
            'reserved' => $package->reservedVouchersCount(),
            'sold' => $package->soldVouchersCount(),
            'is_low_stock' => $package->isLowStock(),
        ];
    }
}
