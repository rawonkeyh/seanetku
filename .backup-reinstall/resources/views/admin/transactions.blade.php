<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <a href="/admin" class="flex items-center px-6 py-3 hover:bg-gray-800 transition">
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
                <a href="/admin/transactions" class="flex items-center px-6 py-3 bg-blue-600 text-white">
                    <i class="fas fa-shopping-cart w-5"></i>
                    <span class="ml-3">Transaksi</span>
                </a>
                <a href="/" class="flex items-center px-6 py-3 hover:bg-gray-800 transition">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-3">Ke Halaman Utama</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Transaksi</h2>
                        <p class="text-gray-600">Riwayat semua transaksi pembelian</p>
                    </div>
                    <select id="status-filter" class="border border-gray-300 rounded-lg px-4 py-2" onchange="filterByStatus()">
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
            </header>

            <div class="p-6">
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Pending</p>
                                <p class="text-2xl font-bold text-yellow-600" id="pending-count">-</p>
                            </div>
                            <i class="fas fa-clock text-3xl text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Berhasil</p>
                                <p class="text-2xl font-bold text-green-600" id="paid-count">-</p>
                            </div>
                            <i class="fas fa-check-circle text-3xl text-green-600"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Gagal</p>
                                <p class="text-2xl font-bold text-red-600" id="failed-count">-</p>
                            </div>
                            <i class="fas fa-times-circle text-3xl text-red-600"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Expired</p>
                                <p class="text-2xl font-bold text-gray-600" id="expired-count">-</p>
                            </div>
                            <i class="fas fa-ban text-3xl text-gray-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Loading -->
                <div id="loading" class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i>
                    <p class="text-gray-600 mt-4">Memuat data transaksi...</p>
                </div>

                <!-- Transactions Table -->
                <div id="transactions-table" class="hidden bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="transactions-tbody" class="bg-white divide-y divide-gray-200">
                                <!-- Transactions will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let allTransactions = [];

        document.addEventListener('DOMContentLoaded', () => {
            loadTransactions();
        });

        async function loadTransactions() {
            try {
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('transactions-table').classList.remove('hidden');
                
                // Mock stats
                updateStats(0, 0, 0, 0);
                
                displayMessage('Data transaksi akan muncul di sini setelah ada pembelian voucher');
            } catch (error) {
                console.error('Error loading transactions:', error);
            }
        }

        function updateStats(pending, paid, failed, expired) {
            document.getElementById('pending-count').textContent = pending;
            document.getElementById('paid-count').textContent = paid;
            document.getElementById('failed-count').textContent = failed;
            document.getElementById('expired-count').textContent = expired;
        }

        function displayMessage(message) {
            document.getElementById('transactions-tbody').innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-info-circle text-4xl mb-4 block"></i>
                        ${message}
                    </td>
                </tr>
            `;
        }

        function filterByStatus() {
            const status = document.getElementById('status-filter').value;
            // Implementation for filtering
            loadTransactions();
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price);
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    </script>
</body>
</html>
