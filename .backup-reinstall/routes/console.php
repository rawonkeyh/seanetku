<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

/*
|--------------------------------------------------------------------------
| Console Routes / Scheduled Tasks
|--------------------------------------------------------------------------
*/

// Auto release reserved vouchers every minute
Schedule::command('vouchers:release-reserved')->everyMinute();

// Low stock alert check every hour
Schedule::command('vouchers:check-stock')->hourly();

// Clean old logs (optional)
Schedule::command('logs:clear')->daily();
