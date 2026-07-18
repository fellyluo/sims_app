> Folder ini adalah situs pemasaran SIMS (Laravel terpisah) di dalam monorepo `sims_app`. Deploy dan `.env`-nya independen dari aplikasi utama.
# Situs Pemasaran SIMS

Landing page penjualan langganan SIMS: beranda, fitur, harga, kontak (lead form), dan privasi.

Aplikasi produksi tetap di repo utama SIMS. Repo ini hanya situs pemasaran.

## Stack

- Laravel 12 + Blade + Tailwind CSS v4 + Alpine.js
- SQLite default (cukup untuk lead lokal); MySQL/Postgres untuk produksi
- Form lead dengan throttle, honeypot, dan notifikasi email

## Setup lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

## Konfigurasi wajib sebelum go-live

Isi di `.env` (lihat juga `.env.example`):

| Variabel | Fungsi |
|---|---|
| `APP_URL` | URL publik situs pemasaran |
| `SIMS_APP_URL` | URL aplikasi SIMS (tombol Masuk) |
| `CONTACT_EMAIL` / `CONTACT_WHATSAPP` / `CONTACT_ADDRESS` | Kontak yang tampil di situs |
| `LEAD_NOTIFICATION_EMAIL` | Email penerima lead demo |
| `MAIL_MAILER=smtp` + host/user/pass | Agar notifikasi lead benar-benar terkirim (bukan `log`) |
| `MARKETING_DOMAIN` | Opsional: domain khusus untuk route marketing |
| `PPN_RATE` | Default 11 |

### Harga paket

Angka final di `config/marketing.php` → `prices` (rupiah penuh, integer). Selama `null`, UI menampilkan `Rp —` / contoh.

```php
'prices' => [
    'dasar' => [3 => 4500000, 6 => null, 12 => null],
    'pro' => [3 => null, 6 => null, 12 => null],
    'enterprise' => [3 => null, 6 => null, 12 => null],
],
```

## Deploy singkat

1. Deploy seperti Laravel biasa (PHP 8.2+, Composer, Node build, migrate).
2. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` benar.
3. Pastikan SMTP + `LEAD_NOTIFICATION_EMAIL` aktif; uji form kontak sekali.
4. Arahkan DNS ke host; jika pakai domain khusus, set `MARKETING_DOMAIN`.
5. Jalankan `php artisan config:cache` dan `php artisan route:cache` setelah env final.

## Aset produk

Screenshot di `public/images/product/` (sudah di-redact). Ganti dengan cuplikan terbaru bila UI berubah.

- OG image: `public/images/og/default.png`
- Favicon: `public/favicon.svg` / `favicon.png`

## Tes

```bash
php artisan test
```

## Halaman

| Path | Nama |
|---|---|
| `/` | Beranda |
| `/fitur` | Fitur |
| `/harga` | Harga |
| `/kontak` | Kontak + form lead |
| `/privasi` | Privasi singkat |
