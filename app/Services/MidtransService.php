<?php

namespace App\Services;

use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create payment with Midtrans Snap
     */
    public function createPayment(Transaction $transaction): array
    {
        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->order_id,
                    'gross_amount' => (int) $transaction->amount,
                ],
                'item_details' => [
                    [
                        'id' => $transaction->package_id,
                        'price' => (int) $transaction->amount,
                        'quantity' => 1,
                        'name' => $transaction->package->name,
                    ],
                ],
                'customer_details' => [
                    'first_name' => $transaction->customer_name ?? 'Guest',
                    'email' => $transaction->customer_email ?? 'guest@example.com',
                    'phone' => $transaction->customer_phone ?? '08123456789',
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            Log::info('Midtrans Payment Created', [
                'order_id' => $transaction->order_id,
                'amount' => $transaction->amount,
            ]);

            return [
                'snap_token' => $snapToken,
                'redirect_url' => $this->getSnapUrl($snapToken),
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Payment Creation Failed', [
                'order_id' => $transaction->order_id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Verify callback signature sent by Midtrans.
     */
    public function verifyNotificationSignature(array $payload): bool
    {
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $serverKey = (string) config('midtrans.server_key');

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signatureKey === '' || $serverKey === '') {
            return false;
        }

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }

    /**
     * Handle payment notification from Midtrans
     */
    public function handleNotification(array $payload): array
    {
        try {
            $notification = new Notification();

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;

            Log::info('Payment Callback Received', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]);

            $status = $this->determineStatus($transactionStatus, $fraudStatus);

            return [
                'order_id' => $orderId,
                'status' => $status,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $notification->payment_type ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Handling Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Determine final status based on Midtrans response
     */
    protected function determineStatus(string $transactionStatus, ?string $fraudStatus): string
    {
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                return 'paid';
            }
        } elseif ($transactionStatus == 'settlement') {
            return 'paid';
        } elseif ($transactionStatus == 'pending') {
            return 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'cancel'])) {
            return 'failed';
        } elseif ($transactionStatus == 'expire') {
            return 'expired';
        }

        return 'failed';
    }

    /**
     * Get Snap redirect URL
     */
    protected function getSnapUrl(string $snapToken): string
    {
        $baseUrl = config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/v2/vtweb/'
            : 'https://app.sandbox.midtrans.com/snap/v2/vtweb/';

        return $baseUrl . $snapToken;
    }

    /**
     * Check transaction status directly from Midtrans
     */
    public function checkStatus(string $orderId): array
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);

            return [
                'order_id' => $status->order_id,
                'transaction_status' => $status->transaction_status,
                'fraud_status' => $status->fraud_status ?? null,
                'payment_type' => $status->payment_type ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Status Check Failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
