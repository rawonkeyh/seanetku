# CHANGELOG

Dokumen ini mencatat perubahan penting yang berdampak pada sistem, operasional, struktur repository, dan dokumentasi.

## 2026-04-02

### Dokumentasi - Restrukturisasi Menyeluruh

- Menetapkan tiga dokumen inti sebagai sumber kebenaran:
  - README.md
  - init.md
  - CHANGELOG.md
- Melakukan penulisan ulang README agar mencakup:
  - setup lengkap
  - konfigurasi environment
  - endpoint API inti
  - alur bisnis end-to-end
  - checklist keamanan dan troubleshooting
- Melakukan penulisan ulang init agar mencakup:
  - state machine voucher dan transaksi
  - invariant keamanan
  - baseline deployment production
  - checklist operasional harian
- Merapikan isi changelog menjadi format kronologis yang konsisten.

### Repository Housekeeping

- Menghapus seluruh file shell script di root repository.
- Menghapus Makefile dari root repository.
- Menghilangkan referensi internal ke file-file yang sudah dihapus.

### Quality Review

- Menemukan area prioritas hardening:
  - validasi callback Midtrans (signature/source verification)
  - penguatan guard transisi state agar transaksi paid tidak downgrade
- Menemukan gap pada test coverage untuk callback payment dan state transition.

### Security Hardening Implemented

- Menambahkan verifikasi signature callback Midtrans sebelum callback diproses.
- Menambahkan rate limiting pada endpoint checkout dan callback.
- Menambahkan access token transaksi untuk endpoint detail/status transaksi publik.
- Menambahkan guard agar transaksi paid tidak bisa didowngrade ke failed/expired.
- Menambahkan guard state pada model voucher (reserve/release/sold).

## 2026-04-02 (riwayat implementasi sebelumnya)

### Fitur Utama yang Sudah Terimplementasi

- Admin authentication dan route protection.
- Flow transaksi berbasis reserved voucher.
- Integrasi Midtrans Snap untuk pembayaran.
- Sinkronisasi status transaksi via callback dan status check.
- Frontend customer dan panel admin lightweight.

### Peningkatan Struktur Aplikasi

- Penyesuaian struktur proyek berbasis Laravel 11.
- Perbaikan konfigurasi inti aplikasi dan deployment Docker.

### Stabilitas Sistem

- Perbaikan issue skema dan relasi transaksi-voucher.
- Perbaikan issue session/CSRF pada environment tertentu.

## Catatan

Prinsip pencatatan changelog pada proyek ini:

- Fokus pada perubahan berdampak.
- Hindari detail yang hanya bersifat sementara.
- Catat perubahan operasional yang memengaruhi tim pengembang dan deployment.
