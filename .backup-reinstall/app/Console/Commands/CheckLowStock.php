<?php

namespace App\Console\Commands;

use App\Services\VoucherService;
use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'vouchers:check-stock';

    /**
     * The console command description.
     */
    protected $description = 'Check for low stock vouchers and send alerts';

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
        $this->info('Checking for low stock packages...');

        $lowStockPackages = $this->voucherService->checkLowStock();

        if (count($lowStockPackages) > 0) {
            $this->warn('Low stock alert for ' . count($lowStockPackages) . ' package(s):');
            
            foreach ($lowStockPackages as $package) {
                $this->line(sprintf(
                    '  - %s: %d available (threshold: %d)',
                    $package['package_name'],
                    $package['available_count'],
                    $package['threshold']
                ));
            }

            // Here you can add email/notification logic
            // Example: Mail::to('admin@example.com')->send(new LowStockAlert($lowStockPackages));
        } else {
            $this->info('All packages have sufficient stock');
        }

        return Command::SUCCESS;
    }
}
