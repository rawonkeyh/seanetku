<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['package', 'voucher']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by order_id or customer name
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_id', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%');
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(50);

        $stats = [
            'pending' => Transaction::where('status', 'pending')->count(),
            'paid' => Transaction::where('status', 'paid')->count(),
            'failed' => Transaction::where('status', 'failed')->count(),
            'expired' => Transaction::where('status', 'expired')->count(),
            'total_revenue' => Transaction::where('status', 'paid')->sum('amount'),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['package', 'voucher', 'paymentLogs']);
        return view('admin.transactions.show', compact('transaction'));
    }
}
