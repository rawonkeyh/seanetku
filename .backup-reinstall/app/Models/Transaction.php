<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'package_id',
        'voucher_id',
        'amount',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Get the package for this transaction
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the voucher for this transaction
     */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if transaction is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if transaction is expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(): bool
    {
        return $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(): bool
    {
        return $this->update([
            'status' => 'failed',
        ]);
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(): bool
    {
        return $this->update([
            'status' => 'expired',
            'expired_at' => now(),
        ]);
    }

    /**
     * Generate unique order ID
     */
    public static function generateOrderId(): string
    {
        return 'VO-' . date('YmdHis') . '-' . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Scope to get pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get paid transactions
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
