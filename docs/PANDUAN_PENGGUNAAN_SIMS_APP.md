# Panduan Penggunaan SIMS App

Dokumen ini disusun dari review source aplikasi `sims_app`, terutama `routes/web.php`, `routes/sarpras.php`, menu sidebar `resources/views/layouts/app.blade.php`, controller, dan view utama. Total route yang terdeteksi saat review: 512 route.

## 1. Ringkasan Role dan Akses

Role utama aplikasi:

- `superadmin`: akses penuh, termasuk gateway AI teknis.
- `admin`: operator sekolah, akses penuh ke master data dan sebagian besar modul.
- `kepala`: pimpinan sekolah, fokus rekap, validasi, analisis, dan monitoring.
- `kurikulum`: akademik, jadwal, nilai, agenda, analisis.
- `kesiswaan`: absensi, disiplin, siswa, pengumuman, monitoring.
- `sapras`: sarana dan prasarana.
- `bendahara`: keuangan SPP.
- `guru`: ruang kelas, agenda, nilai, absensi QR, perangkat ajar.
- `walikelas`: fitur guru plus pengelolaan kelas wali.
- `siswa`: ruang kelas, nilai sendiri, absensi QR, kartu pelajar, tagihan.
- `orangtua`: tagihan anak, nilai anak, chatbot, pengumuman sesuai akses.

Catatan akses:

- Admin dan superadmin selalu bisa mengakses permission aplikasi.
- Role lain bisa diberi tambahan akses melalui menu `Sistem > Hak Akses (RBAC)`.
- Forum memakai matriks akses forum tersendiri.
- Modul Sarpras memakai Gate berbasis `users.access`, bukan RBAC umum.
- Siswa dan guru wajib memiliki data wajah sebelum masuk ke banyak halaman aplikasi, kecuali orang tua.

## 2. Alur Awal Penggunaan

1. Buka aplikasi, halaman `/` otomatis diarahkan ke `/login`.
2. Login memakai username dan password.
3. Jika akun diwajibkan mengganti password, ikuti halaman `Keamanan Akun`.
4. Jika tersedia, pengguna dapat login memakai PIN 6 digit atau WebAuthn/Fingerprint/Face ID.
5. Untuk siswa/guru/staf yang terkena gate wajah, buka `Wajah Saya`, ambil beberapa sampel wajah, lalu simpan.
6. Masuk ke `Dashboard`.
7. Atur profil dan tampilan lewat dropdown profil kanan atas.

## 3. Login, Keamanan Akun, dan Profil

### Login

Digunakan oleh semua user.

Cara pakai:

1. Buka `/login`.
2. Masukkan username dan password.
3. Klik login.
4. Jika memakai PIN, pilih login PIN dan masukkan PIN 6 digit.
5. Jika lupa password, gunakan form permintaan reset password.

Catatan:

- Login password dan PIN memakai throttle untuk mengurangi brute force.
- Reset password diminta dari halaman login, lalu diproses admin/wali kelas sesuai fitur reset akun.

### Logout

Cara pakai:

1. Klik avatar/profil di kanan atas.
2. Pilih `Keluar`.

### Ganti Username dan Password

Menu: dropdown profil > `Profil` atau halaman `Keamanan Akun`.

Cara pakai:

1. Buka `Ganti Password`.
2. Ubah username jika diminta.
3. Masukkan password lama dan password baru.
4. Klik `Simpan Password`.

### Set PIN Login

Menu: halaman `Ganti PIN`.

Cara pakai:

1. Buka `Set PIN Login`.
2. Isi PIN 6 digit.
3. Simpan.
4. Setelah aktif, user bisa login cepat dengan PIN.

### WebAuthn/Fingerprint/Face ID

Fitur: login/register WebAuthn.

Cara pakai:

1. Login dulu dengan akun normal.
2. Daftarkan perangkat biometrik/passkey jika UI tersedia.
3. Saat login berikutnya, gunakan autentikasi perangkat.

### Profil

Menu: dropdown profil > `Profil`.

Cara pakai:

1. Buka `Profil`.
2. Klik edit profil.
3. Sesuaikan data yang tersedia.
4. Simpan.

### Preferensi Tampilan

Menu: dropdown profil > `Tampilan`.

Cara pakai:

1. Pilih warna utama, warna sekunder, aksen, sidebar, ukuran font, motif, dan mode UI.
2. Simpan preferensi.
3. Gunakan reset jika ingin kembali ke default.
4. Tombol gaya cepat dapat mengganti gaya `Soft` dan `Analyzer`.

## 4. Dashboard dan Notifikasi

### Dashboard

Menu: `Dashboard`.

Fungsi:

- Menampilkan ringkasan sesuai role.
- Menampilkan widget siswa/guru/kesiswaan/kurikulum/sarpras/keuangan sesuai akses.
- Menampilkan ticker statistik real-time.
- Mendukung tata letak widget yang bisa disimpan.

Cara pakai:

1. Buka `Dashboard`.
2. Pantau kartu statistik dan daftar aktivitas.
3. Jika widget bisa diatur, drag/drop tata letak.
4. Simpan tata letak dashboard.

### Notifikasi

Fungsi:

- Menampilkan notifikasi pengumuman, forum, ruang kelas, dan sarpras.
- Mendukung bunyi notifikasi.
- Mendukung FCM token dari Android WebView.

Cara pakai:

1. Klik ikon notifikasi di header.
2. Klik notifikasi untuk membuka target.
3. Gunakan `Tandai semua dibaca` jika ingin membersihkan badge.
4. Aktif/nonaktifkan suara notifikasi bila tersedia.

## 5. Pengumuman

Menu: `Pengumuman`.

Akses:

- Semua user dapat melihat pengumuman sesuai target role.
- User dengan permission `manage_pengumuman` dapat membuat, edit, dan hapus.

Cara melihat pengumuman:

1. Buka `Pengumuman`.
2. Pilih pengumuman dari daftar.
3. Baca detail pengumuman.

Cara membuat pengumuman:

1. Buka `Pengumuman`.
2. Klik `Buat`.
3. Isi judul, isi pengumuman, dan target role.
4. Simpan.
5. Sistem mengirim notifikasi ke user sasaran.

Cara mengubah/menghapus:

1. Buka detail atau daftar pengumuman.
2. Klik edit untuk mengubah.
3. Klik hapus jika pengumuman tidak dipakai lagi.

## 6. Asisten Sekolah dan Chat

### Chatbot Asisten Sekolah untuk User

Akses:

- Semua role non-admin dapat memakai widget penanya.
- Admin/superadmin memakai Inbox Admin.

Cara pakai:

1. Klik floating chat atau buka `/chatbot`.
2. Ketik pertanyaan.
3. Kirim gambar atau file jika diperlukan.
4. Klik `Hubungkan ke Admin` jika butuh bantuan manusia.
5. Klik `Kembali ke Bot` jika ingin percakapan kembali dijawab bot.

Fitur:

- FAQ cepat.
- Handoff ke admin.
- Upload gambar dan file.
- Polling pesan baru.
- Badge unread.

### Inbox Admin Chatbot

Menu: `Chat / Inbox`.

Akses: admin/superadmin.

Cara pakai:

1. Buka `Chat / Inbox`.
2. Lihat percakapan masuk dan antrean.
3. Pilih percakapan.
4. Klik assign jika ingin mengambil percakapan.
5. Balas dengan teks, gambar, atau file.
6. Gunakan `Kembalikan ke Bot` jika user tidak perlu admin lagi.
7. Klik `Tutup` untuk menyelesaikan percakapan.
8. Atur avatar, pesan notifikasi, dan quick questions lewat pengaturan inbox.

### AsistenAI Global

Fitur:

- Chat AI login user.
- Riwayat percakapan per user.
- Hapus percakapan.
- Bisa memakai grounding Google Search sesuai intent.

Cara pakai:

1. Buka widget/fitur AsistenAI global jika tampil.
2. Ketik pertanyaan.
3. Buka riwayat jika ingin melanjutkan percakapan lama.
4. Hapus percakapan yang tidak diperlukan.

### Asisten AI Guru

Menu: `Akademik > Asisten AI`.

Akses: guru dan wali kelas.

Cara pakai:

1. Buka `Asisten AI`.
2. Pilih fitur: buat soal, rangkum materi, atau buat feedback.
3. Isi konteks materi/kelas/kebutuhan.
4. Klik generate.
5. Review hasil sebelum digunakan ke siswa.

### Analisis AI

Menu: `Analisis AI > Narasi Data AI`.

Akses: admin, kepala, kurikulum, kesiswaan.

Cara pakai:

1. Buka `Narasi Data AI`.
2. Pilih jenis analisis: nilai, absensi, atau keuangan.
3. Isi filter/periode jika tersedia.
4. Jalankan analisis.
5. Gunakan narasi sebagai bahan evaluasi, bukan pengganti keputusan final.

### Dokumen AI (RAG)

Menu: `Analisis AI > Dokumen AI`.

Akses: admin, kepala, kurikulum, kesiswaan.

Cara pakai:

1. Buka `Dokumen AI`.
2. Upload dokumen.
3. Tunggu dokumen diproses.
4. Ajukan pertanyaan berdasarkan isi dokumen.
5. Baca jawaban dan sitasi.
6. Hapus dokumen yang tidak diperlukan.

## 7. Data Master

Menu: `Data Master`.

Akses: admin atau user dengan `manage_users`.

### Data Guru

Fungsi:

- Tambah, edit, lihat, hapus guru.
- Reset akun guru.
- Import guru dari Excel.
- Unduh template import.
- Unduh kredensial hasil import.
- Atur penugasan mengajar guru per mapel/kelas.
- Simpan dan hapus data wajah guru.

Cara tambah guru:

1. Buka `Data Master > Data Guru`.
2. Klik tambah guru.
3. Isi identitas guru.
4. Simpan.

Cara import guru:

1. Buka `Data Guru > Import`.
2. Unduh template.
3. Isi file sesuai format.
4. Upload file.
5. Setelah import, unduh kredensial guru baru.

Cara atur mengajar:

1. Buka detail atau aksi `Pelajaran` pada guru.
2. Pilih kelas dan mata pelajaran.
3. Simpan penugasan.
4. Hapus penugasan jika tidak berlaku.

Cara reset akun guru:

1. Buka daftar/detail guru.
2. Klik reset.
3. Salin kredensial baru yang muncul.

### Data Siswa

Fungsi:

- Tambah, edit, lihat, hapus siswa.
- Membuat akun siswa dan orang tua.
- Reset akun siswa dan orang tua.
- Import siswa dari Excel.
- Unduh template import.
- Unduh kredensial hasil import.
- Simpan dan hapus data wajah siswa.

Cara tambah siswa:

1. Buka `Data Master > Data Siswa`.
2. Klik tambah siswa.
3. Isi identitas, kelas, dan data orang tua.
4. Simpan.

Cara import siswa:

1. Buka `Data Siswa > Import`.
2. Unduh template.
3. Isi file Excel sesuai panduan.
4. Upload dan import.
5. Unduh kredensial hasil import.

Cara reset akun:

1. Buka data siswa.
2. Klik reset akun siswa atau reset akun orang tua.
3. Salin kredensial baru.

### Data Kelas

Fungsi:

- Tambah, edit, hapus kelas.
- Atur wali kelas.
- Set rombel siswa.
- Melihat histori rombel.
- Hapus histori rombel.

Cara pakai:

1. Buka `Data Master > Data Kelas`.
2. Tambahkan kelas jika belum ada.
3. Pilih aksi wali kelas untuk menetapkan guru wali.
4. Gunakan `Set Kelas` untuk memasukkan siswa ke rombel.
5. Cek `Histori Rombel` untuk riwayat perpindahan kelas.

### Mata Pelajaran

Fungsi:

- Tambah mata pelajaran.
- Edit mata pelajaran.
- Hapus mata pelajaran.
- Sorting urutan mapel.

Cara pakai:

1. Buka `Data Master > Mata Pelajaran`.
2. Tambahkan mapel dari form/modal.
3. Edit nama/atribut mapel bila perlu.
4. Drag/sort jika UI sorting tersedia.
5. Simpan urutan.

### Kartu Pelajar Admin

Menu: `Data Master > Kartu Pelajar`.

Fungsi:

- Kelola kartu pelajar digital per siswa.
- Upload file kartu kustom.
- Lihat kartu siswa.
- Hapus kartu.
- Cetak massal kartu per tingkat.

Cara pakai:

1. Buka `Kartu Pelajar`.
2. Filter siswa/tingkat jika tersedia.
3. Upload kartu untuk siswa tertentu atau gunakan generate default.
4. Klik lihat untuk preview.
5. Klik cetak untuk cetak massal per tingkat.

## 8. Absensi dan Presensi

Menu: `Absensi & Presensi`.

### Kalender Absensi

Fungsi:

- Menentukan hari efektif/non-efektif.
- Toggle tanggal satu per satu.
- Bulk set banyak tanggal.
- Atur mode kalender.

Cara pakai:

1. Buka `Kalender Absensi`.
2. Pilih tanggal.
3. Toggle status hari.
4. Gunakan bulk untuk rentang tanggal.
5. Simpan mode kalender jika tersedia.

### Absensi Siswa Manual

Akses:

- Admin/pengelola absensi.
- Wali kelas untuk kelasnya.

Cara pakai:

1. Buka `Absensi Siswa`.
2. Pilih kelas dan tanggal.
3. Tandai status tiap siswa.
4. Klik `Simpan Absensi`.
5. Buka `Rekap Absensi` untuk rekap per rentang tanggal.

### Rekap Absensi Siswa

Cara pakai:

1. Buka `Rekap Absensi`.
2. Pilih kelas dan rentang tanggal.
3. Lihat rekap kehadiran.
4. Gunakan hasilnya untuk evaluasi wali kelas/kesiswaan.

### Registrasi dan Validasi Wajah

Fungsi:

- Registrasi wajah siswa/guru.
- Galeri wajah.
- Deteksi wajah ganda.

Cara pakai admin:

1. Buka `Validasi Wajah`.
2. Pilih siswa/guru yang belum punya wajah.
3. Ambil beberapa sampel wajah dari sudut berbeda.
4. Simpan.
5. Buka `Wajah Galeri` untuk review data wajah.
6. Buka `Wajah Ganda` untuk memeriksa potensi wajah duplikat.

Cara pakai user:

1. Buka `Wajah Saya`.
2. Izinkan kamera.
3. Ambil sampel wajah.
4. Simpan.

### Absensi Wajah Kiosk

Fungsi:

- Scan wajah lewat komputer/piket.
- Link kiosk publik memakai token rahasia.

Cara pakai:

1. Admin buka `Pengaturan > Absensi`.
2. Generate token kiosk.
3. Pasang link `/kiosk-absensi/{token}` di komputer piket.
4. Guru/siswa melakukan scan wajah di perangkat tersebut.

### QR Absensi

Menu admin: `QR Absensi`.

Menu user: `Absen QR`.

Cara pakai admin:

1. Buka `QR Absensi`.
2. Tampilkan QR harian di layar/print.
3. Pastikan lokasi QR sudah diatur di pengaturan.

Cara pakai siswa/guru:

1. Buka `Absen QR`.
2. Scan QR harian.
3. Izinkan lokasi.
4. Kirim absen.

### Presensi Guru

Fungsi:

- Presensi guru manual.
- Scan wajah guru.
- Rekap presensi guru.

Cara pakai:

1. Buka `Presensi Guru`.
2. Input presensi manual jika diperlukan.
3. Gunakan `Scan` untuk presensi wajah.
4. Buka `Rekap` untuk laporan rentang tanggal.

## 9. Akademik

### Ruang Kelas

Menu: `Akademik > Ruang Kelas`.

Akses:

- Admin/kepala/kurikulum/guru/siswa sesuai policy.
- Orang tua tidak masuk menu Ruang Kelas.

Fungsi:

- Navigasi kelas dan mapel.
- Auto-provision ruang kelas per kelas/mapel.
- Materi.
- Tugas.
- Komentar.
- Pengumpulan tugas.
- Penilaian submission.
- Transfer nilai tugas ke modul nilai.
- Lock materi/tugas dengan token.
- Pemantauan lock event.
- Link meet dan tutup meet.

Cara guru membuat materi:

1. Buka `Ruang Kelas`.
2. Pilih kelas.
3. Pilih mata pelajaran.
4. Klik buat materi.
5. Isi judul, konten, file/link, dan opsi lock jika diperlukan.
6. Simpan.

Cara guru membuat tugas:

1. Buka ruang mapel.
2. Klik buat tugas.
3. Isi instruksi, deadline, file pendukung, dan opsi lock.
4. Simpan.

Cara siswa membaca materi:

1. Buka `Ruang Kelas`.
2. Pilih kelas/mapel.
3. Klik materi.
4. Jika terkunci, masukkan token dari guru.
5. Unduh/preview file bila tersedia.
6. Tulis komentar jika dibuka.

Cara siswa mengumpulkan tugas:

1. Buka tugas.
2. Baca instruksi.
3. Upload file jawaban atau isi jawaban sesuai form.
4. Klik kumpulkan.

Cara guru menilai tugas:

1. Buka detail tugas.
2. Pilih menu penilaian/submission.
3. Beri nilai dan feedback.
4. Kembalikan submission jika perlu revisi.
5. Gunakan transfer nilai untuk memasukkan nilai ke buku nilai.

### Jadwal Pelajaran

Menu admin/kurikulum: `Akademik > Jadwal Pelajaran`.

Menu guru/staf: `Jadwal Mengajar`.

Fungsi:

- Editor grid jadwal per hari.
- Jadwal kelas.
- Jadwal guru.
- Master jam pelajaran.
- Generate jadwal.
- Copy jam.
- Hapus jam.

Cara admin/kurikulum:

1. Buka `Jadwal Pelajaran`.
2. Atur master jam di menu JP/jam.
3. Pilih kelas/hari.
4. Isi sel jadwal dengan mapel dan guru.
5. Simpan sel.
6. Gunakan generate jika ingin jadwal otomatis.
7. Cek tampilan jadwal per kelas.

Cara guru:

1. Buka `Jadwal Mengajar`.
2. Lihat jadwal mengajar berdasarkan penugasan.

### Penilaian / Buku Guru

Menu: `Akademik > Penilaian` atau `Buku Guru`.

Fungsi:

- Daftar penugasan mengajar.
- KKTP per penugasan.
- Materi dan tujuan pembelajaran.
- Nilai formatif.
- Nilai sumatif.
- Nilai penjabaran.
- Nilai PTS.
- Nilai PAS.
- Nilai rapor.
- Deskripsi rapor.
- Konfirmasi/batal konfirmasi rapor.

Cara guru mengisi nilai:

1. Buka `Buku Guru` atau `Penilaian`.
2. Pilih kelas/mapel yang diajar.
3. Atur KKTP jika belum.
4. Buat materi dan tujuan pembelajaran.
5. Masuk ke tab formatif/sumatif/penjabaran/PTS/PAS.
6. Isi nilai pada sel siswa.
7. Cek nilai rapor.
8. Isi deskripsi rapor.
9. Konfirmasi rapor jika final.

Cara admin/kurikulum:

1. Buka `Penilaian`.
2. Pilih guru/kelas/mapel.
3. Pantau progres nilai.
4. Buka rekap untuk melihat lintas kelas/mapel.

### Nilai Saya

Akses: siswa dan orang tua.

Cara pakai:

1. Buka `Akademik > Nilai Saya`.
2. Pilih semester jika tersedia.
3. Lihat nilai per mapel.
4. Orang tua melihat nilai anak sesuai relasi akun.

### Rekap Nilai

Menu: `Akademik > Rekap Nilai`.

Akses:

- Admin/role dengan `manage_rapor`.
- Wali kelas untuk kelasnya.

Cara pakai:

1. Buka `Rekap Nilai`.
2. Pilih kelas/semester/filter.
3. Lihat rekap nilai siswa.
4. Gunakan sebagai dasar cetak rapor.

### Cetak Rapor

Menu: `Akademik > Cetak Rapor`.

Cara pakai:

1. Buka `Cetak Rapor`.
2. Pilih kelas dan siswa atau parameter cetak.
3. Pastikan nilai sudah dikonfirmasi.
4. Klik cetak.
5. Periksa tampilan PDF/print.

### Ekstrakurikuler

Menu: `Akademik > Ekstrakurikuler`.

Fungsi:

- Kelola ekskul.
- Nilai/deskripsi ekskul.

Cara pakai:

1. Buka `Ekstrakurikuler`.
2. Admin menambah/mengubah/menghapus ekskul.
3. Guru/pembina membuka nilai ekskul.
4. Isi nilai/deskripsi per siswa.
5. Simpan.

### Perangkat Ajar

Menu admin: `Akademik > Perangkat Ajar`.

Menu guru: `Akademik > Perangkat Ajar Saya`.

Fungsi:

- Master jenis perangkat ajar.
- Guru upload file perangkat ajar.
- Preview/unduh file.
- Hapus file.
- Unduh ZIP per guru.

Cara admin:

1. Buka `Perangkat Ajar`.
2. Tambah jenis dokumen, misalnya Modul Ajar, Prota, Prosem, RPP.
3. Pilih guru.
4. Pantau file yang sudah/belum diupload.
5. Unduh ZIP jika butuh arsip.

Cara guru:

1. Buka `Perangkat Ajar Saya`.
2. Pilih jenis dokumen.
3. Upload file.
4. Preview atau unduh untuk cek.
5. Hapus dan upload ulang jika salah.

## 10. Agenda Guru

Menu: `Agenda`.

### Agenda Guru

Akses: guru.

Fungsi:

- Mengisi agenda mengajar.
- Memilih slot jadwal berdasarkan tanggal.
- Memuat daftar siswa per jadwal.
- Mencatat ketidakhadiran siswa.

Cara pakai:

1. Buka `Agenda > Agenda Guru`.
2. Klik `Tambah Agenda`.
3. Pilih tanggal.
4. Pilih slot/jadwal mengajar.
5. Isi materi, kegiatan, catatan, dan absensi jika ada.
6. Simpan agenda.
7. Edit atau hapus agenda jika belum final.

### Rekap Agenda

Akses: admin/kepala/kurikulum/role `manage_agenda`.

Cara pakai:

1. Buka `Agenda > Rekap Agenda`.
2. Filter guru/tanggal/kelas jika tersedia.
3. Review agenda.
4. Beri validasi dan catatan.
5. Simpan validasi.

### Buku Batas

Fungsi:

- Rekap batas pelajaran per kelas/rentang tanggal.
- Export Excel.

Cara pakai:

1. Buka `Agenda > Buku Batas`.
2. Pilih kelas dan rentang tanggal.
3. Klik tampilkan.
4. Klik `Unduh Excel` jika perlu arsip.

## 11. Kedisiplinan: Poin atau P3

Aplikasi memiliki dua sistem disiplin, dipilih dari `Pengaturan > Sistem > Jenis Aturan`.

### Sistem Poin

Menu: `Poin & Aturan`.

Fungsi:

- Master aturan poin.
- Import/export aturan.
- Ledger poin siswa.
- Pengajuan poin oleh guru/wali kelas/sekretaris.
- Approval pengajuan.
- Dashboard kedisiplinan.
- Poin Saya untuk siswa/orang tua.

Cara admin/kesiswaan mengelola aturan:

1. Buka `Master Aturan`.
2. Tambah aturan poin.
3. Isi jenis, kategori, nilai poin, dan keterangan.
4. Simpan.
5. Gunakan import/export jika banyak aturan.

Cara input poin langsung:

1. Buka `Poin Siswa`.
2. Pilih siswa.
3. Klik tambah entri.
4. Pilih aturan.
5. Simpan.

Cara guru/sekretaris mengajukan poin:

1. Buka `Ajukan Poin`.
2. Pilih siswa.
3. Pilih aturan dan isi catatan.
4. Kirim pengajuan.

Cara admin memproses pengajuan:

1. Buka `Pengajuan Poin`.
2. Pilih pengajuan.
3. Setujui, tolak, atau proses bulk.
4. Cek riwayat pengajuan.

Cara siswa/orang tua:

1. Buka `Poin Saya`.
2. Lihat saldo/riwayat poin.

### Sistem P3

Menu: `P3 Kedisiplinan`.

P3 mencakup Pelanggaran, Prestasi, dan Partisipasi.

Fungsi:

- Master kategori P3.
- Data P3 siswa.
- Pengajuan P3.
- Approval/disapproval pengajuan.
- Cetak laporan P3 per siswa.
- P3 Saya untuk siswa/orang tua.

Cara admin/kesiswaan:

1. Buka `Master Kategori`.
2. Tambah kategori P3.
3. Buka `P3 Siswa`.
4. Pilih siswa.
5. Tambah entri P3 atau edit entri.
6. Cetak laporan jika diperlukan.

Cara guru/sekretaris:

1. Buka `Ajukan P3`.
2. Pilih siswa.
3. Pilih kategori.
4. Isi catatan/bukti.
5. Kirim pengajuan.

Cara approval:

1. Buka `Pengajuan P3`.
2. Review detail.
3. Klik approve atau disapprove.
4. Cek riwayat.

Cara siswa/orang tua:

1. Buka `P3 Saya`.
2. Lihat riwayat prestasi, partisipasi, dan pelanggaran.

## 12. Wali Kelas

Menu: `Wali Kelas`.

Akses: guru yang menjadi wali kelas.

### Data Siswa Kelas

Cara pakai:

1. Buka `Wali Kelas > Data Siswa Kelas`.
2. Lihat daftar siswa di kelas.
3. Klik detail siswa untuk melihat profil.
4. Reset akun siswa/orang tua jika dibutuhkan.

### Set Sekretaris

Fungsi:

- Menentukan siswa yang boleh mengajukan Poin/P3 sebagai sekretaris kelas.

Cara pakai:

1. Buka `Set Sekretaris`.
2. Pilih siswa yang menjadi sekretaris.
3. Simpan.

### Absensi Kelas Saya

Cara pakai:

1. Buka `Absensi Kelas Saya`.
2. Pilih tanggal.
3. Isi status kehadiran siswa.
4. Simpan.

### Rekap Absensi Kelas

Cara pakai:

1. Buka `Rekap Absensi Kelas`.
2. Pilih rentang tanggal.
3. Review rekap.

### Poin/P3 Siswa Kelas

Cara pakai:

1. Buka menu Poin/P3 siswa kelas.
2. Pilih siswa.
3. Lihat riwayat kedisiplinan.

### Nilai Kelas Saya

Catatan: menu ini tampil jika pengaturan `walikelas_lihat_nilai` aktif.

Cara pakai:

1. Buka `Nilai Kelas Saya`.
2. Pilih siswa/mapel jika tersedia.
3. Review nilai kelas.

## 13. Forum Diskusi

Menu: `Forum Diskusi`.

Akses: tergantung matriks akses forum.

Fungsi:

- Topik forum.
- Komentar.
- Reaksi.
- Pin topik.
- Lock topik.
- Komentar terbaik.
- Presence peserta.
- Pengaturan akses forum.

Cara membaca forum:

1. Buka `Forum Diskusi`.
2. Pilih kategori/topik.
3. Baca diskusi.

Cara membuat topik:

1. Klik `Buat`.
2. Pilih kategori, audience, kelas jika relevan.
3. Isi judul dan konten.
4. Simpan.

Cara berkomentar:

1. Buka topik.
2. Isi komentar.
3. Kirim.
4. Edit/hapus komentar jika memiliki izin.

Cara moderator/admin:

1. Pin/unpin topik penting.
2. Lock/unlock topik.
3. Tandai komentar terbaik.
4. Hapus topik/komentar yang tidak sesuai.
5. Atur matriks akses melalui `Forum > Akses`.

## 14. Sarana dan Prasarana (Sarpras)

Menu: `Sarana & Prasarana`.

Akses:

- Pengelola: superadmin, admin, sapras.
- Staff/guru/siswa/role tertentu: bisa melihat denah, lapor kerusakan, dan peminjaman sesuai Gate.

### Dashboard Sarpras

Cara pakai:

1. Buka `Dashboard Sarpras`.
2. Pantau jumlah aset, kondisi, laporan kerusakan, peminjaman, pengadaan, dan aktivitas.

### Denah Interaktif

Fungsi:

- Kelola denah gedung/lantai.
- Upload/import gambar denah.
- Gambar denah manual di kanvas.
- Atur hotspot ruangan.
- Tambah/import ruangan.
- Edit posisi ruangan.
- Export denah JPEG/PDF dari UI.

Cara membuat denah:

1. Buka `Denah Interaktif`.
2. Klik tambah lantai/denah.
3. Isi nama gedung/lantai.
4. Simpan.
5. Import gambar denah atau gambar manual.
6. Buka editor hotspot.
7. Tambahkan ruangan dan posisikan di denah.

Cara import ruangan:

1. Buka detail denah.
2. Klik import ruangan.
3. Unduh template.
4. Isi Excel/CSV.
5. Upload dan proses import.

Cara melihat ruangan:

1. Buka denah.
2. Klik hotspot ruangan.
3. Lihat detail ruangan dan aset di ruangan.

### Maintenance Lapor

Fungsi:

- Lapor kerusakan aset/ruangan.
- Upload foto.
- Pengelola menerima atau menolak laporan.

Cara user melapor:

1. Buka `Maintenance Lapor`.
2. Klik lapor kerusakan.
3. Pilih aset atau ruangan.
4. Isi deskripsi.
5. Upload foto jika ada.
6. Kirim ke Waka Sarpras.

Cara pengelola:

1. Buka daftar laporan.
2. Klik detail laporan.
3. Terima jika valid atau tolak jika tidak valid.
4. Jika diterima, lanjutkan ke order perbaikan bila perlu.

### Inventaris Barang

Fungsi:

- Data aset.
- Tambah/edit/hapus aset.
- Import aset.
- Export Excel.
- QR aset.
- Cetak label aset PDF.
- Kategori aset.

Cara tambah aset:

1. Buka `Inventaris Barang`.
2. Klik tambah aset.
3. Isi kode, nama, kategori, ruangan, kondisi, status, nilai perolehan, masa manfaat, dan foto jika ada.
4. Simpan.

Cara import aset:

1. Buka `Inventaris Barang`.
2. Klik import.
3. Unduh template.
4. Isi file.
5. Upload dan proses import.

Cara cetak label:

1. Buka detail aset.
2. Klik `Cetak Label`.
3. Cetak PDF berisi QR, kode, dan nama aset.

### Kategori Aset

Fungsi:

- Tambah/edit/hapus kategori.
- Import kategori.

Cara pakai:

1. Buka kategori dari area inventaris/pengaturan sarpras.
2. Tambahkan kategori aset.
3. Gunakan import jika kategori banyak.

### Pengadaan Aset

Fungsi:

- Ajukan pengadaan.
- Item pengadaan.
- Approval setujui/tolak.
- Penerimaan barang.
- Upload dokumen/nota.

Cara ajukan:

1. Buka `Pengadaan Aset`.
2. Klik pengadaan baru.
3. Isi judul dan item.
4. Tambah item jika lebih dari satu.
5. Kirim pengajuan.

Cara approval:

1. Buka detail pengadaan.
2. Klik setujui atau tolak.
3. Jika disetujui dan barang datang, isi penerimaan.
4. Upload nota/dokumen pendukung.

### Peminjaman Aset dan Booking Ruangan

Fungsi:

- Ajukan peminjaman aset.
- Ajukan booking ruangan.
- Approval setujui/tolak.
- Tandai pengembalian.

Cara ajukan peminjaman aset:

1. Buka `Peminjaman Aset`.
2. Klik ajukan peminjaman.
3. Pilih aset/ruangan dan tanggal.
4. Isi keperluan.
5. Ajukan.

Cara booking ruangan:

1. Buka halaman booking jika tersedia dari modul peminjaman.
2. Pilih ruangan, tanggal, jam mulai, jam selesai, dan keperluan.
3. Kirim pengajuan.

Cara pengelola:

1. Buka detail peminjaman/booking.
2. Setujui atau tolak.
3. Setelah barang kembali, klik kembalikan.

### Perbaikan dan Teknisi

Fungsi:

- Order perbaikan.
- Edit order perbaikan.
- Tandai selesai.
- Master teknisi.
- Jadwal pemeliharaan.

Cara buat order perbaikan:

1. Buka `Perbaikan & Teknisi`.
2. Klik order perbaikan baru.
3. Pilih aset/laporan kerusakan/teknisi.
4. Isi keluhan, tindakan, biaya, dan tanggal jika ada.
5. Simpan.

Cara selesai:

1. Buka detail perbaikan.
2. Update data perbaikan.
3. Klik selesai.

Cara kelola teknisi:

1. Buka sub-menu teknisi.
2. Tambah/edit/hapus teknisi internal atau eksternal.

Cara jadwal pemeliharaan:

1. Buka jadwal pemeliharaan.
2. Tambah jadwal untuk aset/ruangan.
3. Isi tanggal, frekuensi, dan catatan.
4. Simpan.

### Mutasi dan Penghapusan Aset

Fungsi:

- Mutasi aset antar ruangan.
- Berita acara mutasi.
- Pengajuan penghapusan aset.
- Approval penghapusan.
- Berita acara penghapusan.

Cara mutasi aset:

1. Buka `Mutasi & Hapus > Mutasi`.
2. Klik mutasi baru.
3. Pilih aset, ruangan asal, dan ruangan tujuan.
4. Isi alasan.
5. Simpan.
6. Cetak berita acara jika diperlukan.

Cara penghapusan aset:

1. Buka `Penghapusan Aset`.
2. Klik ajukan penghapusan.
3. Pilih aset dan alasan.
4. Ajukan.
5. Pengelola menyetujui atau menolak.
6. Cetak berita acara jika disetujui.

### Supplier

Fungsi:

- Data supplier untuk pengadaan.
- Tambah/edit/hapus supplier.

Cara pakai:

1. Buka `Supplier`.
2. Klik tambah supplier.
3. Isi nama, kontak, alamat, dan catatan.
4. Simpan.

### Laporan Sarpras

Fungsi:

- Laporan aset.
- Laporan aktivitas.
- Export aset Excel/PDF.
- Export mutasi Excel.

Cara pakai:

1. Buka `Laporan`.
2. Pilih jenis laporan.
3. Gunakan filter jika tersedia.
4. Export Excel/PDF sesuai kebutuhan.

## 15. Keuangan SPP

Menu admin/bendahara: `Keuangan`.

Menu siswa/orang tua: `Tagihan SPP`.

### Pembayaran SPP Admin/Bendahara

Fungsi:

- Dashboard pembayaran SPP.
- Daftar kelas.
- Detail pembayaran per kelas.
- Edit nominal/VA/status pembayaran per siswa/bulan.
- Pengaturan kelas.

Cara pakai:

1. Buka `Keuangan > Pembayaran SPP`.
2. Pilih kelas.
3. Cek tabel pembayaran per siswa dan bulan.
4. Klik sel pembayaran untuk update status/nominal/catatan.
5. Buka pengaturan kelas untuk menetapkan nominal, jatuh tempo, VA, atau aturan kelas.
6. Simpan pengaturan.

### Verifikasi Pembayaran

Fungsi:

- Verifikasi bukti bayar dari siswa/orang tua.
- Validasi batch.
- Revisi batch.
- Tolak batch.

Cara pakai:

1. Buka `Keuangan > Verifikasi`.
2. Review bukti bayar yang masuk.
3. Pilih satu atau beberapa pembayaran.
4. Klik verifikasi jika bukti benar.
5. Klik validasi jika sudah cocok dengan rekening koran.
6. Klik revisi jika bukti perlu diperbaiki.
7. Klik tolak jika pembayaran tidak valid.

### Bank dan Metode

Fungsi:

- Mengatur rekening/bank/metode pembayaran sekolah.

Cara pakai:

1. Buka `Keuangan > Bank & Metode`.
2. Isi nama bank, nomor rekening, atas nama, dan instruksi pembayaran.
3. Simpan.

### Tagihan SPP Siswa/Orang Tua

Cara melihat tagihan:

1. Buka `Tagihan SPP`.
2. Pilih bulan/tagihan.
3. Lihat nominal, status, jatuh tempo, dan instruksi bayar.

Cara upload bukti:

1. Buka detail tagihan.
2. Upload bukti transfer.
3. Kirim.
4. Tunggu status berubah setelah diverifikasi bendahara.
5. Jika diminta revisi, upload ulang bukti.

## 16. Cetak Data

Menu: `Cetak Data`.

Akses: admin.

Fungsi:

- Export Excel Data Siswa.
- Export Excel Data Guru.
- Export Excel Data Kelas.
- Export Excel Absensi Guru.
- Export Excel Data Agenda.
- Export Excel Buku Batas.
- Export Excel Nilai Formatif.
- Export Excel Nilai Sumatif.
- Export Excel Nilai Rapor.
- Export Excel Nilai PAS.
- Export Excel Nilai Penjabaran.

Cara umum:

1. Buka menu `Cetak Data`.
2. Pilih jenis data.
3. Isi filter seperti kelas, guru, atau rentang tanggal jika tersedia.
4. Klik unduh/export.
5. Simpan file Excel.

Catatan per jenis:

- Data Siswa biasanya memakai filter kelas/tingkat.
- Absensi Guru memakai rentang tanggal.
- Agenda dan Buku Batas memakai rentang tanggal dan/atau guru/kelas.
- Nilai memakai kelas atau parameter mapel/kelas sesuai halaman.

## 17. Sistem dan Pengaturan

Menu: `Sistem`.

Akses: admin atau role dengan `manage_settings`.

### Pengaturan Umum

Fungsi:

- Identitas sekolah.
- Logo/media sosial.
- Semester aktif.
- Sistem absensi.
- Kiosk token.
- Lokasi QR.
- Waktu terlambat.
- Poin terlambat.
- Jenis aturan: Poin atau P3.
- Poin terlambat aturan.
- Agenda wajib pulang.
- Mapel rapor.
- Tanggal rapor.
- Rumus rapor.
- Wali kelas boleh lihat nilai.
- TP range.
- Upload aplikasi APK/Windows.

Cara pakai:

1. Buka `Sistem > Pengaturan`.
2. Isi identitas sekolah.
3. Atur semester aktif.
4. Atur metode absensi dan lokasi QR.
5. Pilih sistem disiplin: Poin atau P3.
6. Atur rumus rapor dan tanggal rapor.
7. Aktifkan/nonaktifkan wali kelas lihat nilai.
8. Upload APK/installer Windows jika fitur unduh aplikasi ingin ditampilkan.
9. Simpan tiap bagian pengaturan.

### Nilai Penjabaran

Menu: `Pengaturan > Penjabaran`.

Cara pakai:

1. Buka pengaturan penjabaran.
2. Tambah komponen nilai.
3. Atur label dan konfigurasi.
4. Simpan.

### Kop Rapor

Menu: `Pengaturan > Kop Rapor`.

Cara pakai:

1. Buka kop rapor.
2. Upload logo/kop/backdrop jika tersedia.
3. Isi teks kop.
4. Simpan.
5. Cek hasil di cetak rapor.

### Hak Akses (RBAC)

Menu: `Sistem > Hak Akses (RBAC)`.

Role yang bisa dikonfigurasi:

- kepala
- kurikulum
- kesiswaan
- sarpras
- bendahara
- guru
- orangtua
- siswa

Permission yang tersedia:

- `manage_users`: Data Master.
- `manage_absensi`: Absensi dan presensi.
- `manage_jadwal`: Jadwal pelajaran.
- `view_all_nilai`: Melihat semua nilai.
- `manage_agenda`: Mengelola/validasi agenda.
- `manage_rapor`: Rekap nilai dan cetak rapor.
- `manage_disiplin`: Modul kedisiplinan.
- `manage_sarpras`: Sarpras.
- `manage_keuangan`: Keuangan.
- `manage_pengumuman`: Pengumuman.
- `manage_settings`: Pengaturan sistem.
- `manage_perangkat`: Monitoring perangkat ajar guru.

Cara pakai:

1. Buka `Hak Akses (RBAC)`.
2. Centang permission untuk role.
3. Simpan.
4. Logout/login ulang user terkait jika menu belum berubah.

## 18. Unduh Aplikasi

Menu: `Unduh Aplikasi`.

Akses: semua user login, jika admin mengaktifkan fitur dan sudah mengupload file.

Cara admin mengaktifkan:

1. Buka `Sistem > Pengaturan`.
2. Cari bagian `Unduh Aplikasi`.
3. Aktifkan fitur.
4. Upload APK Android dan/atau installer Windows.
5. Simpan.

Cara user mengunduh:

1. Buka `Unduh Aplikasi`.
2. Pilih platform Android atau Windows.
3. Klik `Unduh`.

Catatan:

- File disimpan privat dan hanya bisa diunduh melalui route login.

## 19. Kartu Pelajar Digital untuk Siswa

Menu siswa: `Kartu Pelajar`.

Cara pakai siswa:

1. Buka `Kartu Pelajar`.
2. Klik lihat untuk preview kartu.
3. Klik unduh untuk mengunduh kartu.

Catatan:

- Jika admin mengupload kartu kustom, file kustom dipakai.
- Jika tidak ada kartu kustom, sistem generate kartu default.

## 20. Alur Operasional Harian yang Disarankan

### Admin/Operator

1. Pastikan semester aktif benar.
2. Update data guru, siswa, kelas, mapel.
3. Atur wali kelas dan penugasan guru mengajar.
4. Cek absensi dan presensi harian.
5. Pantau pengumuman, forum, chatbot, dan notifikasi.
6. Export data/cetak sesuai kebutuhan.

### Guru

1. Login dan pastikan wajah sudah terdaftar.
2. Cek jadwal mengajar.
3. Isi agenda mengajar setiap selesai kelas.
4. Upload materi/tugas di Ruang Kelas.
5. Isi nilai di Buku Guru.
6. Upload perangkat ajar.
7. Ajukan Poin/P3 jika ada kejadian siswa.

### Wali Kelas

1. Cek data siswa kelas.
2. Set sekretaris kelas.
3. Isi/monitor absensi kelas.
4. Pantau Poin/P3 siswa.
5. Review nilai kelas jika fitur aktif.
6. Cetak rapor saat nilai final.

### Siswa

1. Login.
2. Daftarkan wajah jika diminta.
3. Absen QR.
4. Buka Ruang Kelas untuk materi/tugas.
5. Kumpulkan tugas.
6. Cek nilai.
7. Cek tagihan SPP.
8. Unduh kartu pelajar jika perlu.

### Orang Tua

1. Login akun orang tua.
2. Cek pengumuman.
3. Cek nilai anak.
4. Cek tagihan SPP.
5. Upload bukti pembayaran.
6. Gunakan chatbot untuk menghubungi admin jika ada kendala.

### Bendahara

1. Buka Keuangan.
2. Atur bank/metode pembayaran.
3. Atur tagihan per kelas.
4. Review bukti pembayaran masuk.
5. Verifikasi, validasi, revisi, atau tolak bukti.
6. Pantau rekap pembayaran.

### Waka Sarpras

1. Cek dashboard Sarpras.
2. Review laporan kerusakan.
3. Kelola aset dan ruangan.
4. Proses pengadaan/peminjaman/perbaikan.
5. Catat mutasi dan penghapusan aset.
6. Export laporan aset atau mutasi.

## 21. Checklist Agar Tidak Miss Saat Training User

Gunakan checklist ini saat demo ke sekolah:

- Login, logout, ganti password, PIN, dan registrasi wajah.
- Dashboard dan notifikasi.
- Pengumuman.
- Chatbot user dan Inbox Admin.
- Data Guru, import guru, reset guru, penugasan mengajar.
- Data Siswa, import siswa, reset siswa/orang tua.
- Data Kelas, wali kelas, rombel, histori rombel.
- Mata Pelajaran.
- Kartu Pelajar admin dan siswa.
- Kalender Absensi.
- Absensi siswa manual dan rekap.
- QR Absensi dan Absen QR user.
- Presensi guru dan rekap.
- Validasi wajah, galeri, dan wajah ganda.
- Ruang Kelas: materi, tugas, komentar, submission, penilaian, transfer nilai.
- Jadwal Pelajaran dan Jadwal Mengajar.
- Penilaian: KKTP, materi, TP, formatif, sumatif, penjabaran, PTS, PAS, rapor.
- Nilai Saya.
- Rekap Nilai dan Cetak Rapor.
- Ekstrakurikuler.
- Perangkat Ajar.
- Agenda Guru, Rekap Agenda, Buku Batas.
- Sistem Poin atau P3 sesuai pengaturan aktif.
- Wali Kelas: siswa kelas, sekretaris, absensi kelas, disiplin kelas, nilai kelas.
- Forum Diskusi dan pengaturan akses forum.
- Analisis AI dan Dokumen AI.
- Sarpras lengkap: dashboard, denah, ruangan, kerusakan, aset, kategori, pengadaan, supplier, peminjaman, booking, perbaikan, teknisi, jadwal, mutasi, penghapusan, laporan.
- Keuangan: pembayaran SPP, verifikasi, bank/metode, tagihan siswa/orang tua.
- Cetak Data semua export.
- Sistem: pengaturan umum, kop rapor, penjabaran, RBAC, upload aplikasi.
- Unduh Aplikasi.

## 22. Catatan Review Teknis Singkat

- [Pasti] Aplikasi ini adalah SIMS Laravel dengan banyak modul sekolah, bukan hanya LMS.
- [Pasti] Modul besar yang ada: master data, absensi/presensi, akademik, agenda, disiplin Poin/P3, wali kelas, forum, sarpras, keuangan SPP, AI, chatbot, pengumuman, cetak data, pengaturan.
- [Pasti] Sarpras punya route terpisah di `routes/sarpras.php` dan perlu dimasukkan dalam training user.
- [Pasti] Hak akses tidak satu mekanisme saja: RBAC umum, matriks forum, Gate Sarpras, dan policy classroom.
- [Pasti] Beberapa menu hanya muncul berdasarkan role, permission, atau pengaturan aktif. Saat training, login dengan beberapa role agar semua fitur terlihat.
