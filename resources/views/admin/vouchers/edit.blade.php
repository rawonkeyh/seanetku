@extends('admin.layouts.app')

@section('title', 'Edit Voucher')
@section('page-title', 'Edit Voucher')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Voucher</h1>
        <p class="page-subtitle">Perbarui voucher: {{ $voucher->username }}</p>
    </div>

    <div class="card" style="max-width:820px;">
        <form method="POST" action="{{ route('admin.vouchers.update', $voucher) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="package_id">Paket <span style="color:#ef4444;">*</span></label>
                <select id="package_id" name="package_id" required>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ old('package_id', $voucher->package_id) == $package->id ? 'selected' : '' }}>
                            {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @error('package_id')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="username">Username <span style="color:#ef4444;">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username', $voucher->username) }}" required>
                @error('username')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" id="password" name="password" value="{{ old('password') }}" placeholder="Kosongkan jika tidak diubah">
                <div class="helper">Kosongkan jika tidak ingin mengubah password voucher.</div>
                @error('password')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="status">Status <span style="color:#ef4444;">*</span></label>
                <select id="status" name="status" required>
                    <option value="available" {{ old('status', $voucher->status) === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved" {{ old('status', $voucher->status) === 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="sold" {{ old('status', $voucher->status) === 'sold' ? 'selected' : '' }}>Sold</option>
                </select>
                @error('status')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Voucher</button>
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>

    <style>
        .text-danger { color: #dc2626; font-size: 13px; margin-top: 6px; }
        .helper { color: #64748b; font-size: 13px; margin-top: 6px; }
    </style>
@endsection
