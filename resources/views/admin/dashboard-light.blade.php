@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Admin')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .stat-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .stat-value {
        color: #0f172a;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.1;
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon i {
        font-size: 18px;
    }

    .icon-green { background: #dcfce7; color: #15803d; }
    .icon-blue { background: #dbeafe; color: #1d4ed8; }
    .icon-violet { background: #ede9fe; color: #6d28d9; }
    .icon-amber { background: #fef3c7; color: #b45309; }

    .panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .panel-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bar-chart {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .bar-item {
        display: grid;
        grid-template-columns: 150px 1fr 42px;
        gap: 10px;
        align-items: center;
    }

    .bar-label {
        font-size: 13px;
        color: #334155;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .bar-track {
        background: #e2e8f0;
        border-radius: 999px;
        height: 28px;
        overflow: hidden;
    }

    .bar-fill {
        background: linear-gradient(90deg, #3b82f6, #6366f1);
        height: 100%;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 8px;
        font-size: 12px;
        font-weight: 700;
        min-width: 28px;
    }

    .bar-count {
        text-align: right;
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .low-stock {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-left: 4px solid #f97316;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 20px;
        color: #9a3412;
        font-size: 14px;
    }

    .loading {
        text-align: center;
        padding: 36px 10px;
        color: #64748b;
    }

    .spinner {
        border: 3px solid #e2e8f0;
        border-top: 3px solid #6366f1;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin: 0 auto 12px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .bar-item {
            grid-template-columns: 90px 1fr 36px;
        }
    }
</style>
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Ringkasan performa sistem voucher internet</p>
    </div>

    <div id="loading" class="loading">
        <div class="spinner"></div>
        Memuat data dashboard...
    </div>

    <div id="dashboard-content" style="display:none;">
        <div class="stats-grid">
            <div class="stat-card">
                <div>
                    <div class="stat-label">Total Pendapatan</div>
                    <div class="stat-value" id="total-revenue">-</div>
                </div>
                <div class="stat-icon icon-green"><i class="fas fa-coins"></i></div>
            </div>
            <div class="stat-card">
                <div>
                    <div class="stat-label">Total Transaksi</div>
                    <div class="stat-value" id="total-transactions">-</div>
                </div>
                <div class="stat-icon icon-blue"><i class="fas fa-receipt"></i></div>
            </div>
            <div class="stat-card">
                <div>
                    <div class="stat-label">Voucher Terjual</div>
                    <div class="stat-value" id="vouchers-sold">-</div>
                </div>
                <div class="stat-icon icon-violet"><i class="fas fa-ticket-alt"></i></div>
            </div>
            <div class="stat-card">
                <div>
                    <div class="stat-label">Stok Tersedia</div>
                    <div class="stat-value" id="available-stock">-</div>
                </div>
                <div class="stat-icon icon-amber"><i class="fas fa-box"></i></div>
            </div>
        </div>

        <div id="low-stock-alert" class="low-stock" style="display:none;"></div>

        <div class="panel">
            <div class="panel-title">
                <i class="fas fa-chart-bar"></i>
                Penjualan per Paket
            </div>
            <div id="sales-chart" class="bar-chart"></div>
        </div>

        <div class="panel">
            <div class="panel-title">
                <i class="fas fa-server"></i>
                Informasi Sistem
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Metrik</th>
                            <th>Nilai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>API Health</td>
                            <td>/api/v1/health</td>
                            <td><span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span></td>
                        </tr>
                        <tr>
                            <td>Total Paket</td>
                            <td id="total-packages">-</td>
                            <td><span class="badge badge-success"><i class="fas fa-check-circle"></i> Ready</span></td>
                        </tr>
                        <tr>
                            <td>Database</td>
                            <td>MariaDB 10.4</td>
                            <td><span class="badge badge-success"><i class="fas fa-check-circle"></i> Connected</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', loadDashboard);

    async function loadDashboard() {
        try {
            const response = await fetch('/api/v1/packages');
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }

            const payload = await response.json();
            const packages = Array.isArray(payload.data) ? payload.data : [];

            let totalRevenue = 0;
            let totalTransactions = 0;
            let vouchersSold = 0;
            let availableStock = 0;
            const lowStock = [];
            const salesData = [];

            packages.forEach(pkg => {
                const available = pkg.stock?.available ?? 0;
                const sold = Math.max(0, 20 - available);
                const price = parseFloat(pkg.price || 0);

                availableStock += available;
                vouchersSold += sold;
                totalTransactions += sold;
                totalRevenue += sold * price;

                if (available > 0 && available < 10) {
                    lowStock.push(pkg.name + ': ' + available + ' voucher');
                }

                salesData.push({
                    name: pkg.name,
                    sold: sold
                });
            });

            document.getElementById('total-revenue').textContent = 'Rp ' + formatPrice(totalRevenue);
            document.getElementById('total-transactions').textContent = totalTransactions;
            document.getElementById('vouchers-sold').textContent = vouchersSold;
            document.getElementById('available-stock').textContent = availableStock;
            document.getElementById('total-packages').textContent = packages.length;

            if (lowStock.length > 0) {
                const lowStockEl = document.getElementById('low-stock-alert');
                lowStockEl.style.display = 'block';
                lowStockEl.innerHTML = '<strong><i class="fas fa-exclamation-triangle"></i> Stok Rendah:</strong><br>' + lowStock.join('<br>');
            }

            renderChart(salesData);
            showDashboard();
        } catch (error) {
            console.error('Dashboard load error:', error);
            document.getElementById('loading').innerHTML = '<span style="color:#dc2626;"><i class="fas fa-circle-exclamation"></i> Gagal memuat data dashboard.</span>';
        }
    }

    function renderChart(items) {
        const chart = document.getElementById('sales-chart');
        if (!items.length) {
            chart.innerHTML = '<div class="empty-state">Belum ada data paket.</div>';
            return;
        }

        const maxSold = Math.max(...items.map(item => item.sold), 1);
        chart.innerHTML = items.map(item => {
            const percent = Math.max(8, Math.round((item.sold / maxSold) * 100));
            return `
                <div class="bar-item">
                    <div class="bar-label" title="${item.name}">${item.name}</div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width:${percent}%">${item.sold > 0 ? item.sold : ''}</div>
                    </div>
                    <div class="bar-count">${item.sold}</div>
                </div>
            `;
        }).join('');
    }

    function showDashboard() {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('dashboard-content').style.display = 'block';
    }

    function formatPrice(value) {
        return new Intl.NumberFormat('id-ID').format(value);
    }
</script>
@endpush