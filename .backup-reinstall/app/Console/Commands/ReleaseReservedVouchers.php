<?php

namespace App\Console\Commands;

use App\Services\VoucherService;
use Illuminate\Console\Command;

class ReleaseReservedVouchers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'vouchers:release-reserved';

    /**
     * The console command description.
     */
    protected $description = 'Release expired reserved vouchers back to available';

    protected VoucherService $voucherService;

    /**
     * Create a new command instance.
     */
    public function __construct(VoucherService $voucherService)
    {
        parent::__construct();
        $this->voucherService = $voucherService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for expired voucher reservations...');

        $count = $this->voucherService->releaseExpiredReservations();

        if ($count > 0) {
            $this->info("Released {$count} expired voucher reservation(s)");
        } else {
            $this->info('No expired reservations found');
        }

        return Command::SUCCESS;
    }
}
