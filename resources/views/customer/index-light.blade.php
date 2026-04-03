<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beli Voucher Internet</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background: #f5f5f5; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        
        /* Header */
        .header { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px 0; margin-bottom: 30px; }
        .header h1 { font-size: 24px; color: #333; }
        .header p { color: #666; font-size: 14px; margin-top: 5px; }
        
        /* Filter */
        .filter { display: flex; gap: 10px; margin-bottom: 30px; border-bottom: 2px solid #e0e0e0; }
        .filter button { background: none; border: none; padding: 12px 20px; cursor: pointer; font-size: 14px; font-weight: 600; color: #666; border-bottom: 2px solid transparent; margin-bottom: -2px; }
        .filter button.active { color: #3b82f6; border-bottom-color: #3b82f6; }
        .filter button:hover { color: #333; }
        
        /* Grid */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        
        /* Card */
        .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); transition: box-shadow 0.2s; }
        .card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .card-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .badge-time { background: #ede9fe; color: #7c3aed; }
        .badge-quota { background: #dcfce7; color: #16a34a; }
        .price { font-size: 24px; font-weight: 700; color: #333; }
        .card h3 { font-size: 18px; margin-bottom: 8px; color: #333; }
        .card p { font-size: 13px; color: #666; margin-bottom: 15px; }
        .stock { font-size: 13px; color: #666; margin-bottom: 15px; }
        .stock span { font-weight: 600; }
        .stock.low { color: #dc2626; }
        .btn { width: 100%; padding: 12px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
        .btn-primary { background: #3b82f6; color: #fff; }
        .btn-primary:hover { background: #2563eb; }
        .btn-disabled { background: #e5e7eb; color: #9ca3af; cursor: not-allowed; }
        
        /* Modal */
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; padding: 20px; overflow-y: auto; }
        .modal.active { display: flex; align-items: center; justify-content: center; }
        .modal-content { background: #fff; border-radius: 12px; max-width: 500px; width: 100%; padding: 30px; position: relative; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h2 { font-size: 22px; color: #333; }
        .close { background: none; border: none; font-size: 28px; cursor: pointer; color: #999; line-height: 1; }
        .close:hover { color: #333; }
        
        /* Form */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 6px; }
        .form-group input { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
        .form-group input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
        
        /* Package Info */
        .pkg-info { background: #eff6ff; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .pkg-info h3 { font-size: 16px; margin-bottom: 5px; }
        .pkg-info p { font-size: 13px; color: #666; margin-bottom: 10px; }
        .pkg-price { font-size: 22px; font-weight: 700; color: #3b82f6; }
        
        /* Loading */
        .loading { text-align: center; padding: 60px 20px; }
        .spinner { border: 3px solid #f3f3f3; border-top: 3px solid #3b82f6; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 15px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        /* Error */
        .error { background: #fee; border: 1px solid #fcc; color: #c33; padding: 20px; border-radius: 8px; text-align: center; }

        .pending-payment { display: none; background: #fff7ed; border: 1px solid #fed7aa; color: #9a3412; padding: 16px; border-radius: 8px; margin-bottom: 20px; }
        .pending-payment.active { display: block; }
        .pending-actions { display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap; }
        .pending-actions button { border: none; border-radius: 6px; padding: 10px 14px; font-size: 13px; font-weight: 600; cursor: pointer; }
        .btn-resume { background: #ea580c; color: #fff; }
        .btn-resume:hover { background: #c2410c; }
        .btn-clear { background: #fff; color: #9a3412; border: 1px solid #fdba74; }
        
        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; }
            .filter { overflow-x: auto; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🌐 Voucher Internet</h1>
            <p>Pilih paket internet sesuai kebutuhan Anda</p>
        </div>
    </div>

    <div class="container">
        <div id="pending-payment" class="pending-payment">
            <strong>Anda punya pembayaran yang belum selesai.</strong>
            <div style="font-size:13px;margin-top:6px;">Order ID: <span id="pending-order-id" style="font-family:monospace;"></span></div>
            <div style="font-size:13px;">Batas waktu: <span id="pending-expiry"></span></div>
            <div style="font-size:13px;">Sisa waktu: <span id="pending-countdown"></span></div>
            <div class="pending-actions">
                <button id="resume-payment-btn" class="btn-resume">Lanjutkan Pembayaran</button>
                <button id="clear-payment-btn" class="btn-clear">Ajukan Ulang</button>
            </div>
        </div>

        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p>Memuat paket voucher...</p>
        </div>

        <div id="error" style="display:none;" class="error">
            <p id="error-message"></p>
            <button onclick="loadPackages()" class="btn btn-primary" style="margin-top:15px;max-width:200px;">Coba Lagi</button>
        </div>

        <div id="filter-section" style="display:none;">
            <div class="filter">
                <button onclick="filterPackages('all')" class="active" data-filter="all">Semua Paket</button>
                <button onclick="filterPackages('time')" data-filter="time">Paket Waktu</button>
                <button onclick="filterPackages('quota')" data-filter="quota">Paket Kuota</button>
            </div>
        </div>

        <div id="packages-grid" class="grid"></div>
    </div>

    <!-- Modal -->
    <div id="checkout-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Checkout</h2>
                <button onclick="closeCheckout()" class="close">&times;</button>
            </div>
            <div id="selected-package-info" class="pkg-info"></div>
            <form id="checkout-form">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" id="customer_name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="customer_email" required>
                </div>
                <div class="form-group">
                    <label>No. WhatsApp</label>
                    <input type="tel" id="customer_phone" required placeholder="08xxxxxxxxxx">
                </div>
                <button type="submit" id="submit-btn" class="btn btn-primary">🛒 Bayar Sekarang</button>
            </form>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        let allPackages = [];
        let selectedPackage = null;
        let countdownInterval = null;
        const ACTIVE_PAYMENT_KEY = 'active_pending_payment';

        document.addEventListener('DOMContentLoaded', () => {
            restorePendingPayment();
            loadPackages();

            document.getElementById('resume-payment-btn').addEventListener('click', () => {
                const payment = getActivePayment();
                if (!payment) {
                    alert('Pembayaran sudah habis, silakan ajukan ulang.');
                    clearActivePayment();
                    return;
                }

                openMidtransPopup(payment);
            });

            document.getElementById('clear-payment-btn').addEventListener('click', () => {
                clearActivePayment();
                alert('Silakan ajukan checkout ulang.');
            });
        });

        function saveActivePayment(payload) {
            localStorage.setItem(ACTIVE_PAYMENT_KEY, JSON.stringify(payload));
            renderPendingPayment(payload);
        }

        function getActivePayment() {
            const raw = localStorage.getItem(ACTIVE_PAYMENT_KEY);
            if (!raw) return null;

            try {
                const data = JSON.parse(raw);
                if (!data.expires_at || Date.now() >= new Date(data.expires_at).getTime()) {
                    localStorage.removeItem(ACTIVE_PAYMENT_KEY);
                    return null;
                }

                return data;
            } catch (e) {
                localStorage.removeItem(ACTIVE_PAYMENT_KEY);
                return null;
            }
        }

        function clearActivePayment() {
            localStorage.removeItem(ACTIVE_PAYMENT_KEY);
            const box = document.getElementById('pending-payment');
            box.classList.remove('active');

            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
        }

        function restorePendingPayment() {
            const payment = getActivePayment();
            if (payment) {
                renderPendingPayment(payment);
            }
        }

        function renderPendingPayment(payment) {
            const box = document.getElementById('pending-payment');
            document.getElementById('pending-order-id').textContent = payment.order_id;
            document.getElementById('pending-expiry').textContent = formatDateTime(payment.expires_at);
            box.classList.add('active');

            if (countdownInterval) {
                clearInterval(countdownInterval);
            }

            const updateCountdown = () => {
                const diff = new Date(payment.expires_at).getTime() - Date.now();
                const countdownEl = document.getElementById('pending-countdown');

                if (diff <= 0) {
                    countdownEl.textContent = 'Waktu habis';
                    clearActivePayment();
                    alert('Waktu pembayaran habis. Silakan ajukan ulang.');
                    return;
                }

                const totalSeconds = Math.floor(diff / 1000);
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                countdownEl.textContent = `${minutes}m ${seconds}s`;
            };

            updateCountdown();
            countdownInterval = setInterval(updateCountdown, 1000);
        }

        function formatDateTime(value) {
            const date = new Date(value);
            return new Intl.DateTimeFormat('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }).format(date);
        }

        function openMidtransPopup(payment) {
            snap.pay(payment.snap_token, {
                onSuccess: function() {
                    clearActivePayment();
                    window.location.href = `/success?order_id=${payment.order_id}&token=${encodeURIComponent(payment.access_token)}`;
                },
                onPending: function() {
                    window.location.href = `/success?order_id=${payment.order_id}&token=${encodeURIComponent(payment.access_token)}`;
                },
                onError: function() {
                    alert('Pembayaran gagal! Anda dapat mencoba lagi selama belum melewati batas waktu.');
                },
                onClose: function() {
                    alert('Jendela Midtrans ditutup. Anda masih bisa melanjutkan pembayaran dari perangkat ini sampai waktu habis.');
                    renderPendingPayment(payment);
                }
            });
        }

        async function loadPackages() {
            try {
                document.getElementById('loading').style.display = 'block';
                document.getElementById('error').style.display = 'none';

                const response = await fetch('/api/v1/packages');
                const data = await response.json();

                // Laravel API Resource returns {data: [...], meta: {...}}
                if (data.data && Array.isArray(data.data)) {
                    allPackages = data.data;
                    displayPackages(allPackages);
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('filter-section').style.display = 'block';
                } else {
                    throw new Error('Invalid response format');
                }
            } catch (error) {
                console.error('Load error:', error);
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error').style.display = 'block';
                document.getElementById('error-message').textContent = 'Gagal memuat paket voucher. Silakan coba lagi.';
            }
        }

        function displayPackages(packages) {
            const grid = document.getElementById('packages-grid');
            grid.innerHTML = packages.map(pkg => `
                <div class="card">
                    <div class="card-header">
                        <span class="badge ${pkg.type === 'time' ? 'badge-time' : 'badge-quota'}">
                            ${pkg.type === 'time' ? '⏱ Waktu' : '📊 Kuota'}
                        </span>
                        <div class="price">Rp ${formatPrice(pkg.price)}</div>
                    </div>
                    <h3>${pkg.name}</h3>
                    <p>${pkg.description}</p>
                    <div class="stock ${pkg.stock.available < 10 ? 'low' : ''}">
                        Stok: <span>${pkg.stock.available}</span> ${pkg.stock.available < 10 ? '⚠️' : ''}
                    </div>
                    <button onclick='selectPackage(${JSON.stringify(pkg).replace(/'/g, "&#39;")})' 
                            ${!pkg.stock.is_available ? 'disabled' : ''}
                            class="btn ${pkg.stock.is_available ? 'btn-primary' : 'btn-disabled'}">
                        ${pkg.stock.is_available ? 'Beli Sekarang' : 'Stok Habis'}
                    </button>
                </div>
            `).join('');
        }

        function filterPackages(type) {
            const buttons = document.querySelectorAll('.filter button');
            buttons.forEach(btn => {
                btn.classList.remove('active');
                if(btn.getAttribute('data-filter') === type) btn.classList.add('active');
            });
            const filtered = type === 'all' ? allPackages : allPackages.filter(pkg => pkg.type === type);
            displayPackages(filtered);
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price);
        }

        function selectPackage(pkg) {
            selectedPackage = pkg;
            document.getElementById('selected-package-info').innerHTML = `
                <h3>${pkg.name}</h3>
                <p>${pkg.description}</p>
                <div class="pkg-price">Rp ${formatPrice(pkg.price)}</div>
            `;
            document.getElementById('checkout-modal').classList.add('active');
        }

        function closeCheckout() {
            document.getElementById('checkout-modal').classList.remove('active');
            document.getElementById('checkout-form').reset();
        }

        document.getElementById('checkout-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = '⌛ Memproses...';

            try {
                const response = await fetch('/api/v1/transactions', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        package_id: selectedPackage.id,
                        customer_name: document.getElementById('customer_name').value,
                        customer_email: document.getElementById('customer_email').value,
                        customer_phone: document.getElementById('customer_phone').value,
                    })
                });

                const data = await response.json();

                // Check if transaction was created successfully
                if (data.success && data.data && data.data.payment) {
                    closeCheckout();
                    const orderId = data.data.transaction.order_id;
                    const pendingPayment = {
                        order_id: orderId,
                        snap_token: data.data.payment.snap_token,
                        redirect_url: data.data.payment.redirect_url,
                        access_token: data.data.access_token,
                        expires_at: data.data.payment_expires_at,
                    };

                    saveActivePayment(pendingPayment);
                    openMidtransPopup(pendingPayment);
                } else {
                    throw new Error(data.message || 'Transaksi gagal');
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = '🛒 Bayar Sekarang';
            }
        });
    </script>
</body>
</html>
