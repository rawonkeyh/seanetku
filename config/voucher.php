<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Voucher Reservation Timeout (in minutes)
    |--------------------------------------------------------------------------
    |
    | Time before a reserved voucher is automatically released back to available
    | Default: 15 minutes (synced with Midtrans expiry)
    |
    */
    'reservation_timeout' => env('VOUCHER_RESERVATION_TIMEOUT', 15),
    
    /*
    |--------------------------------------------------------------------------
    | Low Stock Alert Threshold
    |--------------------------------------------------------------------------
    |
    | Trigger alert when available vouchers fall below this number
    | Default: 10 vouchers per package
    |
    */
    'low_stock_threshold' => env('VOUCHER_LOW_STOCK_THRESHOLD', 10),
    
    /*
    |--------------------------------------------------------------------------
    | Voucher Types & Packages
    |--------------------------------------------------------------------------
    */
    'types' => [
        'time' => 'Time-based (hours/days)',
        'quota' => 'Quota-based (GB)',
    ],
    
];
