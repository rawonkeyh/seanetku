<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Voucher - Admin</title>
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
                <a href="/admin/vouchers" class="flex items-center px-6 py-3 bg-blue-600 text-white">
                    <i class="fas fa-ticket-alt w-5"></i>
                    <span class="ml-3">Voucher</span>
                </a>
                <a href="/admin/transactions" class="flex items-center px-6 py-3 hover:bg-gray-800 transition">
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
                        <h2 class="text-2xl font-bold text-gray-800">Kelola Voucher</h2>
                        <p class="text-gray-600">Daftar semua voucher dan statusnya</p>
                    </div>
                    <div class="flex gap-2">
                        <select id="status-filter" class="border border-gray-300 rounded-lg px-4 py-2" onchange="filterByStatus()">
                            <option value="all">Semua Status</option>
                            <option value="available">Tersedia</option>
                            <option value="reserved">Direservasi</option>
                            <option value="sold">Terjual</option>
                        </select>
                        <select id="package-filter" class="border border-gray-300 rounded-lg px-4 py-2" onchange="filterByPackage()">
                            <option value="all">Semua Paket</option>
                        </select>
                    </div>
                </div>
            </header>

            <div class="p-6">
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Tersedia</p>
                                <p class="text-2xl font-bold text-green-600" id="available-count">-</p>
                            </div>
                            <i class="fas fa-check-circle text-3xl text-green-600"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Direservasi</p>
                                <p class="text-2xl font-bold text-yellow-600" id="reserved-count">-</p>
                            </div>
                            <i class="fas fa-clock text-3xl text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Terjual</p>
                                <p class="text-2xl font-bold text-blue-600" id="sold-count">-</p>
                            </div>
                            <i class="fas fa-shopping-cart text-3xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Loading -->
                <div id="loading" class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i>
                    <p class="text-gray-600 mt-4">Memuat data voucher...</p>
                </div>

                <!-- Vouchers Table -->
                <div id="vouchers-table" class="hidden bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terjual</th>
                                </tr>
                            </thead>
                            <tbody id="vouchers-tbody" class="bg-white divide-y divide-gray-200">
                                <!-- Vouchers will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span id="showing-from">1</span> - <span id="showing-to">50</span> dari <span id="total-vouchers">0</span> voucher
                        </div>
                        <div class="flex gap-2">
                            <button onclick="previousPage()" id="prev-btn" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i> Prev
                            </button>
                            <button onclick="nextPage()" id="next-btn" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let allVouchers = [];
        let filteredVouchers = [];
        let currentPage = 1;
        const perPage = 50;

        document.addEventListener('DOMContentLoaded', () => {
            loadPackagesForFilter();
            loadVouchers();
        });

        async function loadPackagesForFilter() {
            try {
                const response = await fetch('/api/v1/packages');
                const data = await response.json();
                if (data.success) {
                    const select = document.getElementById('package-filter');
                    data.data.forEach(pkg => {
                        const option = document.createElement('option');
                        option.value = pkg.id;
                        option.textContent = pkg.name;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading packages:', error);
            }
        }

        async function loadVouchers() {
            try {
                // Note: In production, you'd create an API endpoint for vouchers
                // For now, we'll simulate it
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('vouchers-table').classList.remove('hidden');
                
                // Mock data - in production, fetch from API
                updateStats(0, 0, 0);
                displayMessage('API endpoint untuk voucher belum tersedia. Silakan tambahkan endpoint di routes/api.php');
            } catch (error) {
                console.error('Error loading vouchers:', error);
            }
        }

        function updateStats(available, reserved, sold) {
            document.getElementById('available-count').textContent = available;
            document.getElementById('reserved-count').textContent = reserved;
            document.getElementById('sold-count').textContent = sold;
        }

        function displayMessage(message) {
            document.getElementById('vouchers-tbody').innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-info-circle text-4xl mb-4 block"></i>
                        ${message}
                    </td>
                </tr>
            `;
        }

        function filterByStatus() {
            // Implementation for filtering
            loadVouchers();
        }

        function filterByPackage() {
            // Implementation for filtering
            loadVouchers();
        }

        function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                displayVouchers();
            }
        }

        function nextPage() {
            const totalPages = Math.ceil(filteredVouchers.length / perPage);
            if (currentPage < totalPages) {
                currentPage++;
                displayVouchers();
            }
        }
    </script>
</body>
</html>
