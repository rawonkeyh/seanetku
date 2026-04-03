<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Voucher - Admin</title>
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
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-secondary {
            background: #718096;
            color: white;
        }
        .btn-danger {
            background: #f56565;
            color: white;
        }
        .btn-warning {
            background: #ed8936;
            color: white;
        }
        .btn-success {
            background: #48bb78;
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
        .badge-available {
            background: #c6f6d5;
            color: #22543d;
        }
        .badge-reserved {
            background: #feebc8;
            color: #7c2d12;
        }
        .badge-sold {
            background: #cbd5e0;
            color: #2d3748;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #c6f6d5;
            color: #22543d;
        }
        .alert-error {
            background: #fed7d7;
            color: #c53030;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎫 Kelola Voucher</h1>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">🚪 Logout</button>
        </form>
    </div>

    <div class="content">
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">✅ Available</div>
                <div class="stat-value" style="color: #38a169;">{{ $stats['available'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">⏳ Reserved</div>
                <div class="stat-value" style="color: #dd6b20;">{{ $stats['reserved'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">💰 Sold</div>
                <div class="stat-value" style="color: #4a5568;">{{ $stats['sold'] }}</div>
            </div>
        </div>

        <div class="actions">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
                <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">➕ Tambah Voucher</a>
                <a href="{{ route('admin.vouchers.bulk.create') }}" class="btn btn-success">📦 Generate Bulk</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <h2 style="margin-bottom: 20px;">Filter & Cari</h2>
            
            <form method="GET" class="filters">
                <select name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                </select>

                <select name="package_id" onchange="this.form.submit()">
                    <option value="">Semua Paket</option>
                    @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}" {{ request('package_id') == $pkg->id ? 'selected' : '' }}>
                            {{ $pkg->name }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="search" placeholder="Cari username..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">🔍 Cari</button>
            </form>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h2 style="margin-bottom: 20px;">Daftar Voucher</h2>
            
            @if($vouchers->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Paket</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vouchers as $voucher)
                            <tr>
                                <td>#{{ $voucher->id }}</td>
                                <td><strong>{{ $voucher->username }}</strong></td>
                                <td><code>{{ $voucher->password }}</code></td>
                                <td>{{ $voucher->package->name }}</td>
                                <td>
                                    @if($voucher->status === 'available')
                                        <span class="badge badge-available">✅ Available</span>
                                    @elseif($voucher->status === 'reserved')
                                        <span class="badge badge-reserved">⏳ Reserved</span>
                                    @else
                                        <span class="badge badge-sold">💰 Sold</span>
                                    @endif
                                </td>
                                <td>{{ $voucher->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                                        @if($voucher->status === 'available')
                                            <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}" style="display:inline;" onsubmit="return confirm('Yakin hapus voucher?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination">
                    {{ $vouchers->links() }}
                </div>
            @else
                <p style="text-align: center; color: #718096; padding: 40px;">
                    Tidak ada voucher yang ditemukan.
                </p>
            @endif
        </div>
    </div>
</body>
</html>
