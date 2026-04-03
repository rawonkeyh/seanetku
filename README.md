# Sistem Pembelian Voucher Internet

Sistem pembelian voucher internet berbasis Laravel dengan integrasi Midtrans Snap, reserved voucher, panel admin, dan arsitektur siap dikembangkan.

## Daftar Isi

1. Gambaran Sistem
2. Fitur Inti
3. Teknologi yang Digunakan
4. Prasyarat Lingkungan
5. Instalasi Lengkap
6. Konfigurasi Environment
7. Menjalankan Aplikasi
8. Endpoint API
9. Alur Bisnis End-to-End
10. Struktur Proyek
11. Operasional dan Monitoring
12. Keamanan
13. Troubleshooting Umum
14. Dokumen Internal

## Gambaran Sistem

Sistem ini mengelola penjualan voucher internet dengan prinsip:

- Satu transaksi hanya boleh memiliki satu voucher.
- Voucher tidak boleh bocor ke pengguna sebelum transaksi paid.
- Voucher reserved harus dirilis otomatis bila pembayaran tidak selesai.
- Sistem harus aman terhadap race condition saat reservasi voucher.

## Fitur Inti

- Manajemen paket voucher (tipe time/quota).
- Reserved voucher dengan database locking.
- Integrasi Midtrans Snap untuk pembayaran.
- Callback payment dan sinkronisasi status.
- Auto-release voucher timeout.
- Admin panel untuk paket, voucher, transaksi, dan autentikasi admin.
- Audit log untuk event penting transaksi.

## Teknologi yang Digunakan

- Backend: Laravel 11
- Runtime: PHP 8.2+
- Database: MySQL 8 atau MariaDB 10.4+
- Cache/queue: Redis
- Payment gateway: Midtrans Snap
- Containerization: Docker Compose
- Web server: Nginx

## Prasyarat Lingkungan

Pastikan komponen berikut tersedia:

- Docker dan Docker Compose
- Akses internet untuk install dependency Composer
- Kredensial Midtrans (server key dan client key)
- PHP 8.2+ jika ingin menjalankan command Laravel langsung di host

## Instalasi Lengkap

1. Salin file environment:

```bash
cp .env.example .env
```

2. Sesuaikan variabel penting di .env.

3. Jalankan service container:

```bash
docker-compose up -d
```

4. Install dependency aplikasi:

```bash
docker-compose exec app composer install
```

5. Generate key Laravel:

```bash
docker-compose exec app php artisan key:generate
```

6. Migrasi dan seed database:

```bash
docker-compose exec app php artisan migrate --seed
```

7. Verifikasi health endpoint:

```bash
curl http://localhost:8000/api/v1/health
```

## Konfigurasi Environment

Contoh baseline konfigurasi:

```env
APP_NAME="Voucher System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=host.docker.internal
DB_PORT=3306
DB_DATABASE=voucher_db
DB_USERNAME=root
DB_PASSWORD=admin123

REDIS_HOST=redis
REDIS_PORT=6379

MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_IS_PRODUCTION=false

VOUCHER_RESERVATION_TIMEOUT=15
VOUCHER_LOW_STOCK_THRESHOLD=10
```

Catatan:

- Gunakan MIDTRANS_IS_PRODUCTION=true hanya untuk produksi.
- Ganti kredensial database dan admin default sebelum go-live.

## Menjalankan Aplikasi

URL penting:

- Customer homepage: http://localhost:8000/
- Customer success page: http://localhost:8000/success
- Admin login: http://localhost:8000/admin/login
- Admin dashboard: http://localhost:8000/admin
- API base: http://localhost:8000/api/v1

Command operasional harian:

```bash
# Start service
docker-compose up -d

# Stop service
docker-compose down

# Lihat log app
docker-compose logs -f app

# Jalankan command artisan
docker-compose exec app php artisan <command>
```

## Endpoint API

Public/customer endpoints:

- GET /api/v1/packages
- GET /api/v1/packages/{id}
- POST /api/v1/transactions
- GET /api/v1/transactions/{id}
- GET /api/v1/transactions/{id}/status
- GET /api/v1/transactions/order/{orderId}/status

Catatan keamanan endpoint transaksi:

- Response `POST /api/v1/transactions` sekarang mengembalikan `access_token`.
- Endpoint detail/status transaksi mewajibkan token ini via header `X-Transaction-Token`
  atau query parameter `token`.
- Tanpa token valid, API mengembalikan `403 Invalid transaction access token`.

Catatan UX pembayaran pending:

- Saat popup Midtrans ditutup sebelum bayar selesai, data transaksi pending disimpan di browser yang sama.
- User dapat menekan tombol "Lanjutkan Pembayaran" untuk membuka kembali Midtrans selama belum melewati `payment_expires_at`.
- Setelah waktu habis, sesi pending dihapus otomatis dan user harus checkout ulang.

Payment integration endpoint:

- POST /api/v1/payment/callback

Operational endpoint:

- GET /api/v1/health

## Alur Bisnis End-to-End

1. User memilih paket voucher.
2. Sistem cek stok available untuk paket tersebut.
3. Sistem melakukan reserve voucher dengan lock.
4. Sistem membuat transaksi status pending.
5. Sistem membuat Snap token Midtrans.
6. User melakukan pembayaran.
7. Sistem menerima callback atau melakukan status sync.
8. Jika paid:
  - transaksi menjadi paid
  - voucher menjadi sold
  - kredensial voucher ditampilkan
9. Jika failed/expired:
  - transaksi menjadi failed/expired
  - voucher dirilis ke available

## Struktur Proyek

```text
app/
  Console/Commands/
  Http/Controllers/
  Http/Middleware/
  Http/Requests/
  Http/Resources/
  Models/
  Services/
config/
database/
  migrations/
  seeders/
resources/views/
routes/
docker/
```

## Operasional dan Monitoring

Yang perlu dipantau:

- Ketersediaan voucher per paket.
- Jumlah transaksi pending yang menumpuk.
- Error callback payment.
- Event release timeout voucher.

Lokasi log aplikasi:

- storage/logs/laravel.log

Contoh command monitoring singkat:

```bash
docker-compose exec app tail -f storage/logs/laravel.log
```

## Keamanan

Prinsip keamanan saat ini:

- Voucher tidak boleh tampil saat pending/reserved.
- Reserved voucher memakai lock database.
- Session admin menggunakan guard terpisah.
- Aktivitas penting dicatat dalam log.

Checklist hardening produksi:

- Ganti password admin default.
- Pastikan callback Midtrans tervalidasi signature.
- Pastikan state transition transaksi tidak bisa downgrade dari paid.
- Aktifkan HTTPS dan atur APP_URL produksi.

## Troubleshooting Umum

1. Service tidak bisa naik:
  - cek docker-compose ps
  - cek docker-compose logs

2. Tidak bisa konek database:
  - verifikasi DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
  - cek konektivitas host database dari container

3. Callback Midtrans tidak update status:
  - cek endpoint callback terdaftar di dashboard Midtrans
  - cek log aplikasi untuk payload callback

4. Test tidak bisa dijalankan di host:
  - pastikan PHP host minimal 8.2

## Dokumen Internal

- README.md: panduan setup dan operasional
- init.md: spesifikasi teknis dan baseline arsitektur
- CHANGELOG.md: riwayat perubahan penting
