<!DOCTYPE html><!DOCTYPE html>
















































































































































































































































































</html></body>    </div>        </div>            @endif                </p>                    Tidak ada transaksi yang ditemukan.                <p style="text-align: center; color: #718096; padding: 40px;">            @else                </div>                    {{ $transactions->links() }}                <div class="pagination">                </table>                    </tbody>                        @endforeach                            </tr>                                </td>                                    </a>                                        👁️ Detail                                    <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-primary btn-sm">                                <td>                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>                                </td>                                    @endif                                        <span class="badge badge-expired">⏰ Expired</span>                                    @else                                        <span class="badge badge-failed">❌ Failed</span>                                    @elseif($transaction->status === 'failed')                                        <span class="badge badge-paid">✅ Paid</span>                                    @elseif($transaction->status === 'paid')                                        <span class="badge badge-pending">⏳ Pending</span>                                    @if($transaction->status === 'pending')                                <td>                                <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>                                <td>{{ $transaction->package->name }}</td>                                </td>                                    <small style="color: #718096;">{{ $transaction->customer_email }}</small>                                    <strong>{{ $transaction->customer_name }}</strong><br>                                <td>                                <td><code>{{ $transaction->order_id }}</code></td>                            <tr>                        @foreach($transactions as $transaction)                    <tbody>                    </thead>                        </tr>                            <th>Aksi</th>                            <th>Tanggal</th>                            <th>Status</th>                            <th>Amount</th>                            <th>Paket</th>                            <th>Customer</th>                            <th>Order ID</th>                        <tr>                    <thead>                <table>            @if($transactions->count() > 0)                        <h2 style="margin-bottom: 20px;">Daftar Transaksi</h2>        <div class="card" style="margin-top: 20px;">        </div>            </form>                <button type="submit" class="btn btn-primary">🔍 Cari</button>                <input type="text" name="search" placeholder="Cari order ID, nama, email..." value="{{ request('search') }}">                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai tanggal">                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari tanggal">                </select>                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>                    <option value="">Semua Status</option>                <select name="status" onchange="this.form.submit()">            <form method="GET" class="filters">                        <h2 style="margin-bottom: 20px;">Filter & Cari</h2>        <div class="card">        </div>            </div>                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Dashboard</a>            <div>        <div class="actions">        </div>            </div>                <div class="stat-value" style="color: #2d3748;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>                <div class="stat-label">💵 Total Revenue</div>            <div class="stat-card">            </div>                <div class="stat-value" style="color: #4a5568;">{{ $stats['expired'] }}</div>                <div class="stat-label">⏰ Expired</div>            <div class="stat-card">            </div>                <div class="stat-value" style="color: #e53e3e;">{{ $stats['failed'] }}</div>                <div class="stat-label">❌ Failed</div>            <div class="stat-card">            </div>                <div class="stat-value" style="color: #38a169;">{{ $stats['paid'] }}</div>                <div class="stat-label">✅ Paid</div>            <div class="stat-card">            </div>                <div class="stat-value" style="color: #dd6b20;">{{ $stats['pending'] }}</div>                <div class="stat-label">⏳ Pending</div>            <div class="stat-card">        <div class="stats">    <div class="content">    </div>        </form>            <button type="submit" class="btn-logout">🚪 Logout</button>            @csrf        <form method="POST" action="{{ route('admin.logout') }}">        <h1>💰 Kelola Transaksi</h1>    <div class="header"><body></head>    </style>        }            font-size: 12px;            padding: 6px 12px;        .btn-sm {        }            margin-top: 20px;            gap: 5px;            justify-content: center;            display: flex;        .pagination {        }            color: #2d3748;            background: #cbd5e0;        .badge-expired {        }            color: #c53030;            background: #fed7d7;        .badge-failed {        }            color: #22543d;            background: #c6f6d5;        .badge-paid {        }            color: #7c2d12;            background: #feebc8;        .badge-pending {        }            font-weight: 600;            font-size: 12px;            border-radius: 12px;            padding: 4px 12px;        .badge {        }            background: #f7fafc;        tr:hover {        }            color: #2d3748;            font-weight: 600;            background: #f7fafc;        th {        }            border-bottom: 1px solid #e2e8f0;            text-align: left;            padding: 12px;        th, td {        }            border-collapse: collapse;            width: 100%;        table {        }            border-radius: 6px;            border: 2px solid #e2e8f0;            padding: 10px;        .filters input {        .filters select,        }            flex-wrap: wrap;            margin-bottom: 20px;            gap: 10px;            display: flex;        .filters {        }            padding: 25px;            box-shadow: 0 4px 6px rgba(0,0,0,0.1);            border-radius: 10px;            background: white;        .card {        }            color: white;            background: #667eea;        .btn-primary {        }            color: white;            background: #718096;        .btn-secondary {        }            display: inline-block;            text-decoration: none;            cursor: pointer;            font-weight: 600;            font-size: 14px;            border: none;            border-radius: 6px;            padding: 10px 20px;        .btn {        }            flex-wrap: wrap;            gap: 10px;            margin-bottom: 20px;            justify-content: space-between;            display: flex;        .actions {        }            margin-top: 5px;            font-weight: 700;            font-size: 28px;        .stat-value {        }            font-size: 14px;            color: #718096;        .stat-label {        }            box-shadow: 0 2px 4px rgba(0,0,0,0.1);            padding: 20px;            border-radius: 8px;            background: white;        .stat-card {        }            margin-bottom: 20px;            gap: 15px;            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));            display: grid;        .stats {        }            padding: 0 20px;            margin: 30px auto;            max-width: 1400px;        .content {        }            font-weight: 600;            font-size: 14px;            cursor: pointer;            border-radius: 6px;            padding: 8px 20px;            color: white;            border: 2px solid white;            background: rgba(255,255,255,0.2);        .btn-logout {        .header h1 { font-size: 24px; }        }            box-shadow: 0 2px 10px rgba(0,0,0,0.1);            align-items: center;            justify-content: space-between;            display: flex;            padding: 20px 30px;            color: white;            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);        .header {        }            min-height: 100vh;            background: #f7fafc;            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;        body {        * { margin: 0; padding: 0; box-sizing: border-box; }    <style>    <title>Kelola Transaksi - Admin</title>    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <meta charset="UTF-8"><head><html lang="id"><html  lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Transaksi - Admin</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 24px; }
        .btn-logout {
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            color: white;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        .content {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-label {
            color: #718096;
            font-size: 14px;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-top: 5px;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 10px;
            flex-wrap: wrap;
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
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 25px;
        }
        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filters select,
        .filters input {
            padding: 10px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        th {
            background: #f7fafc;
            font-weight: 600;
            color: #2d3748;
        }
        tr:hover {
            background: #f7fafc;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
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
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>💰 Kelola Transaksi</h1>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">🚪 Logout</button>
        </form>
    </div>

    <div class="content">
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">⏳ Pending</div>
                <div class="stat-value" style="color: #dd6b20;">{{ $stats['pending'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">✅ Paid</div>
                <div class="stat-value" style="color: #38a169;">{{ $stats['paid'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">❌ Failed</div>
                <div class="stat-value" style="color: #e53e3e;">{{ $stats['failed'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">⏰ Expired</div>
                <div class="stat-value" style="color: #4a5568;">{{ $stats['expired'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">💵 Total Revenue</div>
                <div class="stat-value" style="color: #38a169;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="actions">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
        </div>

        <div class="card">
            <h2 style="margin-bottom: 20px;">Filter & Cari</h2>
            
            <form method="GET" class="filters">
                <select name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari Tanggal">
                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai Tanggal">

                <input type="text" name="search" placeholder="Cari Order ID / Customer..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">🔍 Cari</button>
            </form>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h2 style="margin-bottom: 20px;">Daftar Transaksi</h2>
            
            @if($transactions->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Paket</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td><strong>{{ $transaction->order_id }}</strong></td>
                                <td>
                                    <div>{{ $transaction->customer_name }}</div>
                                    <div style="font-size: 12px; color: #718096;">{{ $transaction->customer_email }}</div>
                                </td>
                                <td>{{ $transaction->package->name }}</td>
                                <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($transaction->status === 'pending')
                                        <span class="badge badge-pending">⏳ Pending</span>
                                    @elseif($transaction->status === 'paid')
                                        <span class="badge badge-paid">✅ Paid</span>
                                    @elseif($transaction->status === 'failed')
                                        <span class="badge badge-failed">❌ Failed</span>
                                    @else
                                        <span class="badge badge-expired">⏰ Expired</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-primary btn-sm">👁️ Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 20px;">
                    {{ $transactions->links() }}
                </div>
            @else
                <p style="text-align: center; color: #718096; padding: 40px;">
                    Tidak ada transaksi yang ditemukan.
                </p>
            @endif
        </div>
    </div>
</body>
</html>
