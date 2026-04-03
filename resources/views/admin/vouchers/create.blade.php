@extends('admin.layouts.app')

@section('title', 'Tambah Voucher')
@section('page-title', 'Tambah Voucher')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Tambah Voucher</h1>
        <p class="page-subtitle">Buat voucher baru secara manual</p>
    </div>

    <div class="card" style="max-width:820px;">
        <form method="POST" action="{{ route('admin.vouchers.store') }}">
            @csrf

            <div class="form-group">
                <label for="package_id">Paket <span style="color:#ef4444;">*</span></label>
                <select id="package_id" name="package_id" required>
                    <option value="">-- Pilih Paket --</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                            {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @error('package_id')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="username">Username <span style="color:#ef4444;">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required placeholder="Contoh: user001">
                <div class="helper">Username harus unik dan digunakan untuk login voucher.</div>
                @error('username')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password <span style="color:#ef4444;">*</span></label>
                <input type="text" id="password" name="password" value="{{ old('password') }}" required placeholder="Minimal 6 karakter">
                <div class="helper">Password minimal 6 karakter.</div>
                @error('password')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Voucher</button>
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>

    <style>
        .text-danger { color: #dc2626; font-size: 13px; margin-top: 6px; }
        .helper { color: #64748b; font-size: 13px; margin-top: 6px; }
    </style>
@endsection
