@extends('admin.layouts.app')

@section('title', 'Kelola Paket')
@section('page-title', 'Kelola Paket')

@section('content')
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-box" style="margin-right:10px;color:#667eea;"></i>Daftar Paket</h1>
        <p class="page-subtitle">Kelola paket internet yang tersedia di sistem</p>
    </div>

    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
            <h2 style="font-size:17px;font-weight:600;color:#0f172a;">Semua Paket</h2>
            <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Paket Baru
            </a>
        </div>

        @if($packages->count() > 0)
            <div class="table-container">
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
                                <td style="color:#94a3b8;font-size:13px;">#{{ $package->id }}</td>
                                <td><strong>{{ $package->name }}</strong></td>
                                <td>
                                    @if($package->type === 'time')
                                        <span class="badge badge-info"><i class="fas fa-clock"></i> Waktu</span>
                                    @else
                                        <span class="badge badge-success"><i class="fas fa-database"></i> Kuota</span>
                                    @endif
                                </td>
                                <td>{{ $package->value }} {{ $package->type === 'time' ? 'jam' : 'GB' }}</td>
                                <td><strong>Rp {{ number_format($package->price, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($package->available_stock < 10)
                                        <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> {{ $package->available_stock }} voucher</span>
                                    @else
                                        <span class="badge badge-success"><i class="fas fa-check"></i> {{ $package->available_stock }} voucher</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;gap:8px;">
                                        <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                        <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" onsubmit="return confirm('Yakin hapus paket ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $packages->links('vendor.pagination.admin') }}
        @else
            <div style="text-align:center;padding:60px 20px;color:#94a3b8;">
                <i class="fas fa-box-open" style="font-size:48px;margin-bottom:16px;display:block;"></i>
                Belum ada paket. <a href="{{ route('admin.packages.create') }}" style="color:#667eea;">Tambahkan paket baru</a>.
            </div>
        @endif
    </div>
@endsection
