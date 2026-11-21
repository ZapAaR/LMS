# Library Management System (LMS)
## Project Planning & Brainstorming

## 1. Tujuan

Library Management System adalah aplikasi berbasis web yang bertujuan untuk membantu pengelolaan operasional perpustakaan secara terstruktur dan efisien.

Tujuan utama aplikasi:

- Mempermudah pengelolaan data buku.
- Mempermudah pengelolaan data anggota.
- Mengelola transaksi peminjaman dan pengembalian buku.
- Memantau ketersediaan stok buku.
- Mengurangi kesalahan pencatatan transaksi secara manual.
- Menyediakan riwayat dan laporan aktivitas perpustakaan.

## 2. Pengguna

Aplikasi memiliki dua jenis pengguna dengan hak akses berbeda.

### Admin

Bertanggung jawab atas pengelolaan keseluruhan sistem.

Hak akses:
- Mengelola data buku.
- Mengelola kategori buku.
- Mengelola data anggota.
- Mengelola akun petugas.
- Mengelola transaksi peminjaman.
- Mengelola transaksi pengembalian.
- Melihat laporan perpustakaan.
- Melihat dashboard.

### Petugas

Bertanggung jawab atas operasional perpustakaan sehari-hari.

Hak akses:
- Melihat dashboard.
- Melihat data buku.
- Mengelola data buku.
- Mengelola data anggota.
- Memproses peminjaman buku.
- Memproses pengembalian buku.
- Melihat riwayat transaksi.

## 3. Fitur yang Akan Digunakan

### 3.1 Authentication & Authorization

- Login dan logout.
- Role-based access control (RBAC).
- Pembatasan akses berdasarkan role.

### 3.2 Dashboard

- Total buku.
- Total anggota.
- Total peminjaman aktif.
- Total pengembalian.
- Informasi buku yang sedang dipinjam.

### 3.3 Manajemen Kategori

- Menambahkan kategori buku.
- Menampilkan kategori buku.
- Mengubah kategori buku.
- Menghapus kategori buku.

### 3.4 Manajemen Buku

- Menambahkan buku.
- Menampilkan daftar buku.
- Mengubah informasi buku.
- Menghapus buku.
- Mencari buku berdasarkan judul atau penulis.
- Filter berdasarkan kategori.
- Mengelola stok buku.

### 3.5 Manajemen Anggota

- Menambahkan anggota.
- Menampilkan daftar anggota.
- Mengubah data anggota.
- Menghapus anggota.
- Mencari anggota.

### 3.6 Peminjaman Buku

- Memilih anggota.
- Memilih buku.
- Memvalidasi ketersediaan stok.
- Memvalidasi batas maksimal peminjaman.
- Menentukan tanggal peminjaman.
- Menentukan tanggal jatuh tempo.
- Menyimpan transaksi peminjaman.
- Mengurangi stok buku secara otomatis.

### 3.7 Pengembalian Buku

- Mencari transaksi peminjaman.
- Memvalidasi status peminjaman.
- Mencatat tanggal pengembalian.
- Menghitung keterlambatan.
- Menghitung denda jika ada.
- Mengembalikan stok buku secara otomatis.
- Memperbarui status transaksi.

### 3.8 Riwayat Transaksi

- Melihat riwayat peminjaman.
- Melihat riwayat pengembalian.
- Melihat status transaksi.
- Mencari transaksi berdasarkan anggota.

### 3.9 Laporan

- Laporan data buku.
- Laporan data anggota.
- Laporan peminjaman.
- Laporan pengembalian.
- Laporan keterlambatan.

## 4. Tech Stack

| Kategori | Teknologi |
|---|---|
| Backend | Laravel 12 |
| Programming Language | PHP 8.2+ |
| Database | MySQL |
| Frontend | Laravel Blade |
| CSS Framework | Bootstrap / Tailwind CSS |
| Authentication | Laravel Breeze |
| Authorization | Middleware / Policy |
| API | Laravel REST API |
| API Testing | Postman |
| Version Control | Git & GitHub |

## 5. Arsitektur

Aplikasi menggunakan pendekatan Monolithic Architecture dengan Laravel.

Seluruh komponen backend dan frontend berada dalam satu aplikasi Laravel.

### 5.1 Gambaran Arsitektur

```text
                  USER
                    |
                    v
              WEB BROWSER
                    |
                    v
              ROUTING LAYER
                    |
                    v
             MIDDLEWARE LAYER
                    |
                    v
              CONTROLLER
                    |
                    v
              SERVICE LAYER
                    |
                    v
              MODEL / ELOQUENT
                    |
                    v
                DATABASE
                  MySQL
```

### 5.2 Penjelasan Komponen

| Komponen | Tanggung Jawab |
|---|---|
| Routing | Mengatur endpoint aplikasi |
| Middleware | Memeriksa autentikasi dan hak akses |
| Controller | Menerima request dan mengatur response |
| Service | Menangani business logic aplikasi |
| Model | Mengelola interaksi dengan database |
| Database | Menyimpan seluruh data aplikasi |
| Blade | Menampilkan halaman antarmuka |

### 5.3 Pola Pengembangan

Aplikasi menggunakan Service Layer Pattern untuk memisahkan business logic dari Controller.

Contoh pembagian tanggung jawab:

- BookController: Mengelola request terkait buku.
- BookService: Menangani proses bisnis buku.
- BorrowingController: Mengelola request peminjaman.
- BorrowingService: Menangani validasi dan proses peminjaman.
- ReturnController: Mengelola request pengembalian.
- ReturnService: Menangani proses pengembalian dan perhitungan denda.

### 5.4 Database

Database menggunakan MySQL dengan relasi antar tabel.

Rencana tabel:

| Tabel | Fungsi |
|---|---|
| users | Menyimpan akun pengguna |
| roles | Menyimpan role pengguna |
| members | Menyimpan data anggota |
| categories | Menyimpan kategori buku |
| books | Menyimpan data buku |
| borrowings | Menyimpan transaksi peminjaman |
| borrowing_details | Menyimpan detail buku yang dipinjam |
| returns | Menyimpan informasi pengembalian |

---

## 6. Status Pengembangan

**Status:** Planning / Brainstorming

Tahapan pengembangan:

- [ ] Finalisasi kebutuhan aplikasi.
- [ ] Finalisasi fitur.
- [ ] Perancangan database.
- [ ] Perancangan relasi database.
- [ ] Perancangan struktur folder.
- [ ] Implementasi authentication dan authorization.
- [ ] Implementasi manajemen buku.
- [ ] Implementasi manajemen anggota.
- [ ] Implementasi peminjaman.
- [ ] Implementasi pengembalian.
- [ ] Implementasi dashboard dan laporan.
- [ ] Testing aplikasi.
- [ ] Dokumentasi proyek.

**Catatan:** Belum memasuki tahap implementasi kode.
