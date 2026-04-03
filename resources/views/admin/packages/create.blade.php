@extends('admin.layouts.app')

@section('title', 'Tambah Paket')
@section('page-title', 'Tambah Paket')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Tambah Paket Baru</h1>
        <p class="page-subtitle">Buat paket internet baru untuk dijual</p>
    </div>

    <div class="card" style="max-width:820px;">
        <form method="POST" action="{{ route('admin.packages.store') }}">
            @csrf

            <div class="form-group">
                <label for="name">Nama Paket <span style="color:#ef4444;">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Paket 1 Jam">
                @error('name')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div class="form-group">
                    <label for="type">Tipe Paket <span style="color:#ef4444;">*</span></label>
                    <select id="type" name="type" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="time" {{ old('type') === 'time' ? 'selected' : '' }}>Paket Waktu</option>
                        <option value="quota" {{ old('type') === 'quota' ? 'selected' : '' }}>Paket Kuota</option>
                    </select>
                    @error('type')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="value">Nilai <span style="color:#ef4444;">*</span></label>
                    <input type="number" id="value" name="value" value="{{ old('value') }}" min="1" required placeholder="Contoh: 1 atau 5">
                    @error('value')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label for="price">Harga (Rp) <span style="color:#ef4444;">*</span></label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" required placeholder="Contoh: 5000">
                @error('price')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="4" placeholder="Deskripsi singkat paket (opsional)">{{ old('description') }}</textarea>
                @error('description')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Paket</button>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>

    <style>
        .text-danger { color: #dc2626; font-size: 13px; margin-top: 6px; }
        @media (max-width: 768px) {
            .grid-2 { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection