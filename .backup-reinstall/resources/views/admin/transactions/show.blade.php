<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f7fafc;
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 24px; }
        .content {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
        }
        .card h2 {
            margin-bottom: 20px;
            color: #2d3748;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .info-item {
            padding: 15px;
            background: #f7fafc;
            border-radius: 6px;
        }
        .info-label {
            color: #718096;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .info-value {
            color: #2d3748;
            font-size: 16px;
            font-weight: 500;
        }
        .badge {
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-pending {
            background: #feebc8;
            color: #7c2d12;
        }
        .badge-paid {
            background: #c6f6d5;
            color: #22543d;
        }
        .badge-failed {
            background: #fed7d7;
            color: #c53030;
        }
        .badge-expired {
            background: #cbd5e0;
            color: #2d3748;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary {
            background: #718096;
            color: white;
        }
        .voucher-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
        }
        .voucher-credentials {
            background: rgba(255,255,255,0.2);
            padding: 20px;
            border-radius: 6px;
            margin-top: 15px;
        }
        .voucher-credentials code {
            background: rgba(255,255,255,0.3);
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background: #f7fafc;
            font-weight: 600;
            color: #2d3748;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Detail Transaksi</h1>
    </div>

    <div class="content">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">← Kembali ke Daftar Transaksi</a>
        </div>

        <div class="card">
            <h2>Informasi Transaksi</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Order ID</div>
                    <div class="info-value"><code>{{ $transaction->order_id }}</code></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        @if($transaction->status === 'pending')
                            <span class="badge badge-pending">⏳ Pending</span>
                        @elseif($transaction->status === 'paid')
                            <span class="badge badge-paid">✅ Paid</span>
                        @elseif($transaction->status === 'failed')
                            <span class="badge badge-failed">❌ Failed</span>
                        @else
                            <span class="badge badge-expired">⏰ Expired</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Amount</div>
                    <div class="info-value">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal</div>
                    <div class="info-value">{{ $transaction->created_at->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Informasi Customer</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama</div>
                    <div class="info-value">{{ $transaction->customer_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $transaction->customer_email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">No. HP</div>
                    <div class="info-value">{{ $transaction->customer_phone }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Informasi Paket</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Paket</div>
                    <div class="info-value">{{ $transaction->package->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tipe</div>
                    <div class="info-value">
                        @if($transaction->package->type === 'time')
                            ⏰ Paket Waktu ({{ $transaction->package->value }} jam)
                        @else
                            📊 Paket Kuota ({{ $transaction->package->value }} GB)
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Harga</div>
                    <div class="info-value">Rp {{ number_format($transaction->package->price, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        @if($transaction->status === 'paid' && $transaction->voucher)
            <div class="card">
                <h2>🎫 Voucher</h2>
                <div class="voucher-box">
                    <h3 style="margin-bottom: 15px;">Credentials Voucher</h3>
                    <div class="voucher-credentials">
                        <div style="margin-bottom: 15px;">
                            <div style="font-size: 13px; margin-bottom: 5px;">Username:</div>
                            <code>{{ $transaction->voucher->username }}</code>
                        </div>
                        <div>
                            <div style="font-size: 13px; margin-bottom: 5px;">Password:</div>
                            <code>{{ $transaction->voucher->password }}</code>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($transaction->paymentLogs->count() > 0)
            <div class="card">
                <h2>📜 Payment Logs</h2>
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
                                <td>{{ $log->status }}</td>
                                <td>{{ $log->payment_type ?? '-' }}</td>
                                <td><code style="font-size: 11px;">{{ Str::limit($log->response_data, 50) }}</code></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>
</html>
