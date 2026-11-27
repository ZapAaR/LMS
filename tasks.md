# Library Management System
# Development Tasks

Status:
- [ ] Not Started
- [x] Completed

---

## PHASE 1 - Project Setup

- [x] Membuat project Laravel 12.
- [x] Mengatur koneksi database MySQL.
- [x] Membuat database perpustakaan.
- [x] Mengatur file .env.
- [x] Memastikan aplikasi dapat dijalankan.
- [x] Menginisialisasi Git.
- [x] Membuat repository GitHub.

Checkpoint:
- [x] Aplikasi Laravel berhasil dijalankan.
- [x] Laravel berhasil terhubung ke MySQL.

---

## PHASE 2 - Authentication & Authorization

- [x] Menginstal Laravel Breeze.
- [x] Mengatur halaman login.
- [X] Mengatur proses logout.
- [X] Membuat migration roles.
- [X] Membuat relasi users dan roles.
- [x] Membuat seeder role admin.
- [x] Membuat seeder role petugas.
- [x] Membuat middleware role.
- [x] Membatasi halaman berdasarkan role.

Checkpoint:
- [x] Admin dapat login.
- [x] Petugas dapat login.
- [x] Hak akses setiap role berjalan.

---

## PHASE 3 - Category Management

- [x] Membuat migration categories.
- [x] Membuat model Category.
- [x] Membuat CategoryController.
- [x] Membuat CategoryService.
- [x] Membuat validasi kategori.
- [x] Membuat halaman daftar kategori.
- [x] Membuat halaman tambah kategori.
- [x] Membuat proses simpan kategori.
- [x] Membuat halaman edit kategori.
- [x] Membuat proses update kategori.
- [x] Membuat proses hapus kategori.
- [x] Menguji CRUD kategori.

Checkpoint:
- [x] CRUD kategori berjalan.
- [x] Validasi kategori berjalan.

---

## PHASE 4 - Book Management

- [x] Membuat migration books.
- [x] Membuat model Book.
- [x] Membuat relasi Book dan Category.
- [x] Membuat BookController.
- [x] Membuat BookService.
- [x] Membuat validasi buku.
- [x] Membuat halaman daftar buku.
- [x] Membuat halaman tambah buku.
- [x] Membuat proses simpan buku.
- [x] Membuat halaman edit buku.
- [x] Membuat proses update buku.
- [x] Membuat proses hapus buku.
- [x] Membuat pencarian buku.
- [x] Membuat filter kategori.
- [x] Membuat pagination.
- [x] Menguji CRUD buku.

Checkpoint:
- [x] CRUD buku berjalan.
- [x] Relasi kategori berjalan.
- [x] Pencarian dan filter berjalan.

---

## PHASE 5 - Member Management

- [x] Membuat migration members.
- [x] Membuat model Member.
- [x] Membuat MemberController.
- [x] Membuat MemberService.
- [x] Membuat validasi anggota.
- [x] Membuat halaman daftar anggota.
- [x] Membuat halaman tambah anggota.
- [x] Membuat proses simpan anggota.
- [x] Membuat halaman edit anggota.
- [x] Membuat proses update anggota.
- [x] Membuat proses hapus anggota.
- [x] Membuat pencarian anggota.
- [x] Menguji CRUD anggota.

Checkpoint:
- [x] CRUD anggota berjalan.
- [x] Validasi anggota berjalan.

---

## PHASE 6 - Borrowing Management

- [x] Membuat migration borrowings.
- [x] Membuat migration borrowing_details.
- [x] Membuat model Borrowing.
- [x] Membuat model BorrowingDetail.
- [x] Membuat relasi peminjaman dan anggota.
- [x] Membuat relasi peminjaman dan buku.
- [x] Membuat BorrowingController.
- [x] Membuat BorrowingService.
- [x] Membuat validasi peminjaman.
- [x] Memeriksa status anggota.
- [x] Memeriksa ketersediaan stok.
- [x] Memeriksa batas maksimal peminjaman.
- [x] Menentukan tanggal jatuh tempo.
- [x] Menyimpan transaksi peminjaman.
- [x] Mengurangi stok buku.
- [x] Menggunakan database transaction.
- [x] Menguji proses peminjaman.

Checkpoint:
- [x] Peminjaman berhasil.
- [x] Stok berkurang secara otomatis.
- [x] Anggota tidak aktif tidak dapat meminjam.
- [x] Buku tanpa stok tidak dapat dipinjam.
- [x] Batas maksimal peminjaman berjalan.
- [x] Transaksi aman dari perubahan parsial.

---

## PHASE 7 - Return Management

- [x] Membuat migration returns.
- [x] Membuat model Return.
- [x] Membuat ReturnController.
- [x] Membuat ReturnService.
- [x] Membuat validasi pengembalian.
- [x] Mencari transaksi peminjaman.
- [x] Memeriksa status peminjaman.
- [x] Mencatat tanggal pengembalian.
- [x] Menghitung keterlambatan.
- [x] Menghitung denda.
- [x] Mengembalikan stok buku.
- [x] Memperbarui status transaksi.
- [x] Menggunakan database transaction.
- [x] Menguji proses pengembalian.

Checkpoint:
- [x] Pengembalian berhasil.
- [x] Stok bertambah secara otomatis.
- [x] Denda dihitung dengan benar.
- [x] Pengembalian tidak dapat diproses dua kali.

---

## PHASE 8 - Transaction History

- [x] Membuat halaman riwayat peminjaman.
- [x] Membuat halaman riwayat pengembalian.
- [x] Menampilkan status transaksi.
- [x] Membuat pencarian transaksi.
- [x] Membuat filter berdasarkan tanggal.
- [x] Membuat pagination.
- [x] Menguji riwayat transaksi.

Checkpoint:
- [x] Riwayat transaksi dapat ditampilkan.
- [x] Filter dan pencarian berjalan.

---

## PHASE 9 - Dashboard & Reports

- [x] Membuat tampilan dashboard.
- [x] Menampilkan total buku.
- [x] Menampilkan total anggota.
- [x] Menampilkan peminjaman aktif.
- [x] Menampilkan jumlah pengembalian.
- [x] Menampilkan transaksi terbaru.
- [x] Membuat laporan peminjaman.
- [x] Membuat laporan pengembalian.
- [x] Membuat laporan keterlambatan.

Checkpoint:
- [x] Dashboard menampilkan data aktual.
- [x] Laporan dapat ditampilkan.

---

## PHASE 10 - Testing & Quality

- [x] Menguji authentication.
- [x] Menguji authorization.
- [x] Menguji CRUD kategori.
- [x] Menguji CRUD buku.
- [x] Menguji CRUD anggota.
- [x] Menguji peminjaman.
- [x] Menguji pengembalian.
- [x] Menguji validasi input.
- [x] Menguji business rules.
- [x] Menguji database transaction.
- [x] Memeriksa error handling.
- [x] Memeriksa keamanan aplikasi.

Checkpoint:
- [x] Seluruh fitur utama berjalan.
- [x] Tidak ada bug kritis.
- [x] Business rules berjalan sesuai PRD.

---

## PHASE 11 - Documentation & Deployment

- [x] Membuat README.md.
- [x] Menjelaskan kebutuhan instalasi.
- [x] Menjelaskan cara menjalankan aplikasi.
- [x] Mendokumentasikan fitur.
- [x] Mendokumentasikan struktur database.
- [ ] Menambahkan screenshot aplikasi.
- [ ] Menyiapkan demo proyek.
- [ ] Menyiapkan deployment.

Checkpoint:
- [x] Dokumentasi selesai.
- [ ] Proyek siap dipresentasikan.

---

## PROJECT COMPLETION

- [ ] Seluruh phase selesai.
- [ ] Seluruh checkpoint terpenuhi.
- [ ] Aplikasi siap digunakan.
- [ ] Aplikasi siap dimasukkan ke portofolio.
