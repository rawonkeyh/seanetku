<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beli Voucher Internet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        <i class="fas fa-wifi text-blue-500"></i> Voucher Internet
                    </h1>
                    <p class="text-gray-600 mt-1">Pilih paket internet sesuai kebutuhan Anda</p>
                </div>
                <a href="/admin" class="text-sm text-gray-600 hover:text-gray-900">
                    <i class="fas fa-user-shield"></i> Admin
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Loading State -->
        <div id="loading" class="text-center py-12">
            <i class="fas fa-spinner fa-spin text-4xl text-blue-500"></i>
            <p class="text-gray-600 mt-4">Memuat paket voucher...</p>
        </div>

        <!-- Error State -->
        <div id="error" class="hidden bg-red-50 border border-red-200 rounded-lg p-6 text-center">
            <i class="fas fa-exclamation-circle text-4xl text-red-500"></i>
            <p class="text-red-700 mt-4" id="error-message"></p>
            <button onclick="loadPackages()" class="mt-4 bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                Coba Lagi
            </button>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-8 hidden" id="filter-section">
            <div class="flex gap-4 border-b border-gray-200">
                <button onclick="filterPackages('all')" class="filter-btn px-6 py-3 font-semibold border-b-2 border-blue-500 text-blue-600" data-filter="all">
                    Semua Paket
                </button>
                <button onclick="filterPackages('time')" class="filter-btn px-6 py-3 font-semibold text-gray-600 hover:text-gray-900" data-filter="time">
                    Paket Waktu
                </button>
                <button onclick="filterPackages('quota')" class="filter-btn px-6 py-3 font-semibold text-gray-600 hover:text-gray-900" data-filter="quota">
                    Paket Kuota
                </button>
            </div>
        </div>

        <!-- Packages Grid -->
        <div id="packages-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Packages will be loaded here -->
        </div>
    </main>

    <!-- Checkout Modal -->
    <div id="checkout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Checkout</h2>
                <button onclick="closeCheckout()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Selected Package Info -->
            <div id="selected-package-info" class="bg-blue-50 rounded-lg p-4 mb-6">
                <!-- Package info will be loaded here -->
            </div>

            <!-- Customer Form -->
            <form id="checkout-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="customer_name" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="customer_email" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                    <input type="tel" id="customer_phone" required placeholder="08xxxxxxxxxx" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submit-btn" class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                    <i class="fas fa-shopping-cart mr-2"></i> Bayar Sekarang
                </button>
            </form>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        let allPackages = [];
        let selectedPackage = null;

        // Load packages on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadPackages();
        });

        async function loadPackages() {
            try {
                document.getElementById('loading').classList.remove('hidden');
                document.getElementById('error').classList.add('hidden');

                const response = await fetch('/api/v1/packages');
                const data = await response.json();

                if (data.success) {
                    allPackages = data.data;
                    displayPackages(allPackages);
                    document.getElementById('loading').classList.add('hidden');
                    document.getElementById('filter-section').classList.remove('hidden');
                } else {
                    throw new Error('Failed to load packages');
                }
            } catch (error) {
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('error').classList.remove('hidden');
                document.getElementById('error-message').textContent = 'Gagal memuat paket voucher. Silakan coba lagi.';
            }
        }

        function displayPackages(packages) {
            const grid = document.getElementById('packages-grid');
            grid.innerHTML = packages.map(pkg => `
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6 border border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold ${pkg.type === 'time' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700'}">
                                ${pkg.type === 'time' ? '⏱ Waktu' : '📊 Kuota'}
                            </span>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900">Rp ${formatPrice(pkg.price)}</div>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2">${pkg.name}</h3>
                    <p class="text-gray-600 mb-4">${pkg.description}</p>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-box mr-1"></i> Stok: <span class="font-semibold ${pkg.stock.available < 10 ? 'text-red-600' : 'text-green-600'}">${pkg.stock.available}</span>
                        </div>
                        ${pkg.stock.available < 10 ? '<span class="text-xs text-red-600 font-semibold">⚠ Stok Terbatas!</span>' : ''}
                    </div>
                    
                    <button onclick='selectPackage(${JSON.stringify(pkg)})' 
                            ${!pkg.stock.is_available ? 'disabled' : ''}
                            class="w-full py-2 rounded-lg font-semibold transition ${pkg.stock.is_available ? 'bg-blue-500 text-white hover:bg-blue-600' : 'bg-gray-300 text-gray-500 cursor-not-allowed'}">
                        ${pkg.stock.is_available ? '<i class="fas fa-shopping-cart mr-2"></i> Beli Sekarang' : 'Stok Habis'}
                    </button>
                </div>
            `).join('');
        }

        function filterPackages(type) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('text-gray-600');
            });

            const activeBtn = document.querySelector(`[data-filter="${type}"]`);
            activeBtn.classList.add('border-blue-500', 'text-blue-600');
            activeBtn.classList.remove('text-gray-600');

            const filtered = type === 'all' ? allPackages : allPackages.filter(pkg => pkg.type === type);
            displayPackages(filtered);
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price);
        }

        function selectPackage(pkg) {
            selectedPackage = pkg;
            document.getElementById('selected-package-info').innerHTML = `
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg">${pkg.name}</h3>
                        <p class="text-sm text-gray-600">${pkg.description}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600">Rp ${formatPrice(pkg.price)}</div>
                    </div>
                </div>
            `;
            document.getElementById('checkout-modal').classList.remove('hidden');
        }

        function closeCheckout() {
            document.getElementById('checkout-modal').classList.add('hidden');
            document.getElementById('checkout-form').reset();
        }

        document.getElementById('checkout-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

            try {
                const response = await fetch('/api/v1/transactions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        package_id: selectedPackage.id,
                        customer_name: document.getElementById('customer_name').value,
                        customer_email: document.getElementById('customer_email').value,
                        customer_phone: document.getElementById('customer_phone').value,
                    })
                });

                const data = await response.json();

                if (data.success) {
                    closeCheckout();
                    
                    // Open Midtrans Snap
                    snap.pay(data.data.payment.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = `/success?order_id=${data.data.order_id}`;
                        },
                        onPending: function(result) {
                            window.location.href = `/success?order_id=${data.data.order_id}`;
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal! Silakan coba lagi.');
                            loadPackages();
                        },
                        onClose: function() {
                            alert('Anda menutup jendela pembayaran. Transaksi masih menunggu pembayaran.');
                        }
                    });
                } else {
                    throw new Error(data.message || 'Transaksi gagal');
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i> Bayar Sekarang';
            }
        });
    </script>
</body>
</html>
