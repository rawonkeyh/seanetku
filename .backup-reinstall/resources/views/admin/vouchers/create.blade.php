<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Voucher - Admin</title>
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
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 8px;
        }
        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 15px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-secondary {
            background: #718096;
            color: white;
        }
        .error {
            color: #e53e3e;
            font-size: 13px;
            margin-top: 4px;
        }
        .help-text {
            color: #718096;
            font-size: 13px;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>➕ Tambah Voucher Baru</h1>
    </div>

    <div class="content">
        <div class="card">
            <form method="POST" action="{{ route('admin.vouchers.store') }}">
                @csrf
                
                <div class="form-group">
                    <label for="package_id">Paket *</label>
                    <select id="package_id" name="package_id" required>
                        <option value="">-- Pilih Paket --</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required placeholder="Contoh: user001">
                    <div class="help-text">Username harus unik dan akan digunakan untuk login</div>
                    @error('username')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="text" id="password" name="password" value="{{ old('password') }}" required placeholder="Minimal 6 karakter">
                    <div class="help-text">Password untuk akses voucher (minimal 6 karakter)</div>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">💾 Simpan Voucher</button>
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">← Kembali</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
