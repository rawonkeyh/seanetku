<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background: #f5f5f5; }
        
        /* Layout */
        .layout { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #1f2937; color: #fff; flex-shrink: 0; }
        .main { flex: 1; overflow-y: auto; }
        
        /* Sidebar */
        .sidebar-header { padding: 24px; border-bottom: 1px solid #374151; }
        .sidebar-header h1 { font-size: 20px; }
        .nav { padding: 20px 0; }
        .nav-link { display: flex; align-items: center; padding: 12px 24px; color: #d1d5db; text-decoration: none; transition: all 0.2s; }
        .nav-link:hover, .nav-link.active { background: #374151; color: #fff; }
        .nav-link span { margin-left: 12px; }
        
        /* Header */
        .header { background: #fff; border-bottom: 1px solid #e5e7eb; padding: 20px 30px; }
        .header h2 { font-size: 24px; color: #111827; margin-bottom: 4px; }
        .header p { color: #6b7280; font-size: 14px; }
        
        /* Content */
        .content { padding: 30px; }
        
        /* Cards Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #fff; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .stat-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px; }
        .stat-label { font-size: 13px; color:#6b7280; font-weight: 600; }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .stat-value { font-size: 32px; font-weight: 700; color: #111827; }
        .icon-green { background: #dcfce7; }
        .icon-blue { background: #dbeafe; }
        .icon-purple { background: #e9d5ff; }
        .icon-yellow { background: #fef3c7; }
        
        /* Alert */
        .alert { background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 30px; }
        .alert h4 { font-size: 14px; font-weight: 600; margin-bottom: 8px; }
        .alert-content { font-size: 13px; }
        
        /* Chart Card */
        .chart-card { background: #fff; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .chart-card h3 { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 20px; }
        
        /* Simple Bar Chart */
        .bar-chart { display: flex; flex-direction: column; gap: 12px; }
        .bar-item { display: flex; align-items: center; gap: 12px; }
        .bar-label { width: 120px; font-size: 13px; color: #4b5563; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .bar-visual { flex: 1; height: 32px; background: #e5e7eb; border-radius: 6px; position: relative; overflow: hidden; }
        .bar-fill { height: 100%; background: linear-gradient(90deg, #3b82f6, #60a5fa); border-radius: 6px; display: flex; align-items: center; justify-content: flex-end; padding-right: 8px; color: #fff; font-size: 12px; font-weight: 600; transition: width 0.3s ease; }
        .bar-count { min-width: 30px; text-align: right; font-size: 13px; font-weight: 600; color: #111827; }
        
        /* Table */
        .table-card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
        .table-card h3 { font-size: 18px; font-weight: 700; color: #111827; padding: 24px; }
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f9fafb; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; padding: 12px 24px; text-align: left; }
        td { padding: 16px 24px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #111827; }
        .empty-state { padding: 60px 20px; text-align: center; color: #9ca3af; }
        
        /* Badge */
        .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #16a34a; }
        .badge-warning { background: #fef3c7; color: #d97706; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        
        /* Loading */
        .loading { text-align: center; padding: 40px; }
        .spinner { border: 3px solid #f3f3f3; border-top: 3px solid #3b82f6; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        @media (max-width: 768px) {
            .layout { flex-direction: column; }
            .sidebar { width: 100%; }
            .stats-grid { grid-template-columns: 1fr; }
            .bar-label { width: 80px; font-size: 11px; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>🛡️ Admin Panel</h1>
            </div>
            <nav class="nav">
                <a href="/admin" class="nav-link active">📊<span>Dashboard</span></a>
                <a href="/admin/packages" class="nav-link">📦<span>Paket</span></a>
                <a href="/admin/vouchers" class="nav-link">🎫<span>Voucher</span></a>
                <a href="/admin/transactions" class="nav-link">🛒<span>Transaksi</span></a>
                <a href="/" class="nav-link">🏠<span>Beranda</span></a>
            </nav>
        </aside>

        <!-- Main -->
        <main class="main">
            <div class="header">
                <h2>Dashboard</h2>
                <p>Overview sistem voucher internet</p>
            </div>

            <div class="content">
                <div id="loading" class="loading">
                    <div class="spinner"></div>
                    <p style="margin-top:12px;color:#6b7280;">Memuat data...</p>
                </div>

                <div id="dashboard-content" style="display:none;">
                    <!-- Stats -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-label">TOTAL PENDAPATAN</div>
                                    <div class="stat-value" id="total-revenue">-</div>
                                </div>
                                <div class="stat-icon icon-green">💰</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-label">TOTAL TRANSAKSI</div>
                                    <div class="stat-value" id="total-transactions">-</div>
                                </div>
                                <div class="stat-icon icon-blue">🛒</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-label">VOUCHER TERJUAL</div>
                                    <div class="stat-value" id="vouchers-sold">-</div>
                                </div>
                                <div class="stat-icon icon-purple">🎫</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-header">
                                <div>
                                    <div class="stat-label">STOK TERSEDIA</div>
                                    <div class="stat-value" id="available-stock">-</div>
                                </div>
                                <div class="stat-icon icon-yellow">📦</div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Alert -->
                    <div id="low-stock-alert" class="alert" style="display:none;">
                        <h4>⚠️ Peringatan Stok Rendah</h4>
                        <div id="low-stock-packages" class="alert-content"></div>
                    </div>

                    <!-- Sales Chart -->
                    <div class="chart-card">
                        <h3>Penjualan per Paket</h3>
                        <div id="sales-chart" class="bar-chart"></div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="table-card">
                        <h3>Informasi Sistem</h3>
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
                                        <td><span class="badge badge-success">Aktif</span></td>
                                    </tr>
                                    <tr>
                                        <td>Total Paket</td>
                                        <td id="total-packages">10</td>
                                        <td><span class="badge badge-success">Ready</span></td>
                                    </tr>
                                    <tr>
                                        <td>Database</td>
                                        <td>MariaDB 10.4</td>
                                        <td><span class="badge badge-success">Connected</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadDashboard();
        });

        async function loadDashboard() {
            try {
                const response = await fetch('/api/v1/packages');
                const data = await response.json();

                if (data.success) {
                    const packages = data.data;
                    
                    let totalRevenue = 0;
                    let totalTransactions = 0;
                    let vouchersSold = 0;
                    let availableStock = 0;
                    const lowStockPackages = [];
                    const salesData = [];

                    packages.forEach(pkg => {
                        availableStock += pkg.stock.available;
                        const sold = 20 - pkg.stock.available;
                        vouchersSold += sold;
                        totalTransactions += sold;
                        totalRevenue += parseFloat(pkg.price) * sold;

                        if (pkg.stock.available < 10 && pkg.stock.available > 0) {
                            lowStockPackages.push(pkg);
                        }

                        salesData.push({
                            name: pkg.name,
                            sold: sold,
                            max: 20
                        });
                    });

                    // Update stats
                    document.getElementById('total-revenue').textContent = 'Rp ' + formatPrice(totalRevenue);
                    document.getElementById('total-transactions').textContent = totalTransactions;
                    document.getElementById('vouchers-sold').textContent = vouchersSold;
                    document.getElementById('available-stock').textContent = availableStock;
                    document.getElementById('total-packages').textContent = packages.length;

                    // Low stock alert
                    if (lowStockPackages.length > 0) {
                        document.getElementById('low-stock-alert').style.display = 'block';
                        document.getElementById('low-stock-packages').innerHTML = 
                            lowStockPackages.map(pkg => `• ${pkg.name}: <strong>${pkg.stock.available}</strong> tersisa`).join('<br>');
                    }

                    // Sales chart
                    const chartHtml = salesData.map(item => {
                        const percentage = (item.sold / item.max) * 100;
                        return `
                            <div class="bar-item">
                                <div class="bar-label" title="${item.name}">${item.name}</div>
                                <div class="bar-visual">
                                    <div class="bar-fill" style="width: ${percentage}%">${item.sold > 0 ? item.sold : ''}</div>
                                </div>
                                <div class="bar-count">${item.sold}</div>
                            </div>
                        `;
                    }).join('');
                    document.getElementById('sales-chart').innerHTML = chartHtml;

                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('dashboard-content').style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading dashboard:', error);
                document.getElementById('loading').innerHTML = '<p style="color:#dc2626;">Gagal memuat data dashboard</p>';
            }
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price);
        }
    </script>
</body>
</html>
