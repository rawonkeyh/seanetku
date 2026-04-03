# Inisialisasi Sistem Voucher Internet

Dokumen ini mendeskripsikan baseline teknis, aturan sistem, dan standar operasional implementasi saat ini.

## Tujuan Dokumen

- Menjadi acuan teknis internal tim.
- Menetapkan aturan state dan keamanan voucher/transaksi.
- Menyediakan checklist operasional untuk environment local dan production.

## Lingkup Implementasi

Komponen yang sudah ada:

- API customer untuk pembelian voucher.
- Integrasi Midtrans Snap.
- Callback payment dan update status transaksi.
- Panel admin untuk manajemen paket, voucher, transaksi.
- Logging event utama sistem.

Komponen yang belum ada:

- Integrasi MikroTik otomatis.
- Role-based access control admin tingkat lanjut.
- Notifikasi eksternal (email/WA) terintegrasi.

## Akses Layanan

Customer:

- Homepage: http://localhost:8000/
- Success page: http://localhost:8000/success

Admin:

- Login: http://localhost:8000/admin/login
- Dashboard: http://localhost:8000/admin
- Packages: http://localhost:8000/admin/packages
- Vouchers: http://localhost:8000/admin/vouchers
- Transactions: http://localhost:8000/admin/transactions

API:

- Base URL: http://localhost:8000/api/v1
- Health: http://localhost:8000/api/v1/health

## Definisi Entitas Inti

Package:

- Menyimpan definisi produk voucher (nama, tipe, value, harga, status aktif).

Voucher:

- Menyimpan kredensial akses internet (username/password) per package.
- Mempunyai state: available, reserved, sold.

Transaction:

- Mencatat pembelian user, referensi package dan voucher, nominal, status payment.
- Mempunyai state: pending, paid, failed, expired.

PaymentLog:

- Menyimpan jejak callback/payment event untuk audit dan debugging.

Admin:

- Akun pengelola backoffice dengan guard autentikasi terpisah.

## State Machine Sistem

State voucher:

- available -> reserved -> sold
- reserved -> available (release timeout atau payment gagal)

State transaksi:

- pending -> paid
- pending -> failed
- pending -> expired

Aturan state:

- Voucher sold tidak boleh kembali available.
- Transaksi paid tidak boleh downgrade menjadi failed/expired.
- Semua perubahan state harus tercatat di log.

## Invariant Keamanan

- Voucher credential hanya dikembalikan jika transaksi paid.
- Reserve voucher harus menggunakan lock database untuk menghindari race condition.
- Callback payment harus diproses idempotent.
- Validasi asal callback wajib dilakukan pada mode production.
- Session admin wajib diisolasi dengan guard admin.

## Alur Proses Pembelian

1. Customer memilih package dari daftar paket aktif.
2. Backend melakukan lock dan reserve satu voucher available.
3. Backend membuat transaksi status pending.
4. Backend meminta Snap token ke Midtrans.
5. Customer menyelesaikan pembayaran.
6. Sistem menerima callback/status check.
7. Sistem melakukan transisi state transaksi dan voucher.
8. Sistem menampilkan voucher jika status transaksi paid.

## Komponen Teknis yang Terlibat

Controllers:

- API package, transaction, callback payment.
- Admin auth, dashboard, package, voucher, transaction.

Services:

- VoucherService: reserve, release, mark sold.
- TransactionService: create transaction, proses success/failure/expired.
- MidtransService: create payment, notification handling, status check.

Middleware:

- Admin authentication middleware untuk route admin protected.

## Konfigurasi Kritis

Variable environment minimum:

- MIDTRANS_SERVER_KEY
- MIDTRANS_CLIENT_KEY
- MIDTRANS_IS_PRODUCTION
- VOUCHER_RESERVATION_TIMEOUT
- VOUCHER_LOW_STOCK_THRESHOLD
- DB_* sesuai deployment

Parameter operasional yang direkomendasikan:

- VOUCHER_RESERVATION_TIMEOUT=15
- MIDTRANS_IS_PRODUCTION=false untuk sandbox
- APP_DEBUG=false pada production

## Operasional Harian

Checklist startup:

1. Pastikan container up.
2. Pastikan health endpoint status ok.
3. Pastikan scheduler berjalan.
4. Pastikan log tidak menunjukkan error callback berulang.

Checklist harian admin:

1. Cek stok low stock per package.
2. Cek transaksi pending yang belum berubah lama.
3. Cek error log payment.
4. Cek konsistensi status voucher terhadap transaksi.

## Monitoring dan Logging

Hal yang harus dipantau:

- Jumlah voucher available per package.
- Rasio paid vs failed/expired.
- Error Midtrans callback.
- Kejadian release timeout.

Sumber log utama:

- storage/logs/laravel.log

## Baseline Deployment Production

Minimum hardening sebelum go-live:

1. Ganti password admin default.
2. Pakai HTTPS dan domain valid.
3. Nonaktifkan APP_DEBUG.
4. Validasi callback Midtrans (signature/source verification).
5. Tambah guard agar transaksi paid tidak bisa downgrade.
6. Audit permission akses admin.

## Kredensial Admin Default

- Username: admin
- Password: admin123

Kredensial ini hanya untuk bootstrap awal dan wajib diganti segera.

## Rencana Pengembangan Lanjutan

- Integrasi MikroTik berbasis queue worker.
- Notifikasi customer otomatis.
- Export report transaksi.
- Analitik performa penjualan.
- Role dan permission admin multi-level.
