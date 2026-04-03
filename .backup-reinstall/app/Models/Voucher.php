<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'username',
        'password',
        'status',
        'reserved_at',
        'sold_at',
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
        'sold_at' => 'datetime',
    ];

    protected $hidden = [
        'password', // Hide password by default
    ];

    /**
     * Get the package that owns the voucher
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Check if voucher is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if voucher is reserved
     */
    public function isReserved(): bool
    {
        return $this->status === 'reserved';
    }

    /**
     * Check if voucher is sold
     */
    public function isSold(): bool
    {
        return $this->status === 'sold';
    }

    /**
     * Check if reservation has expired
     */
    public function isReservationExpired(): bool
    {
        if (!$this->isReserved() || !$this->reserved_at) {
            return false;
        }

        $timeout = config('voucher.reservation_timeout', 15);
        return $this->reserved_at->addMinutes($timeout)->isPast();
    }

    /**
     * Reserve this voucher
     */
    public function reserve(): bool
    {
        return $this->update([
            'status' => 'reserved',
            'reserved_at' => now(),
        ]);
    }

    /**
     * Mark voucher as sold
     */
    public function markAsSold(): bool
    {
        return $this->update([
            'status' => 'sold',
            'sold_at' => now(),
        ]);
    }

    /**
     * Release reserved voucher back to available
     */
    public function release(): bool
    {
        return $this->update([
            'status' => 'available',
            'reserved_at' => null,
        ]);
    }

    /**
     * Scope to get available vouchers
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope to get reserved vouchers
     */
    public function scopeReserved($query)
    {
        return $query->where('status', 'reserved');
    }

    /**
     * Scope to get expired reservations
     */
    public function scopeExpiredReservations($query)
    {
        $timeout = config('voucher.reservation_timeout', 15);
        return $query->where('status', 'reserved')
            ->where('reserved_at', '<', now()->subMinutes($timeout));
    }
}
