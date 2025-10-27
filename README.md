## ISP Support — Backend

Proyek ini adalah backend aplikasi ISP Support berbasis Laravel 12 (PHP 8.2+), dengan Vite untuk aset frontend.

Dokumen ini menjelaskan cara men-setup environment, instalasi dependensi, serta menjalankan server pengembangan.

## Prasyarat

- PHP 8.2 atau lebih baru (pastikan ekstensi `pdo_mysql` aktif)
- Composer 2.x
- Node.js 18+ (disarankan LTS) dan npm
- Database: MySQL 8.x (atau MariaDB 10.5+)

## Mulai Cepat (Pengembangan)

Di root proyek:

1) Salin file environment dan buat kunci aplikasi

- Linux/Mac: `cp .env.example .env`
- Windows (PowerShell): `Copy-Item .env.example .env`
- Generate key: `php artisan key:generate`

2) Siapkan MySQL

<!-- - Buat database (dan user opsional) menggunakan CLI MySQL:
  - `mysql -u root -p -e "CREATE DATABASE isp_support CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"`
  - (Opsional) Buat user khusus dan beri hak akses:
    - `mysql -u root -p -e "CREATE USER 'isp_support'@'%' IDENTIFIED BY 'secret'; GRANT ALL PRIVILEGES ON isp_support.* TO 'isp_support'@'%'; FLUSH PRIVILEGES;"` -->
- Konfigurasi `.env` untuk MySQL:
  - `DB_CONNECTION=mysql`
  - `DB_HOST=127.0.0.1`
  - `DB_PORT=3306`
  - `DB_DATABASE=isp_support`
  - `DB_USERNAME=isp_support` (atau `root` jika sesuai)
  - `DB_PASSWORD=secret` (atau password Anda)

3) Instal dependensi

- PHP: `composer install`
- Node: `npm install`

4) Migrasi dan seed database

- Jalankan migrasi: `php artisan migrate`
- Jalankan Seed data: `php artisan db:seed`

5) Jalankan server pengembangan

- Cara ringkas (semua sekaligus: PHP server, queue listener, log, dan Vite):
  - `composer dev`
- Cara manual (terpisah):
  - Terminal 1: `php artisan serve`
  - Terminal 2: `npm run dev`
  - (Opsional) Queue listener: `php artisan queue:listen`

Aplikasi default dapat diakses pada `http://127.0.0.1:8000`.

## Detail Environment

- File env: `.env` (dibuat dari `.env.example`)
- Kunci aplikasi: `php artisan key:generate`
- Database: gunakan MySQL dengan kredensial yang dikonfigurasi pada bagian di atas.
- Session/Queue/Cache:
  - Proyek ini mengatur `SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`, dan `CACHE_STORE=database` secara default.
  - Tabel cache dan jobs sudah disediakan oleh migrasi.
  - Jika menggunakan session database, buat tabel session jika belum ada: `php artisan session:table && php artisan migrate`

## Perintah Berguna

- `composer setup` — instal semua dependensi, generate `.env` & app key, migrasi, lalu build aset.
- `composer dev` — jalankan server dev gabungan (PHP, queue, log, Vite) secara bersamaan.
- `php artisan migrate:fresh --seed` — reset database dan seed ulang data contoh.
- `npm run build` — build aset untuk produksi.

## Troubleshooting

- Port 8000 sudah terpakai: jalankan `php artisan serve --port=8001` atau hentikan proses yang memakai port 8000.
- Koneksi MySQL gagal (SQLSTATE[HY000] [2002] atau Connection refused):
  - Pastikan layanan MySQL berjalan dan gunakan `DB_HOST=127.0.0.1` (bukan `localhost`).
  - Cek `DB_PORT=3306` sesuai port MySQL Anda.
<!-- - Access denied untuk user MySQL:
  - Verifikasi `DB_USERNAME`/`DB_PASSWORD` dan pastikan user memiliki hak akses ke database.
  - Coba login manual: `mysql -h 127.0.0.1 -u USER -p` -->
- Error saat `npm run dev`/`vite`:
  - Pastikan Node.js versi 18+ dan lakukan reinstall dependensi jika perlu (`rm -rf node_modules && npm install`).

---

Untuk kebutuhan deployment/produksi (queue, cache, config, optimize, dll.), silakan beri tahu jika Anda ingin menambahkan panduan lanjutan.
