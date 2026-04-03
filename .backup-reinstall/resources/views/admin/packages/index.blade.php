<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Paket - Admin</title>
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
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
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
            transition: transform 0.2s;
        }
        .btn:hover { transform: translateY(-2px); }
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
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border-color: #38a169;
        }
        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border-color: #e53e3e;
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
            display: inline-block;
        }
        .badge-time {
            background: #bee3f8;
            color: #2c5282;
        }
        .badge-quota {
            background: #c6f6d5;
            color: #22543d;
        }
        .badge-low {
            background: #fed7d7;
            color: #c53030;
        }
        .badge-ok {
            background: #c6f6d5;
            color: #22543d;
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
        <h1>📦 Kelola Paket</h1>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">🚪 Logout</button>
        </form>
    </div>

    <div class="content">
        <div class="actions">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
                <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">➕ Tambah Paket Baru</a>
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
            <h2 style="margin-bottom: 20px;">Daftar Paket</h2>
            
            @if($packages->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Paket</th>
                            <th>Tipe</th>
                            <th>Nilai</th>
                            <th>Harga</th>
                            <th>Stok Tersedia</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                            <tr>
                                <td>#{{ $package->id }}</td>
                                <td><strong>{{ $package->name }}</strong></td>
                                <td>
                                    @if($package->type === 'time')
                                        <span class="badge badge-time">⏰ Waktu</span>
                                    @else
                                        <span class="badge badge-quota">📊 Kuota</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $package->value }}
                                    @if($package->type === 'time')
                                        jam
                                    @else
                                        GB
                                    @endif
                                </td>
                                <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                                <td>
                                    @if($package->available_stock < 10)
                                        <span class="badge badge-low">{{ $package->available_stock }} voucher</span>
                                    @else
                                        <span class="badge badge-ok">{{ $package->available_stock }} voucher</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-warning btn-sm">✏️ Edit</a>
                                        <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus paket ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center; color: #718096; padding: 40px;">
                    Belum ada paket. Silakan tambahkan paket baru.
                </p>
            @endif
        </div>
    </div>
</body>
</html>
