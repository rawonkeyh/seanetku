<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_status',
        'payment_type',
        'fraud_status',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
    ];

    /**
     * Parent transaction by order_id
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'order_id', 'order_id');
    }

    /**
     * Log payment callback
     */
    public static function logCallback(array $data): self
    {
        return self::create([
            'order_id' => $data['order_id'] ?? null,
            'transaction_status' => $data['transaction_status'] ?? null,
            'payment_type' => $data['payment_type'] ?? null,
            'fraud_status' => $data['fraud_status'] ?? null,
            'raw_payload' => $data,
        ]);
    }
}
