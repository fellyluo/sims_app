# Checklist go-live situs pemasaran SIMS

## Konten & penjualan

- [x] Isi harga acuan di `config/marketing.php` → `prices` (tinjau ulang sebelum go-live)
- [x] `CONTACT_*` dan `LEAD_NOTIFICATION_EMAIL` (btivesolution@gmail.com / 085668330050)
- [ ] Set `SIMS_APP_URL` ke URL aplikasi produksi / demo
- [ ] Uji form kontak end-to-end (email notifikasi benar-benar masuk inbox)
- [ ] Review copy Asisten Guru vs klaim fitur Enterprise

## Teknis

- [ ] `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` benar
- [ ] `MAIL_MAILER` bukan `log` (SMTP / provider)
- [ ] `php artisan migrate --force`
- [ ] `npm run build` + asset Vite ter-deploy
- [ ] Favicon & OG image tampil saat share WhatsApp/Telegram
- [ ] HTTPS + redirect HTTP→HTTPS
- [ ] Opsional: `MARKETING_DOMAIN` jika domain khusus

## Setelah live

- [ ] Kirim 1 lead uji dari perangkat lain
- [ ] Pastikan tombol "Masuk" mengarah ke login aplikasi yang benar
- [ ] Catat lead di proses penjualan (CRM / spreadsheet) sampai admin UI tersedia
