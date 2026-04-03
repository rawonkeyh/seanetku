@extends('admin.layouts.app')

@section('title', 'Generate Bulk Voucher')
@section('page-title', 'Generate Bulk Voucher')

@section('content')
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-layer-group" style="margin-right:10px;color:#667eea;"></i>Generate Bulk Voucher</h1>
        <p class="page-subtitle">Buat voucher secara massal dengan username terurut dan password otomatis</p>
    </div>

    <div style="max-width:700px;">
        <div class="card" style="margin-bottom:20px;background:#eff6ff;border:1px solid #bfdbfe;">
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <i class="fas fa-info-circle" style="color:#3b82f6;font-size:18px;margin-top:2px;flex-shrink:0;"></i>
                <p style="color:#1e40af;font-size:14px;line-height:1.7;">
                    Bulk voucher mendukung 2 mode:
                    <strong>Manual</strong> (prefix + jumlah) dan <strong>CSV</strong> (upload file).
                    Format CSV: <code>username,password</code> (header opsional).
                </p>
            </div>
        </div>

        <div class="card">
            <form method="POST" action="{{ route('admin.vouchers.bulk.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label style="margin-bottom:10px;display:block;">Mode Generate <span style="color:#ef4444;">*</span></label>
                    <div style="display:flex;gap:14px;flex-wrap:wrap;">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="radio" name="generation_type" value="manual" {{ old('generation_type', 'manual') === 'manual' ? 'checked' : '' }}>
                            <span>Manual (Prefix + Jumlah)</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="radio" name="generation_type" value="csv" {{ old('generation_type') === 'csv' ? 'checked' : '' }}>
                            <span>Upload CSV</span>
                        </label>
                    </div>
                    @error('generation_type')
                        <div style="color:#ef4444;font-size:13px;margin-top:6px;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="package_id">Paket <span style="color:#ef4444;">*</span></label>
                    <select id="package_id" name="package_id" required
                        style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;background:white;">
                        <option value="">-- Pilih Paket --</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')
                        <div style="color:#ef4444;font-size:13px;margin-top:6px;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group manual-fields">
                    <label for="prefix">Prefix Username <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="prefix" name="prefix" value="{{ old('prefix', 'user') }}" required
                        placeholder="Contoh: user, vip, guest" maxlength="10"
                        style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;">
                    <p style="color:#64748b;font-size:13px;margin-top:6px;">Prefix username (maksimal 10 karakter, tanpa spasi)</p>
                    @error('prefix')
                        <div style="color:#ef4444;font-size:13px;margin-top:6px;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;margin-top:10px;font-size:13px;">
                        <span style="color:#64748b;">Preview: </span>
                        <code style="color:#667eea;font-weight:600;" id="preview-username">user0001, user0002, user0003, ...</code>
                    </div>
                </div>

                <div class="form-group manual-fields">
                    <label for="quantity">Jumlah Voucher <span style="color:#ef4444;">*</span></label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 10) }}" required
                        min="1" max="100" placeholder="Contoh: 50"
                        style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;">
                    <p style="color:#64748b;font-size:13px;margin-top:6px;">Maksimal 100 voucher per generate</p>
                    @error('quantity')
                        <div style="color:#ef4444;font-size:13px;margin-top:6px;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group csv-fields" style="display:none;">
                    <label for="csv_file">File CSV <span style="color:#ef4444;">*</span></label>
                    <input type="file" id="csv_file" name="csv_file" accept=".csv,.txt"
                        style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:14px;background:white;">
                    <p style="color:#64748b;font-size:13px;margin-top:6px;">
                        Contoh isi file:<br>
                        <code>username,password</code><br>
                        <code>user001,pass1234</code><br>
                        <code>user002,pass5678</code>
                    </p>
                    @error('csv_file')
                        <div style="color:#ef4444;font-size:13px;margin-top:6px;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div style="display:flex;gap:10px;margin-top:10px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-rocket"></i> Generate Voucher
                    </button>
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const modeInputs = document.querySelectorAll('input[name="generation_type"]');
    const manualFields = document.querySelectorAll('.manual-fields');
    const csvFields = document.querySelectorAll('.csv-fields');
    const prefixInput = document.getElementById('prefix');
    const quantityInput = document.getElementById('quantity');
    const csvInput = document.getElementById('csv_file');

    function toggleMode() {
        const selected = document.querySelector('input[name="generation_type"]:checked')?.value || 'manual';
        const manual = selected === 'manual';

        manualFields.forEach(el => el.style.display = manual ? 'block' : 'none');
        csvFields.forEach(el => el.style.display = manual ? 'none' : 'block');

        prefixInput.required = manual;
        quantityInput.required = manual;
        csvInput.required = !manual;
    }

    modeInputs.forEach(input => input.addEventListener('change', toggleMode));
    toggleMode();

    prefixInput.addEventListener('input', function() {
        const prefix = this.value.replace(/[^a-zA-Z0-9]/g, '') || 'user';
        document.getElementById('preview-username').textContent =
            prefix + '0001, ' + prefix + '0002, ' + prefix + '0003, ...';
    });
</script>
@endpush
