@extends('admin.layouts.app')

@section('title', 'Kelola Voucher')
@section('page-title', 'Kelola Voucher')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Kelola Voucher</h1>
        <p class="page-subtitle">Pantau stok dan status voucher secara real-time</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:20px;">
        <div class="card" style="padding:16px;">
            <div style="font-size:12px;color:#64748b;font-weight:700;text-transform:uppercase;">Available</div>
            <div style="font-size:28px;font-weight:800;color:#16a34a;">{{ $stats['available'] }}</div>
        </div>
        <div class="card" style="padding:16px;">
            <div style="font-size:12px;color:#64748b;font-weight:700;text-transform:uppercase;">Reserved</div>
            <div style="font-size:28px;font-weight:800;color:#d97706;">{{ $stats['reserved'] }}</div>
        </div>
        <div class="card" style="padding:16px;">
            <div style="font-size:12px;color:#64748b;font-weight:700;text-transform:uppercase;">Sold</div>
            <div style="font-size:28px;font-weight:800;color:#475569;">{{ $stats['sold'] }}</div>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <div style="font-size:15px;font-weight:700;margin-bottom:12px;">Filter Voucher</div>
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
            <div>
                <label for="status" style="font-size:12px;margin-bottom:4px;display:block;color:#64748b;">Status</label>
                <select id="status" name="status" style="min-width:150px;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold</option>
                </select>
            </div>

            <div>
                <label for="package_id" style="font-size:12px;margin-bottom:4px;display:block;color:#64748b;">Paket</label>
                <select id="package_id" name="package_id" style="min-width:220px;" onchange="this.form.submit()">
                    <option value="">Semua Paket</option>
                    @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}" {{ request('package_id') == $pkg->id ? 'selected' : '' }}>{{ $pkg->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex:1;min-width:220px;">
                <label for="search" style="font-size:12px;margin-bottom:4px;display:block;color:#64748b;">Cari Username</label>
                <input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: user001">
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary"><i class="fas fa-rotate-left"></i> Reset</a>
        </form>
    </div>

    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:14px;">
            <h2 style="font-size:18px;font-weight:700;color:#0f172a;">Daftar Voucher</h2>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Voucher</a>
                <a href="{{ route('admin.vouchers.bulk.create') }}" class="btn btn-success"><i class="fas fa-layer-group"></i> Bulk Voucher</a>
            </div>
        </div>

        @if($vouchers->count() > 0)
            <div class="table-container">
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
                                <td><code style="background:#f1f5f9;padding:3px 8px;border-radius:6px;">{{ $voucher->password }}</code></td>
                                <td>{{ $voucher->package->name }}</td>
                                <td>
                                    @if($voucher->status === 'available')
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Available</span>
                                    @elseif($voucher->status === 'reserved')
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Reserved</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-bag-shopping"></i> Sold</span>
                                    @endif
                                </td>
                                <td>{{ $voucher->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                                        @if($voucher->status === 'available')
                                            <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}" onsubmit="return confirm('Yakin hapus voucher ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $vouchers->appends(request()->except('page'))->links('vendor.pagination.admin') }}
        @else
            <div style="text-align:center;color:#94a3b8;padding:40px;">Belum ada voucher yang sesuai filter.</div>
        @endif
    </div>
@endsection