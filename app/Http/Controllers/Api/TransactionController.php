<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use App\Services\MidtransService;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;
    protected MidtransService $midtransService;

    public function __construct(
        TransactionService $transactionService,
        MidtransService $midtransService
    ) {
        $this->transactionService = $transactionService;
        $this->midtransService = $midtransService;
    }

    /**
     * Create new transaction
     */
    public function create(StoreTransactionRequest $request): JsonResponse
    {
        try {
            $result = $this->transactionService->createTransaction($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => [
                    'transaction' => new TransactionResource($result['transaction']->load(['package', 'voucher'])),
                    'payment' => $result['payment'],
                    'access_token' => $result['access_token'],
                    'payment_expires_at' => $result['payment_expires_at'],
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get transaction details
     */
    public function show(int $id): JsonResponse|TransactionResource
    {
        $transaction = $this->transactionService->getTransaction($id);

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found',
            ], 404);
        }

        if (!$this->isValidAccessToken($transaction, request())) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid transaction access token',
            ], 403);
        }

        return new TransactionResource($transaction->load(['package', 'voucher']));
    }

    /**
     * Get transaction status
     */
    public function status(int $id): JsonResponse
    {
        $transaction = $this->transactionService->getTransaction($id);

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found',
            ], 404);
        }

        if (!$this->isValidAccessToken($transaction, request())) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid transaction access token',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new TransactionResource($transaction->load(['package', 'voucher'])),
        ]);
    }

    /**
     * Get transaction status by order ID
     */
    public function statusByOrderId(string $orderId): JsonResponse
    {
        $transaction = $this->transactionService->getTransactionByOrderId($orderId);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found',
            ], 404);
        }

        if (!$this->isValidAccessToken($transaction, request())) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid transaction access token',
            ], 403);
        }

        // If status is still pending, check with Midtrans directly
        if ($transaction->status === 'pending') {
            try {
                $midtransStatus = $this->midtransService->checkStatus($orderId);
                $transactionStatus = $midtransStatus['transaction_status'];
                $fraudStatus = $midtransStatus['fraud_status'] ?? null;

                // Determine status
                $newStatus = 'pending';
                if ($transactionStatus == 'settlement' || 
                    ($transactionStatus == 'capture' && $fraudStatus == 'accept')) {
                    $newStatus = 'paid';
                } elseif (in_array($transactionStatus, ['deny', 'cancel'])) {
                    $newStatus = 'failed';
                } elseif ($transactionStatus == 'expire') {
                    $newStatus = 'expired';
                }

                // Update status if changed
                if ($newStatus !== 'pending') {
                    if ($newStatus === 'paid') {
                        $this->transactionService->handlePaymentSuccess($orderId);
                    } elseif ($newStatus === 'failed') {
                        $this->transactionService->handlePaymentFailure($orderId);
                    } elseif ($newStatus === 'expired') {
                        $this->transactionService->handlePaymentExpired($orderId);
                    }
                    
                    // Refresh transaction data
                    $transaction->refresh();
                }
            } catch (\Exception $e) {
                // If check fails, continue with local status
                Log::warning('Failed to check Midtrans status', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => new TransactionResource($transaction->load(['package', 'voucher'])),
        ]);
    }

    protected function isValidAccessToken(Transaction $transaction, Request $request): bool
    {
        $token = (string) ($request->header('X-Transaction-Token') ?: $request->query('token', ''));

        if ($token === '') {
            return false;
        }

        return hash_equals($transaction->accessToken(), $token);
    }
}
