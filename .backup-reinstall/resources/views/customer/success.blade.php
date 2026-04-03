<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-2xl w-full">
            <!-- Loading State -->
            <div id="loading" class="bg-white rounded-lg shadow-lg p-8 text-center">
                <i class="fas fa-spinner fa-spin text-5xl text-blue-500"></i>
                <p class="text-gray-600 mt-4 text-lg">Memuat status transaksi...</p>
            </div>

            <!-- Success State -->
            <div id="success" class="hidden bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                        <i class="fas fa-check-circle text-5xl text-green-500"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h1>
                    <p class="text-gray-600">Terima kasih telah membeli voucher internet</p>
                </div>

                <!-- Transaction Details -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h2 class="font-semibold text-lg mb-4">Detail Transaksi</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order ID:</span>
                            <span class="font-mono font-semibold" id="order-id"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Paket:</span>
                            <span class="font-semibold" id="package-name"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total:</span>
                            <span class="font-bold text-lg" id="amount"></span>
                        </div>
                    </div>
                </div>

                <!-- Voucher Credentials -->
                <div id="voucher-section" class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
                    <h2 class="font-semibold text-lg mb-4 text-blue-900">
                        <i class="fas fa-ticket-alt mr-2"></i>Kredensial Voucher Anda
                    </h2>
                    <div class="bg-white rounded-lg p-4 space-y-3">
                        <div>
                            <label class="text-sm text-gray-600">Username:</label>
                            <div class="flex items-center gap-2">
                                <input type="text" id="username" readonly class="flex-1 font-mono font-bold text-lg border border-gray-300 rounded px-3 py-2 bg-gray-50">
                                <button onclick="copyToClipboard('username')" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Password:</label>
                            <div class="flex items-center gap-2">
                                <input type="text" id="password" readonly class="flex-1 font-mono font-bold text-lg border border-gray-300 rounded px-3 py-2 bg-gray-50">
                                <button onclick="copyToClipboard('password')" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-blue-800 bg-blue-100 rounded p-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Penting:</strong> Simpan kredensial ini dengan baik. Anda dapat menggunakannya untuk login ke sistem WiFi.
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button onclick="window.print()" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </button>
                    <button onclick="window.location.href='/'" class="flex-1 bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                        <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                    </button>
                </div>
            </div>

            <!-- Pending State -->
            <div id="pending" class="hidden bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full mb-4">
                        <i class="fas fa-clock text-5xl text-yellow-500"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Menunggu Pembayaran</h1>
                    <p class="text-gray-600">Transaksi Anda sedang menunggu konfirmasi pembayaran</p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <p class="text-sm text-yellow-800 mb-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        Order ID: <span class="font-mono font-bold" id="pending-order-id"></span>
                    </p>
                    <p class="text-sm text-yellow-800">
                        Silakan selesaikan pembayaran Anda. Voucher akan dikirimkan setelah pembayaran dikonfirmasi.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button onclick="location.reload()" class="flex-1 bg-yellow-500 text-white py-3 rounded-lg font-semibold hover:bg-yellow-600 transition">
                        <i class="fas fa-sync-alt mr-2"></i>Cek Status
                    </button>
                    <button onclick="window.location.href='/'" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                        <i class="fas fa-home mr-2"></i>Kembali
                    </button>
                </div>
            </div>

            <!-- Error State -->
            <div id="error" class="hidden bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                        <i class="fas fa-times-circle text-5xl text-red-500"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Transaksi Tidak Ditemukan</h1>
                    <p class="text-gray-600" id="error-message"></p>
                </div>

                <button onclick="window.location.href='/'" class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                    <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                </button>
            </div>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const orderId = urlParams.get('order_id');

        if (!orderId) {
            showError('Order ID tidak ditemukan');
        } else {
            checkTransactionStatus();
        }

        async function checkTransactionStatus() {
            try {
                const response = await fetch(`/api/v1/transactions/order/${orderId}/status`);
                const data = await response.json();

                document.getElementById('loading').classList.add('hidden');

                if (data.success) {
                    const transaction = data.data;

                    if (transaction.status === 'paid') {
                        showSuccess(transaction);
                    } else if (transaction.status === 'pending') {
                        showPending(transaction);
                    } else {
                        showError('Status transaksi: ' + transaction.status);
                    }
                } else {
                    showError(data.message || 'Transaksi tidak ditemukan');
                }
            } catch (error) {
                document.getElementById('loading').classList.add('hidden');
                showError('Gagal memuat status transaksi');
            }
        }

        function showSuccess(transaction) {
            document.getElementById('order-id').textContent = transaction.order_id;
            document.getElementById('package-name').textContent = transaction.package.name;
            document.getElementById('amount').textContent = 'Rp ' + formatPrice(transaction.amount);

            if (transaction.voucher) {
                document.getElementById('username').value = transaction.voucher.username;
                document.getElementById('password').value = transaction.voucher.password;
                document.getElementById('voucher-section').classList.remove('hidden');
            } else {
                document.getElementById('voucher-section').classList.add('hidden');
            }

            document.getElementById('success').classList.remove('hidden');
        }

        function showPending(transaction) {
            document.getElementById('pending-order-id').textContent = transaction.order_id;
            document.getElementById('pending').classList.remove('hidden');
        }

        function showError(message) {
            document.getElementById('error-message').textContent = message;
            document.getElementById('error').classList.remove('hidden');
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price);
        }

        function copyToClipboard(fieldId) {
            const input = document.getElementById(fieldId);
            input.select();
            document.execCommand('copy');
            
            // Show feedback
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                button.innerHTML = originalHTML;
            }, 1000);
        }
    </script>
</body>
</html>
