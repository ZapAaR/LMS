# Library Management System

Aplikasi manajemen perpustakaan berbasis web yang dibangun menggunakan Laravel 12 dan MySQL. Aplikasi ini membantu petugas perpustakaan mengelola data buku, kategori, anggota, peminjaman, pengembalian, serta laporan transaksi.

## Fitur Aplikasi

### 1. Dashboard
- Menampilkan ringkasan data perpustakaan.
- Menampilkan total buku dan anggota.
- Menampilkan jumlah peminjaman aktif dan pengembalian.
- Menampilkan transaksi terbaru.

### 2. Manajemen Kategori
- Menambahkan kategori buku.
- Mengubah data kategori.
- Menghapus kategori.
- Menampilkan daftar kategori.

### 3. Manajemen Buku
- Menambahkan data buku.
- Mengubah data buku.
- Menghapus data buku.
- Pencarian buku.
- Filter berdasarkan kategori.
- Sorting berdasarkan judul.
- Filter berdasarkan ketersediaan stok.
- Pagination.
- Bulk delete.
- Melihat detail buku.

### 4. Manajemen Anggota
- Menambahkan data anggota.
- Mengubah data anggota.
- Menghapus data anggota.
- Pencarian anggota.
- Sorting dan pagination.
- Mengubah status anggota menjadi aktif atau nonaktif.
- Bulk update status.
- Melihat detail anggota.

### 5. Manajemen Peminjaman
- Mencatat transaksi peminjaman buku.
- Memeriksa status keaktifan anggota.
- Memeriksa ketersediaan stok buku.
- Membatasi jumlah peminjaman maksimal 3 buku per transaksi.
- Menentukan tanggal jatuh tempo secara otomatis.
- Mengurangi stok tersedia setelah peminjaman berhasil.

### 6. Manajemen Pengembalian
- Mencatat pengembalian buku.
- Menghitung keterlambatan pengembalian.
- Menghitung denda berdasarkan aturan yang diterapkan.
- Mengembalikan stok buku secara otomatis.
- Mencegah transaksi pengembalian diproses lebih dari satu kali.

### 7. Riwayat Transaksi
- Melihat riwayat peminjaman.
- Melihat riwayat pengembalian.
- Pencarian transaksi.
- Filter berdasarkan tanggal.
- Filter berdasarkan status.
- Sorting dan pagination.

### 8. Laporan
- Laporan peminjaman.
- Laporan pengembalian.
- Laporan keterlambatan.
- Pencarian dan filter berdasarkan rentang tanggal.
- Sorting dan pagination.

### 9. Manajemen Pengguna
- Pengelolaan akun pengguna aplikasi.
- Pembagian hak akses berdasarkan role.

## Teknologi yang Digunakan

| Teknologi | Keterangan |
|---|---|
| PHP | Bahasa pemrograman backend |
| Laravel 12 | Backend framework |
| MySQL | Database |
| Bootstrap | CSS framework |
| Blade | Template engine |
| JavaScript | Interaksi frontend |
| Composer | Dependency manager PHP |
| NPM | Dependency manager frontend |
| Git | Version control |
| GitHub | Repository project |

## Persyaratan Sistem

Pastikan perangkat sudah memiliki:

- PHP 8.2 atau lebih baru.
- Composer.
- MySQL atau MariaDB.
- Node.js dan NPM.
- Git.
- Web browser.

XAMPP dapat digunakan untuk menjalankan Apache dan MySQL secara lokal.

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/USERNAME/REPOSITORY.git
```

Masuk ke folder project:

```bash
cd REPOSITORY
```

Ganti `USERNAME` dan `REPOSITORY` sesuai alamat repository GitHub.

### 2. Install Dependency

Install dependency Laravel:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`.

Windows CMD:

```cmd
copy .env.example .env
```

Linux/macOS:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Konfigurasi Database

Buat database baru melalui phpMyAdmin atau MySQL.

Contoh nama database:

```text
library_management
```

Sesuaikan konfigurasi database pada file `.env`:

```env
APP_NAME="Library Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_management
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_PORT`, username, dan password dengan konfigurasi MySQL di perangkat masing-masing.

### 5. Migrasi Database

Jalankan migrasi:

```bash
php artisan migrate
```

Jika project sudah menyediakan seeder dan kamu ingin mengisi data awal:

```bash
php artisan migrate --seed
```

### 6. Build Frontend

```bash
npm run build
```

## Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Buka aplikasi melalui browser:

```text
http://127.0.0.1:8000
```

Untuk pengembangan frontend menggunakan Vite, jalankan:

```bash
npm run dev
```

## Struktur Database

Aplikasi menggunakan beberapa tabel utama:

| Tabel | Fungsi |
|---|---|
| users | Menyimpan akun pengguna aplikasi |
| roles | Menyimpan role pengguna |
| categories | Menyimpan kategori buku |
| books | Menyimpan data buku dan stok |
| members | Menyimpan data anggota perpustakaan |
| borrowings | Menyimpan transaksi peminjaman |
| borrowing_details | Menyimpan detail buku dalam transaksi peminjaman |
| returns | Menyimpan transaksi pengembalian |

### Relasi Database

- `categories` memiliki banyak `books`.
- `members` memiliki banyak `borrowings`.
- `users` dapat memproses banyak transaksi peminjaman.
- `borrowings` memiliki banyak `borrowing_details`.
- `books` dapat muncul dalam banyak `borrowing_details`.
- `borrowings` memiliki satu transaksi `returns`.
- `users` dapat memproses banyak transaksi pengembalian.

## Aturan Bisnis

Aturan utama dalam aplikasi:

1. Maksimal 3 buku dalam satu transaksi peminjaman.
2. Durasi peminjaman adalah 7 hari.
3. Anggota harus berstatus aktif untuk melakukan peminjaman.
4. Buku harus memiliki stok tersedia.
5. Stok buku tidak boleh menjadi negatif.
6. Tanggal jatuh tempo dihitung otomatis berdasarkan tanggal peminjaman.
7. Pengembalian diproses secara penuh.
8. Satu transaksi peminjaman hanya dapat memiliki satu transaksi pengembalian.
9. Stok tersedia bertambah setelah pengembalian berhasil.

## Role dan Hak Akses

Aplikasi memiliki dua role pengguna:

| Role | Keterangan |
|---|---|
| Admin | Mengelola aplikasi sesuai hak akses administrator |
| Petugas | Mengelola operasional perpustakaan sesuai hak akses petugas |

Akses setiap fitur dibatasi menggunakan middleware dan pemeriksaan role.

## Struktur Folder

Beberapa direktori penting dalam project Laravel:

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
└── Services/

database/
├── migrations/
└── seeders/

resources/
└── views/

routes/
└── web.php

public/
```

Keterangan:

- `Controllers`: Mengelola request dan response.
- `Middleware`: Memeriksa autentikasi dan hak akses.
- `Requests`: Mengelola validasi input.
- `Models`: Mengelola representasi tabel database.
- `Services`: Menangani business logic aplikasi.
- `migrations`: Mengelola struktur database.
- `seeders`: Menyediakan data awal.
- `views`: Menyimpan tampilan Blade.
- `routes`: Mendefinisikan endpoint aplikasi.

## Screenshot Aplikasi

Screenshot aplikasi akan ditambahkan setelah tampilan dan seluruh fitur utama selesai diuji.

Contoh struktur penyimpanan screenshot:

```text
docs/
└── screenshots/
    ├── dashboard.png
    ├── categories.png
    ├── books.png
    ├── members.png
    ├── borrowing.png
    ├── returns.png
    ├── transaction-history.png
    └── reports.png
```

Contoh menampilkan screenshot di README:

```markdown
![Dashboard](docs/screenshots/dashboard.png)
```

## Pengembangan

Project ini dikembangkan sebagai project pembelajaran untuk menerapkan konsep pengembangan aplikasi web menggunakan Laravel, termasuk:

- CRUD (Create, Read, Update, Delete).
- MVC Architecture.
- Service Layer.
- Form Request Validation.
- Eloquent ORM dan relasi database.
- Database Transaction.
- Role-Based Access Control.
- Search, Filter, Sorting, dan Pagination.
- Pengelolaan stok dan transaksi.

## Status Project

**Status:** Dalam pengembangan.

Hosting dan deployment belum dilakukan.

## Repository

GitHub: https://github.com/ZapAaR/LMS.git

## Author

Nama: ZapAaR

GitHub: https://github.com/ZapAaR
