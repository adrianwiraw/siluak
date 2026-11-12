# SILUAK (CodeIgniter 4)

SILUAK adalah aplikasi berbasis CodeIgniter 4. Aplikasi Laravel sebelumnya
tetap berada di direktori `siluak` yang sejajar dengan proyek ini.

## Menjalankan dengan Docker

### Persyaratan

- Docker Desktop sudah terpasang dan sedang berjalan.
- Docker Compose tersedia melalui perintah `docker compose`.

### 1. Siapkan konfigurasi

Buka PowerShell di folder proyek. Salin berkas konfigurasi contoh jika berkas
lokal belum tersedia:

```powershell
if (-not (Test-Path .env)) { Copy-Item env .env }
if (-not (Test-Path .env.docker)) { Copy-Item .env.docker.example .env.docker }
```

Edit `.env.docker` untuk mengatur database yang digunakan Docker Compose.
Ganti kedua kata sandi contoh dengan nilai lokal yang unik:

```ini
SILUAK_DB_NAME=siluak
SILUAK_DB_USER=siluak
SILUAK_DB_PASSWORD=ganti-dengan-kata-sandi-lokal
SILUAK_DB_ROOT_PASSWORD=ganti-dengan-kata-sandi-root-lokal
```

Lalu edit `.env` untuk mengatur aplikasi agar terhubung ke layanan database
`db` di dalam jaringan Docker. Pastikan nama database, pengguna, dan kata
sandi sesuai dengan nilai di `.env.docker`:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
database.default.hostname = db
database.default.database = siluak
database.default.username = siluak
database.default.password = 'ganti-dengan-kata-sandi-lokal'
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Jangan gunakan kata sandi contoh untuk lingkungan produksi. Jangan bagikan
atau commit berkas `.env` dan `.env.docker`.

### 2. Build image dan jalankan container

Jalankan perintah berikut dari folder proyek. Opsi `--build` membangun image
aplikasi terlebih dahulu; Compose kemudian menjalankan aplikasi dan MariaDB di
latar belakang:

```powershell
docker compose --env-file .env.docker up --build -d
```

Untuk memeriksa status container:

```powershell
docker compose --env-file .env.docker ps
```

### 3. Siapkan database

Setelah container aktif dan database siap, jalankan migrasi untuk membuat
tabel aplikasi:

```powershell
docker compose --env-file .env.docker exec app php spark migrate --all
```

Untuk membuat akun administrator pertama pada database baru, isi
`SILUAK_ADMIN_EMAIL` dan `SILUAK_ADMIN_PASSWORD` di `.env`, lalu jalankan
seeder:

```powershell
docker compose --env-file .env.docker exec app php spark db:seed DatabaseSeeder
```

### 4. Buka aplikasi

Buka alamat berikut di browser:

**http://localhost:8080**

Setelah mengirim pengajuan layanan, pemohon akan mendapat kode tiket untuk
mengecek status. Kode tersebut dapat digunakan kembali di menu **Cek Tiket**
atau di alamat **http://localhost:8080/tiket**.

Jika halaman belum dapat dibuka, periksa status dan log container:

```powershell
docker compose --env-file .env.docker ps
docker compose --env-file .env.docker logs app
docker compose --env-file .env.docker logs db
```

Untuk menghentikan container tanpa menghapus data:

```powershell
docker compose --env-file .env.docker down
```

Database dan berkas unggahan disimpan di Docker volumes sehingga tetap ada
setelah container dihentikan. Perintah berikut juga menghapus volume beserta
data database dan unggahan:

```powershell
docker compose --env-file .env.docker down --volumes
```

Gunakan `--volumes` hanya jika memang ingin menghapus data tersebut.

Data aplikasi Laravel yang sudah ada tidak otomatis disalin ke database
MariaDB di Docker. Untuk memakai database yang sudah ada, ubah konfigurasi
koneksi di `.env`, buat cadangan data, dan pastikan targetnya sebelum
menjalankan migrasi.

## Menjalankan tanpa Docker

### Persyaratan

PHP 8.2 atau lebih baru, Composer, MySQL/MariaDB (atau database lain dengan
driver yang dikonfigurasi), serta ekstensi PHP `intl`, `mbstring`, `zip`, dan
`gd`.

### Persiapan

1. Salin `env` menjadi `.env` jika `.env` belum tersedia.
2. Atur `app.baseURL` dan koneksi database di `.env`. Gunakan database yang
   sama dengan Laravel jika ingin mengakses data pengguna dan pengajuan yang
   sudah ada. Jangan masukkan kredensial produksi ke kontrol versi.
3. Pasang dependensi:

   ```powershell
   composer install
   ```

4. Jalankan migrasi:

   ```powershell
   php spark migrate --all
   ```

   Migrasi membuat tabel domain SILUAK yang belum ada dan menambahkan kolom
   kode tiket yang nullable pada tabel pengajuan untuk menjaga data lama.
5. Arahkan document root web server ke folder `public/`, atau jalankan server
   bawaan CodeIgniter:

   ```powershell
   php spark serve
   ```

   Kemudian buka **http://localhost:8080**.

Untuk SQLite, atur `database.default.DBDriver = SQLite3` dan isi
`database.default.database` dengan lokasi berkas SQLite. Untuk MySQL/MariaDB,
atur hostname, nama database, pengguna, dan kata sandi di `.env`. Pastikan
koneksi yang dipilih benar sebelum menjalankan migrasi.

Aplikasi tidak membuat akun administrator dengan kata sandi bawaan. Pada
database baru, isi `SILUAK_ADMIN_EMAIL` dan `SILUAK_ADMIN_PASSWORD` di `.env`,
lalu jalankan `php spark db:seed DatabaseSeeder`. Jika database yang
dikonfigurasi sudah memiliki data pengguna Laravel, akun tersebut dapat
digunakan.

## Pengujian

Pengujian menggunakan konfigurasi SQLite dalam memori. Jika `sqlite3` sudah
terpasang tetapi belum aktif pada konfigurasi PHP CLI, jalankan:

```powershell
php -d extension=sqlite3 vendor/bin/phpunit --no-coverage
```

## Catatan migrasi

- Nama tabel dan kolom database dipertahankan agar kompatibel dengan skema
  Laravel.
- Unggahan disimpan di `writable/uploads`. Cadangkan dan pindahkan berkas
  tersebut secara terpisah jika lokasi penyimpanan berubah.
- Aktifkan ekstensi PHP `zip` dan `gd` pada konfigurasi web server untuk
  menggunakan ekspor XLSX.
- Template Laravel Blade dan plugin Laravel Vite tidak diperlukan oleh
  aplikasi ini saat dijalankan.
