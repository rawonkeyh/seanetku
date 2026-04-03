<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentLog;
use App\Services\MidtransService;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    protected MidtransService $midtransService;
    protected TransactionService $transactionService;

    public function __construct(
        MidtransService $midtransService,
        TransactionService $transactionService
    ) {
        $this->midtransService = $midtransService;
        $this->transactionService = $transactionService;
    }

    /**
     * Handle payment callback from Midtrans
     */
    public function handle(Request $request): JsonResponse
    {
        try {
            if (!$this->midtransService->verifyNotificationSignature($request->all())) {
                Log::warning('Rejected callback with invalid Midtrans signature', [
                    'ip' => $request->ip(),
                    'order_id' => $request->input('order_id'),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid callback signature',
                ], 403);
            }

            // Log raw payload
            PaymentLog::logCallback($request->all());

            // Handle notification
            $notification = $this->midtransService->handleNotification($request->all());

            $orderId = $notification['order_id'];
            $status = $notification['status'];

            // Process based on status
            $result = match ($status) {
                'paid' => $this->transactionService->handlePaymentSuccess($orderId),
                'failed' => $this->transactionService->handlePaymentFailure($orderId),
                'expired' => $this->transactionService->handlePaymentExpired($orderId),
                default => true, // For pending status, do nothing
            };

            Log::info('Payment Callback Processed', [
                'order_id' => $orderId,
                'status' => $status,
                'result' => $result,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment callback processed',
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Callback Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment callback',
            ], 500);
        }
    }
}
