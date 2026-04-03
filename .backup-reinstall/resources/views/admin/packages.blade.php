<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Paket - Admin</title>
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
                <a href="/admin/packages" class="flex items-center px-6 py-3 bg-blue-600 text-white">
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
                <div class="px-6 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">Kelola Paket</h2>
                    <p class="text-gray-600">Daftar semua paket voucher internet</p>
                </div>
            </header>

            <div class="p-6">
                <!-- Loading -->
                <div id="loading" class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i>
                    <p class="text-gray-600 mt-4">Memuat data paket...</p>
                </div>

                <!-- Packages Table -->
                <div id="packages-table" class="hidden bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Paket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody id="packages-tbody" class="bg-white divide-y divide-gray-200">
                            <!-- Packages will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadPackages();
        });

        async function loadPackages() {
            try {
                const response = await fetch('/api/v1/packages');
                const data = await response.json();

                if (data.success) {
                    displayPackages(data.data);
                    document.getElementById('loading').classList.add('hidden');
                    document.getElementById('packages-table').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading packages:', error);
            }
        }

        function displayPackages(packages) {
            const tbody = document.getElementById('packages-tbody');
            tbody.innerHTML = packages.map(pkg => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${pkg.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${pkg.name}</div>
                        <div class="text-sm text-gray-500">${pkg.description}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${pkg.type === 'time' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'}">
                            ${pkg.type === 'time' ? 'Waktu' : 'Kuota'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${pkg.value}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Rp ${formatPrice(pkg.price)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-semibold ${pkg.stock.available < 10 ? 'text-red-600' : 'text-green-600'}">
                            ${pkg.stock.available}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${pkg.stock.is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${pkg.stock.is_available ? 'Tersedia' : 'Habis'}
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price);
        }
    </script>
</body>
</html>
