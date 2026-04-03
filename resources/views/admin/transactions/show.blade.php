@extends('admin.layouts.app')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Detail Transaksi</h1>
        <p class="page-subtitle">Informasi lengkap order {{ $transaction->order_id }}</p>
    </div>

    <div style="margin-bottom:16px;">
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar</a>
    </div>

    <div class="detail-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;">
        <div class="card">
            <div class="card-header">Informasi Transaksi</div>
            <div class="kv-item"><span>Order ID</span><strong>{{ $transaction->order_id }}</strong></div>
            <div class="kv-item"><span>Amount</span><strong>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</strong></div>
            <div class="kv-item"><span>Tanggal</span><strong>{{ $transaction->created_at->format('d M Y H:i') }}</strong></div>
            <div class="kv-item"><span>Status</span>
                <strong>
                    @if($transaction->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                    @elseif($transaction->status === 'paid')
                        <span class="badge badge-success">Paid</span>
                    @elseif($transaction->status === 'failed')
                        <span class="badge badge-danger">Failed</span>
                    @else
                        <span class="badge badge-secondary">Expired</span>
                    @endif
                </strong>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Informasi Customer</div>
            <div class="kv-item"><span>Nama</span><strong>{{ $transaction->customer_name }}</strong></div>
            <div class="kv-item"><span>Email</span><strong>{{ $transaction->customer_email }}</strong></div>
            <div class="kv-item"><span>No. HP</span><strong>{{ $transaction->customer_phone }}</strong></div>
        </div>

        <div class="card">
            <div class="card-header">Informasi Paket</div>
            <div class="kv-item"><span>Nama Paket</span><strong>{{ $transaction->package->name }}</strong></div>
            <div class="kv-item"><span>Tipe</span>
                <strong>{{ $transaction->package->type === 'time' ? 'Paket Waktu' : 'Paket Kuota' }}</strong>
            </div>
            <div class="kv-item"><span>Nilai</span>
                <strong>{{ $transaction->package->value }} {{ $transaction->package->type === 'time' ? 'jam' : 'GB' }}</strong>
            </div>
            <div class="kv-item"><span>Harga Paket</span><strong>Rp {{ number_format($transaction->package->price, 0, ',', '.') }}</strong></div>
        </div>
    </div>

    @if($transaction->status === 'paid' && $transaction->voucher)
        <div class="card" style="margin-top:16px;">
            <div class="card-header">Voucher Credentials</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="kv-item"><span>Username</span><strong><code>{{ $transaction->voucher->username }}</code></strong></div>
                <div class="kv-item"><span>Password</span><strong><code>{{ $transaction->voucher->password }}</code></strong></div>
            </div>
        </div>
    @endif

    @if($transaction->paymentLogs->count() > 0)
        <div class="card" style="margin-top:16px;">
            <div class="card-header">Payment Logs</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Status</th>
                            <th>Payment Type</th>
                            <th>Response</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->paymentLogs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $log->transaction_status ?? '-' }}</td>
                                <td>{{ $log->payment_type ?? '-' }}</td>
                                <td>
                                    <code style="font-size:11px;">{{ \Illuminate\Support\Str::limit(json_encode($log->raw_payload), 80) }}</code>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <style>
        .kv-item {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            align-items: center;
        }

        .kv-item span {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
        }

        .kv-item strong {
            color: #0f172a;
            font-size: 14px;
            text-align: right;
        }

        .kv-item:last-child {
            border-bottom: 0;
        }

        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
