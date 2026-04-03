<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'value',
        'value_numeric',
        'unit',
        'price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'value_numeric' => 'integer',
    ];

    /**
     * Get all vouchers for this package
     */
    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    /**
     * Get available vouchers count
     */
    public function availableVouchersCount(): int
    {
        return $this->vouchers()->where('status', 'available')->count();
    }

    /**
     * Get reserved vouchers count
     */
    public function reservedVouchersCount(): int
    {
        return $this->vouchers()->where('status', 'reserved')->count();
    }

    /**
     * Get sold vouchers count
     */
    public function soldVouchersCount(): int
    {
        return $this->vouchers()->where('status', 'sold')->count();
    }

    /**
     * Check if stock is low
     */
    public function isLowStock(): bool
    {
        $threshold = config('voucher.low_stock_threshold', 10);
        return $this->availableVouchersCount() < $threshold;
    }

    /**
     * Scope to get only active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
