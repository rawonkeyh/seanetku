<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Voucher System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-shield-alt"></i> Admin Panel
                </h1>
            </div>
            <nav class="mt-6">
                <a href="/admin" class="flex items-center px-6 py-3 bg-blue-600 text-white">
                    <i class="fas fa-chart-line w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="/admin/packages" class="flex items-center px-6 py-3 hover:bg-gray-800 transition">
                    <i class="fas fa-box w-5"></i>
                    <span class="ml-3">Paket</span>
                </a>
                <a href="/admin/vouchers" class="flex items-center px-6 py-3 hover:bg-gray-800 transition">
                    <i class="fas fa-ticket-alt w-5"></i>
                    <span class="ml-3">Voucher</span>
                </a>
                <a href="/admin/transactions" class="flex items-center px-6 py-3 hover:bg-gray-800 transition">
                    <i class="fas fa-shopping-cart w-5"></i>
                    <span class="ml-3">Transaksi</span>
                </a>
                <a href="/" class="flex items-center px-6 py-3 hover:bg-gray-800 transition mt-auto">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-3">Ke Halaman Utama</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
                    <p class="text-gray-600">Overview sistem voucher internet</p>
                </div>
            </header>

            <div class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Total Revenue -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Pendapatan</p>
                                <p class="text-2xl font-bold text-gray-900" id="total-revenue">-</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full">
                                <i class="fas fa-dollar-sign text-2xl text-green-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Transactions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Transaksi</p>
                                <p class="text-2xl font-bold text-gray-900" id="total-transactions">-</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-shopping-cart text-2xl text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Vouchers Sold -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Voucher Terjual</p>
                                <p class="text-2xl font-bold text-gray-900" id="vouchers-sold">-</p>
                            </div>
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i class="fas fa-ticket-alt text-2xl text-purple-600"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Available Stock -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Stok Tersedia</p>
                                <p class="text-2xl font-bold text-gray-900" id="available-stock">-</p>
                            </div>
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <i class="fas fa-box text-2xl text-yellow-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Sales Chart -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Penjualan per Paket</h3>
                        <canvas id="salesChart"></canvas>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Transaksi Terbaru</h3>
                        <div id="recent-transactions" class="space-y-3">
                            <!-- Transactions will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <div id="low-stock-alert" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl mt-1 mr-3"></i>
                        <div class="flex-1">
                            <h4 class="font-bold text-red-900 mb-2">Peringatan Stok Rendah</h4>
                            <div id="low-stock-packages" class="text-sm text-red-800">
                                <!-- Low stock packages will be listed here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let salesChart = null;

        document.addEventListener('DOMContentLoaded', () => {
            loadDashboardData();
        });

        async function loadDashboardData() {
            try {
                // Load packages and calculate stats
                const packagesResponse = await fetch('/api/v1/packages');
                const packagesData = await packagesResponse.json();

                if (packagesData.success) {
                    const packages = packagesData.data;
                    
                    // Calculate stats
                    let totalRevenue = 0;
                    let totalTransactions = 0;
                    let vouchersSold = 0;
                    let availableStock = 0;
                    const lowStockPackages = [];

                    packages.forEach(pkg => {
                        availableStock += pkg.stock.available;
                        const sold = 20 - pkg.stock.available; // Assuming 20 initial per package
                        vouchersSold += sold;
                        totalTransactions += sold;
                        totalRevenue += parseFloat(pkg.price) * sold;

                        if (pkg.stock.available < 10 && pkg.stock.available > 0) {
                            lowStockPackages.push(pkg);
                        }
                    });

                    // Update stats
                    document.getElementById('total-revenue').textContent = 'Rp ' + formatPrice(totalRevenue);
                    document.getElementById('total-transactions').textContent = totalTransactions;
                    document.getElementById('vouchers-sold').textContent = vouchersSold;
                    document.getElementById('available-stock').textContent = availableStock;

                    // Show low stock alert
                    if (lowStockPackages.length > 0) {
                        document.getElementById('low-stock-alert').classList.remove('hidden');
                        document.getElementById('low-stock-packages').innerHTML = lowStockPackages.map(pkg => 
                            `<div class="mb-1">• ${pkg.name}: <strong>${pkg.stock.available}</strong> tersisa</div>`
                        ).join('');
                    }

                    // Create sales chart
                    createSalesChart(packages);
                    
                    // Load recent transactions (mock data for now)
                    loadRecentTransactions();
                }
            } catch (error) {
                console.error('Error loading dashboard:', error);
            }
        }

        function createSalesChart(packages) {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            const labels = packages.map(pkg => pkg.name);
            const data = packages.map(pkg => 20 - pkg.stock.available);

            if (salesChart) {
                salesChart.destroy();
            }

            salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Voucher Terjual',
                        data: data,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        function loadRecentTransactions() {
            // Mock recent transactions
            const recentTrans = document.getElementById('recent-transactions');
            recentTrans.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-info-circle text-3xl mb-2"></i>
                    <p>Data transaksi akan muncul di sini setelah ada pembelian</p>
                </div>
            `;
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price);
        }
    </script>
</body>
</html>
