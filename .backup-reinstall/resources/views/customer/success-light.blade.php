<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Transaksi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background: #f5f5f5; line-height: 1.6; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { max-width: 600px; width: 100%; }
        .card { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        
        /* Loading */
        .loading { text-align: center; }
        .spinner { border: 3px solid #f3f3f3; border-top: 3px solid #3b82f6; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 0 auto 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        /* Success/Error Icons */
        .icon { width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; font-size: 48px; }
        .icon-success { background: #dcfce7; color: #16a34a; }
        .icon-pending { background: #fef3c7; color: #d97706; }
        .icon-error { background: #fee2e2; color: #dc2626; }
        
        h1 { font-size: 28px; text-align: center; margin-bottom: 10px; color: #333; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; font-size: 14px; }
        
        /* Transaction Details */
        .details { background: #f9fafb; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .details h2 { font-size: 16px; font-weight: 600; margin-bottom: 15px; color: #333; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .detail-row:last-child { margin-bottom: 0; }
        .detail-label { color: #666; }
        .detail-value { font-weight: 600; color: #333; font-family: monospace; }
        .detail-amount { font-size: 18px; font-weight: 700; color: #3b82f6; }
        
        /* Voucher Box */
        .voucher-box { background: #eff6ff; border: 2px solid #bfdbfe; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .voucher-box h2 { font-size: 16px; font-weight: 600; margin-bottom: 15px; color: #1e40af; }
        .voucher-field { background: #fff; border: 1px solid#dbeafe; border-radius: 6px; padding: 12px; margin-bottom: 12px; }
        .voucher-field:last-child { margin-bottom: 0; }
        .voucher-label { font-size: 12px; color: #666; margin-bottom: 4px; }
        .voucher-value { font-size: 16px; font-weight: 700; font-family: monospace; color: #1e40af; }
        .copy-btn { background: #3b82f6; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; margin-top: 8px; }
        .copy-btn:hover { background: #2563eb; }
        
        /* Alert */
        .alert { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 13px; }
        .alert-warning { background: #fef3c7; border-color: #fde68a; color: #92400e; }
        
        /* Buttons */
        .btn { width: 100%; padding: 14px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: #3b82f6; color: #fff; margin-bottom: 10px; }
        .btn-primary:hover { background: #2563eb; }
        .btn-secondary { background: #f3f4f6; color: #4b5563; }
        .btn-secondary:hover { background: #e5e7eb; }
        
        @media (max-width: 480px) {
            .card { padding: 20px; }
            h1 { font-size: 24px; }
            .icon { width: 60px; height: 60px; font-size: 36px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Loading -->
        <div id="loading" class="card loading">
            <div class="spinner"></div>
            <p style="text-align:center;color:#666;">Memuat status transaksi...</p>
        </div>

        <!-- Success -->
        <div id="success" class="card" style="display:none;">
            <div class="icon icon-success">✓</div>
            <h1>Pembayaran Berhasil!</h1>
            <p class="subtitle">Terima kasih telah membeli voucher internet</p>
            
            <div class="details">
                <h2>Detail Transaksi</h2>
                <div class="detail-row">
                    <span class="detail-label">Order ID:</span>
                    <span class="detail-value" id="order-id"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Paket:</span>
                    <span class="detail-value" id="package-name"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total:</span>
                    <span class="detail-amount" id="amount"></span>
                </div>
            </div>

            <div id="voucher-section" class="voucher-box">
                <h2>🎟️ Kredensial Voucher Anda</h2>
                <div class="voucher-field">
                    <div class="voucher-label">Username:</div>
                    <div class="voucher-value" id="username"></div>
                    <button onclick="copy('username')" class="copy-btn">📋 Copy</button>
                </div>
                <div class="voucher-field">
                    <div class="voucher-label">Password:</div>
                    <div class="voucher-value" id="password"></div>
                    <button onclick="copy('password')" class="copy-btn">📋 Copy</button>
                </div>
                <div class="alert">
                    <strong>Penting:</strong> Simpan kredensial ini dengan baik untuk login ke WiFi.
                </div>
            </div>

            <button onclick="window.print()" class="btn btn-secondary">🖨️ Cetak</button>
            <button onclick="window.location.href='/'" class="btn btn-primary">🏠 Kembali ke Beranda</button>
        </div>

        <!-- Pending -->
        <div id="pending" class="card" style="display:none;">
            <div class="icon icon-pending">⏱</div>
            <h1>Menunggu Pembayaran</h1>
            <p class="subtitle">Transaksi Anda sedang menunggu konfirmasi</p>
            
            <div class="alert alert-warning">
                Order ID: <strong id="pending-order-id" style="font-family:monospace;"></strong><br>
                Silakan selesaikan pembayaran Anda.
            </div>

            <button onclick="location.reload()" class="btn btn-primary">🔄 Cek Status</button>
            <button onclick="window.location.href='/'" class="btn btn-secondary">🏠 Kembali</button>
        </div>

        <!-- Error -->
        <div id="error" class="card" style="display:none;">
            <div class="icon icon-error">✕</div>
            <h1>Transaksi Tidak Ditemukan</h1>
            <p class="subtitle" id="error-message"></p>
            <button onclick="window.location.href='/'" class="btn btn-primary">🏠 Kembali ke Beranda</button>
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

                document.getElementById('loading').style.display = 'none';

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
                document.getElementById('loading').style.display = 'none';
                showError('Gagal memuat status transaksi');
            }
        }

        function showSuccess(transaction) {
            document.getElementById('order-id').textContent = transaction.order_id;
            document.getElementById('package-name').textContent = transaction.package.name;
            document.getElementById('amount').textContent = 'Rp ' + formatPrice(transaction.amount);

            if (transaction.voucher) {
                document.getElementById('username').textContent = transaction.voucher.username;
                document.getElementById('password').textContent = transaction.voucher.password;
            } else {
                document.getElementById('voucher-section').style.display = 'none';
            }

            document.getElementById('success').style.display = 'block';
        }

        function showPending(transaction) {
            document.getElementById('pending-order-id').textContent = transaction.order_id;
            document.getElementById('pending').style.display = 'block';
        }

        function showError(message) {
            document.getElementById('error-message').textContent = message;
            document.getElementById('error').style.display = 'block';
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price);
        }

        function copy(fieldId) {
            const text = document.getElementById(fieldId).textContent;
            navigator.clipboard.writeText(text).then(() => {
                alert('Tersalin: ' + text);
            });
        }
    </script>
</body>
</html>
