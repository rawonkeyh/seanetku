@extends('admin.layouts.app')

@section('title', 'Kelola Transaksi')
@section('page-title', 'Kelola Transaksi')

@push('styles')
<style>
    .txn-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1200;
        padding: 16px;
    }

    .txn-modal-backdrop.open {
        display: flex;
    }

    .txn-modal {
        width: min(820px, 100%);
        max-height: 90vh;
        overflow: auto;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.25);
        border: 1px solid #e2e8f0;
    }

    .txn-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .txn-modal-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .txn-modal-close {
        border: 0;
        background: #f1f5f9;
        color: #334155;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        cursor: pointer;
    }

    .txn-modal-content {
        padding: 18px 20px 22px;
    }

    .txn-detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 14px;
    }

    .txn-detail-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
    }

    .txn-detail-card h4 {
        font-size: 14px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 10px;
    }

    .txn-kv {
        display: grid;
        grid-template-columns: 95px 1fr;
        gap: 10px;
        padding: 7px 0;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 13px;
        align-items: center;
    }

    .txn-kv:last-child {
        border-bottom: 0;
    }

    .txn-kv span {
        color: #64748b;
    }

    .txn-kv strong {
        color: #0f172a;
        text-align: right;
        min-width: 0;
        overflow-wrap: break-word;
        word-break: break-word;
    }

    .txn-kv.status-row strong {
        display: inline-flex;
        justify-content: flex-end;
        align-items: center;
    }

    .txn-kv.status-row .badge {
        white-space: nowrap;
    }

    .txn-code {
        background: #f1f5f9;
        border-radius: 6px;
        padding: 2px 8px;
        display: inline-block;
        max-width: 100%;
        overflow-wrap: normal;
        word-break: normal;
        white-space: normal;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 12px;
        line-height: 1.4;
    }

    .txn-table-order code {
        display: inline-block;
        max-width: 220px;
        overflow-wrap: anywhere;
        word-break: break-word;
        white-space: normal;
    }

    .txn-table-customer small,
    .txn-table-package {
        display: inline-block;
        max-width: 240px;
        overflow-wrap: anywhere;
        word-break: break-word;
        white-space: normal;
    }

    .txn-filter-date {
        width: 170px !important;
        min-width: 170px;
        height: 42px;
        padding: 0 12px;
        border: 1.5px solid #dbe2ea;
        border-radius: 10px;
        background: #ffffff;
        color: #0f172a;
    }

    .txn-filter-date:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12);
    }

    @media (max-width: 768px) {
        .txn-kv {
            grid-template-columns: 1fr;
            gap: 4px;
        }

        .txn-kv span,
        .txn-kv strong {
            max-width: 100%;
            text-align: left;
        }

        .txn-kv.status-row strong {
            justify-content: flex-start;
        }

        .txn-table-order code,
        .txn-table-customer small,
        .txn-table-package {
            max-width: 160px;
        }

        .txn-filter-date {
            width: 100% !important;
            min-width: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title"><i class="fas fa-receipt"></i> Kelola Transaksi</h1>
    <p class="page-subtitle">Pantau status pembayaran dan histori transaksi pelanggan</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon icon-orange"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-content">
            <div class="stat-label">Pending</div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-content">
            <div class="stat-label">Paid</div>
            <div class="stat-value">{{ $stats['paid'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-purple"><i class="fas fa-times-circle"></i></div>
        <div class="stat-content">
            <div class="stat-label">Failed</div>
            <div class="stat-value">{{ $stats['failed'] }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon icon-blue"><i class="fas fa-wallet"></i></div>
        <div class="stat-content">
            <div class="stat-label">Revenue</div>
            <div class="stat-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-filter"></i> Filter Transaksi</div>
    <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap;">
        <select name="status" style="width:auto;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
        </select>

        <input type="date" name="date_from" value="{{ request('date_from') }}" class="txn-filter-date">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="txn-filter-date">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari order ID / nama / email" style="flex:1; min-width:220px;">

        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
        @if(request()->hasAny(['status', 'date_from', 'date_to', 'search']))
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary"><i class="fas fa-undo"></i> Reset</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-table"></i> Daftar Transaksi ({{ $transactions->total() }})</div>

    @if($transactions->count())
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Pelanggan</th>
                        <th>Paket</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td class="txn-table-order"><code>{{ $transaction->order_id }}</code></td>
                            <td class="txn-table-customer">
                                <strong>{{ $transaction->customer_name }}</strong><br>
                                <small style="color:#64748b;">{{ $transaction->customer_email }}</small>
                            </td>
                            <td class="txn-table-package">{{ optional($transaction->package)->name ?? '-' }}</td>
                            <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td>
                                @if($transaction->status === 'paid')
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Paid</span>
                                @elseif($transaction->status === 'pending')
                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                @elseif($transaction->status === 'failed')
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Failed</span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-hourglass-end"></i> Expired</span>
                                @endif
                            </td>
                            <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm js-open-txn-modal"
                                    data-order-id="{{ $transaction->order_id }}"
                                    data-status="{{ $transaction->status }}"
                                    data-amount="Rp {{ number_format($transaction->amount, 0, ',', '.') }}"
                                    data-created-at="{{ $transaction->created_at->format('d M Y H:i') }}"
                                    data-customer-name="{{ $transaction->customer_name }}"
                                    data-customer-email="{{ $transaction->customer_email }}"
                                    data-customer-phone="{{ $transaction->customer_phone }}"
                                    data-package-name="{{ optional($transaction->package)->name ?? '-' }}"
                                    data-package-type="{{ optional($transaction->package)->type === 'time' ? 'Paket Waktu' : (optional($transaction->package)->type === 'quota' ? 'Paket Kuota' : '-') }}"
                                    data-package-value="{{ optional($transaction->package)->value ? optional($transaction->package)->value . ' ' . (optional($transaction->package)->type === 'time' ? 'jam' : 'GB') : '-' }}"
                                    data-voucher-username="{{ optional($transaction->voucher)->username ?? '-' }}"
                                    data-voucher-password="{{ optional($transaction->voucher)->password ?? '-' }}"
                                >
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $transactions->appends(request()->except('page'))->links('vendor.pagination.admin') }}
    @else
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
            <p>Tidak ada transaksi ditemukan.</p>
        </div>
    @endif
</div>

<div class="txn-modal-backdrop" id="txnDetailModal" aria-hidden="true">
    <div class="txn-modal" role="dialog" aria-modal="true" aria-labelledby="txnModalTitle">
        <div class="txn-modal-header">
            <div class="txn-modal-title" id="txnModalTitle">Detail Transaksi</div>
            <button type="button" class="txn-modal-close" id="txnModalClose" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="txn-modal-content">
            <div class="txn-detail-grid">
                <div class="txn-detail-card">
                    <h4>Informasi Transaksi</h4>
                    <div class="txn-kv"><span>Order ID</span><strong><span class="txn-code" id="mOrderId">-</span></strong></div>
                    <div class="txn-kv status-row"><span>Status</span><strong id="mStatus">-</strong></div>
                    <div class="txn-kv"><span>Jumlah</span><strong id="mAmount">-</strong></div>
                    <div class="txn-kv"><span>Tanggal</span><strong id="mCreatedAt">-</strong></div>
                </div>

                <div class="txn-detail-card">
                    <h4>Pelanggan</h4>
                    <div class="txn-kv"><span>Nama</span><strong id="mCustomerName">-</strong></div>
                    <div class="txn-kv"><span>Email</span><strong id="mCustomerEmail">-</strong></div>
                    <div class="txn-kv"><span>No HP</span><strong id="mCustomerPhone">-</strong></div>
                </div>

                <div class="txn-detail-card">
                    <h4>Paket</h4>
                    <div class="txn-kv"><span>Nama Paket</span><strong id="mPackageName">-</strong></div>
                    <div class="txn-kv"><span>Tipe</span><strong id="mPackageType">-</strong></div>
                    <div class="txn-kv"><span>Nilai</span><strong id="mPackageValue">-</strong></div>
                </div>

                <div class="txn-detail-card">
                    <h4>Voucher</h4>
                    <div class="txn-kv"><span>Username</span><strong id="mVoucherUsername">-</strong></div>
                    <div class="txn-kv"><span>Password</span><strong id="mVoucherPassword">-</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (() => {
        const modal = document.getElementById('txnDetailModal');
        const closeBtn = document.getElementById('txnModalClose');

        const statusBadge = {
            paid: '<span class="badge badge-success"><i class="fas fa-check"></i> Paid</span>',
            pending: '<span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>',
            failed: '<span class="badge badge-danger"><i class="fas fa-times"></i> Failed</span>',
            expired: '<span class="badge badge-secondary"><i class="fas fa-hourglass-end"></i> Expired</span>'
        };

        document.querySelectorAll('.js-open-txn-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('mOrderId').textContent = btn.dataset.orderId || '-';
                document.getElementById('mStatus').innerHTML = statusBadge[btn.dataset.status] || '-';
                document.getElementById('mAmount').textContent = btn.dataset.amount || '-';
                document.getElementById('mCreatedAt').textContent = btn.dataset.createdAt || '-';
                document.getElementById('mCustomerName').textContent = btn.dataset.customerName || '-';
                document.getElementById('mCustomerEmail').textContent = btn.dataset.customerEmail || '-';
                document.getElementById('mCustomerPhone').textContent = btn.dataset.customerPhone || '-';
                document.getElementById('mPackageName').textContent = btn.dataset.packageName || '-';
                document.getElementById('mPackageType').textContent = btn.dataset.packageType || '-';
                document.getElementById('mPackageValue').textContent = btn.dataset.packageValue || '-';
                document.getElementById('mVoucherUsername').textContent = btn.dataset.voucherUsername || '-';
                document.getElementById('mVoucherPassword').textContent = btn.dataset.voucherPassword || '-';

                modal.classList.add('open');
                modal.setAttribute('aria-hidden', 'false');
            });
        });

        function closeModal() {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
        }

        closeBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('open')) {
                closeModal();
            }
        });
    })();
</script>
@endpush
