<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Paket - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f7fafc;
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);            color: white;
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
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 15px;
        }
        input:focus, select:focus, textarea:focus {
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
    </style>
</head>
<body>
    <div class="header">
        <h1>✏️ Edit Paket: {{ $package->name }}</h1>
    </div>

    <div class="content">
        <div class="card">
            <form method="POST" action="{{ route('admin.packages.update', $package) }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Nama Paket *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $package->name) }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type">Tipe Paket *</label>
                    <select id="type" name="type" required>
                        <option value="time" {{ old('type', $package->type) == 'time' ? 'selected' : '' }}>⏰ Paket Waktu</option>
                        <option value="quota" {{ old('type', $package->type) == 'quota' ? 'selected' : '' }}>📊 Paket Kuota</option>
                    </select>
                    @error('type')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="value">Nilai *</label>
                    <input type="number" id="value" name="value" value="{{ old('value', $package->value) }}" required min="1">
                    @error('value')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Harga (Rp) *</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $package->price) }}" required min="0">
                    @error('price')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="3">{{ old('description', $package->description) }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">💾 Update Paket</button>
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">← Kembali</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
