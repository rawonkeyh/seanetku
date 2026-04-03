<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Bulk Voucher - Admin</title>
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
        .info-box {
            background: #bee3f8;
            border-left: 4px solid #3182ce;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
        }
        .preview {
            background: #f7fafc;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 Generate Bulk Voucher</h1>
    </div>

    <div class="content">
        <div class="info-box">
            <strong>ℹ️ Info:</strong> Fitur ini akan membuat voucher secara massal dengan username terurut (prefix0001, prefix0002, dst) dan password random otomatis.
        </div>

        <div class="card">
            <form method="POST" action="{{ route('admin.vouchers.bulk.store') }}">
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
                    <label for="prefix">Prefix Username *</label>
                    <input type="text" id="prefix" name="prefix" value="{{ old('prefix', 'user') }}" required placeholder="Contoh: user, vip, guest" maxlength="10">
                    <div class="help-text">Prefix untuk username (maksimal 10 karakter)</div>
                    @error('prefix')
                        <div class="error">{{ $message }}</div>
                    @enderror
                    <div class="preview">
                        Preview: <strong id="preview-username">user0001, user0002, user0003, ...</strong>
                    </div>
                </div>

                <div class="form-group">
                    <label for="quantity">Jumlah Voucher *</label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 10) }}" required min="1" max="100" placeholder="Contoh: 50">
                    <div class="help-text">Maksimal 100 voucher per generate</div>
                    @error('quantity')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">🚀 Generate Voucher</button>
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">← Kembali</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('prefix').addEventListener('input', function(e) {
            const prefix = e.target.value || 'user';
            document.getElementById('preview-username').textContent = 
                `${prefix}0001, ${prefix}0002, ${prefix}0003, ...`;
        });
    </script>
</body>
</html>
