<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    protected VoucherService $voucherService;
    protected MidtransService $midtransService;

    public function __construct(
        VoucherService $voucherService,
        MidtransService $midtransService
    ) {
        $this->voucherService = $voucherService;
        $this->midtransService = $midtransService;
    }

    /**
     * Create new transaction
     */
    public function createTransaction(array $data): array
    {
        return DB::transaction(function () use ($data) {
            // Get package
            $package = Package::active()->find($data['package_id']);
            
            if (!$package) {
                throw new \Exception('Package not found or inactive');
            }

            // Create transaction
            $transaction = Transaction::create([
                'order_id' => Transaction::generateOrderId(),
                'package_id' => $package->id,
                'amount' => $package->price,
                'status' => 'pending',
                'customer_name' => $data['customer_name'] ?? null,
                'customer_email' => $data['customer_email'] ?? null,
                'customer_phone' => $data['customer_phone'] ?? null,
            ]);

            Log::info('Transaction Created', [
                'transaction_id' => $transaction->id,
                'order_id' => $transaction->order_id,
                'package_id' => $package->id,
                'amount' => $transaction->amount,
            ]);

            // Reserve voucher
            $voucher = $this->voucherService->reserveVoucher(
                $package->id,
                $transaction->id
            );

            if (!$voucher) {
                // Rollback transaction if no voucher available
                throw new \Exception('No voucher available for this package');
            }

            // Update transaction with voucher_id
            $transaction->update(['voucher_id' => $voucher->id]);

            // Generate Midtrans payment
            $paymentData = $this->midtransService->createPayment($transaction);

            return [
                'success' => true,
                'transaction' => $transaction,
                'payment' => $paymentData,
            ];
        });
    }

    /**
     * Get transaction details
     */
    public function getTransaction(int $id): ?Transaction
    {
        return Transaction::with(['package', 'voucher'])->find($id);
    }

    /**
     * Get transaction by order ID
     */
    public function getTransactionByOrderId(string $orderId): ?Transaction
    {
        return Transaction::with(['package', 'voucher'])
            ->where('order_id', $orderId)
            ->first();
    }

    /**
     * Handle payment success
     */
    public function handlePaymentSuccess(string $orderId): bool
    {
        return DB::transaction(function () use ($orderId) {
            $transaction = $this->getTransactionByOrderId($orderId);

            if (!$transaction) {
                Log::error('Transaction not found for payment success', [
                    'order_id' => $orderId,
                ]);
                return false;
            }

            // Prevent double execution (idempotent)
            if ($transaction->isPaid()) {
                Log::info('Transaction already paid (idempotent check)', [
                    'order_id' => $orderId,
                ]);
                return true;
            }

            // Mark transaction as paid
            $transaction->markAsPaid();

            // Mark voucher as sold
            if ($transaction->voucher_id) {
                $this->voucherService->markVoucherAsSold($transaction->voucher_id);
            }

            Log::info('Payment Success Processed', [
                'transaction_id' => $transaction->id,
                'order_id' => $orderId,
                'voucher_id' => $transaction->voucher_id,
            ]);

            return true;
        });
    }

    /**
     * Handle payment failure
     */
    public function handlePaymentFailure(string $orderId): bool
    {
        return DB::transaction(function () use ($orderId) {
            $transaction = $this->getTransactionByOrderId($orderId);

            if (!$transaction) {
                Log::error('Transaction not found for payment failure', [
                    'order_id' => $orderId,
                ]);
                return false;
            }

            // Mark transaction as failed
            $transaction->markAsFailed();

            // Release voucher
            if ($transaction->voucher_id) {
                $this->voucherService->releaseVoucher(
                    $transaction->voucher_id,
                    'payment_failed'
                );
            }

            Log::info('Payment Failure Processed', [
                'transaction_id' => $transaction->id,
                'order_id' => $orderId,
            ]);

            return true;
        });
    }

    /**
     * Handle payment expiration
     */
    public function handlePaymentExpired(string $orderId): bool
    {
        return DB::transaction(function () use ($orderId) {
            $transaction = $this->getTransactionByOrderId($orderId);

            if (!$transaction) {
                Log::error('Transaction not found for payment expiration', [
                    'order_id' => $orderId,
                ]);
                return false;
            }

            // Mark transaction as expired
            $transaction->markAsExpired();

            // Release voucher
            if ($transaction->voucher_id) {
                $this->voucherService->releaseVoucher(
                    $transaction->voucher_id,
                    'payment_expired'
                );
            }

            Log::info('Payment Expiration Processed', [
                'transaction_id' => $transaction->id,
                'order_id' => $orderId,
            ]);

            return true;
        });
    }

    /**
     * Get transaction status with voucher
     * Only return voucher if transaction is paid
     */
    public function getTransactionStatus(int $id): array
    {
        $transaction = $this->getTransaction($id);

        if (!$transaction) {
            return [
                'success' => false,
                'message' => 'Transaction not found',
            ];
        }

        $response = [
            'success' => true,
            'transaction' => [
                'id' => $transaction->id,
                'order_id' => $transaction->order_id,
                'status' => $transaction->status,
                'amount' => $transaction->amount,
                'package' => [
                    'name' => $transaction->package->name,
                    'type' => $transaction->package->type,
                    'value' => $transaction->package->value,
                ],
            ],
            'voucher' => null,
        ];

        // Only include voucher if paid (SECURITY RULE)
        if ($transaction->isPaid() && $transaction->voucher) {
            $response['voucher'] = [
                'username' => $transaction->voucher->username,
                'password' => $transaction->voucher->password,
            ];
        }

        return $response;
    }
}
