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

- [ ] Membuat migration categories.
- [ ] Membuat model Category.
- [ ] Membuat CategoryController.
- [ ] Membuat CategoryService.
- [ ] Membuat validasi kategori.
- [ ] Membuat halaman daftar kategori.
- [ ] Membuat halaman tambah kategori.
- [ ] Membuat proses simpan kategori.
- [ ] Membuat halaman edit kategori.
- [ ] Membuat proses update kategori.
- [ ] Membuat proses hapus kategori.
- [ ] Menguji CRUD kategori.

Checkpoint:
- [ ] CRUD kategori berjalan.
- [ ] Validasi kategori berjalan.

---

## PHASE 4 - Book Management

- [ ] Membuat migration books.
- [ ] Membuat model Book.
- [ ] Membuat relasi Book dan Category.
- [ ] Membuat BookController.
- [ ] Membuat BookService.
- [ ] Membuat validasi buku.
- [ ] Membuat halaman daftar buku.
- [ ] Membuat halaman tambah buku.
- [ ] Membuat proses simpan buku.
- [ ] Membuat halaman edit buku.
- [ ] Membuat proses update buku.
- [ ] Membuat proses hapus buku.
- [ ] Membuat pencarian buku.
- [ ] Membuat filter kategori.
- [ ] Membuat pagination.
- [ ] Menguji CRUD buku.

Checkpoint:
- [ ] CRUD buku berjalan.
- [ ] Relasi kategori berjalan.
- [ ] Pencarian dan filter berjalan.

---

## PHASE 5 - Member Management

- [ ] Membuat migration members.
- [ ] Membuat model Member.
- [ ] Membuat MemberController.
- [ ] Membuat MemberService.
- [ ] Membuat validasi anggota.
- [ ] Membuat halaman daftar anggota.
- [ ] Membuat halaman tambah anggota.
- [ ] Membuat proses simpan anggota.
- [ ] Membuat halaman edit anggota.
- [ ] Membuat proses update anggota.
- [ ] Membuat proses hapus anggota.
- [ ] Membuat pencarian anggota.
- [ ] Menguji CRUD anggota.

Checkpoint:
- [ ] CRUD anggota berjalan.
- [ ] Validasi anggota berjalan.

---

## PHASE 6 - Borrowing Management

- [ ] Membuat migration borrowings.
- [ ] Membuat migration borrowing_details.
- [ ] Membuat model Borrowing.
- [ ] Membuat model BorrowingDetail.
- [ ] Membuat relasi peminjaman dan anggota.
- [ ] Membuat relasi peminjaman dan buku.
- [ ] Membuat BorrowingController.
- [ ] Membuat BorrowingService.
- [ ] Membuat validasi peminjaman.
- [ ] Memeriksa status anggota.
- [ ] Memeriksa ketersediaan stok.
- [ ] Memeriksa batas maksimal peminjaman.
- [ ] Menentukan tanggal jatuh tempo.
- [ ] Menyimpan transaksi peminjaman.
- [ ] Mengurangi stok buku.
- [ ] Menggunakan database transaction.
- [ ] Menguji proses peminjaman.

Checkpoint:
- [ ] Peminjaman berhasil.
- [ ] Stok berkurang secara otomatis.
- [ ] Anggota tidak aktif tidak dapat meminjam.
- [ ] Buku tanpa stok tidak dapat dipinjam.
- [ ] Batas maksimal peminjaman berjalan.
- [ ] Transaksi aman dari perubahan parsial.

---

## PHASE 7 - Return Management

- [ ] Membuat migration returns.
- [ ] Membuat model Return.
- [ ] Membuat ReturnController.
- [ ] Membuat ReturnService.
- [ ] Membuat validasi pengembalian.
- [ ] Mencari transaksi peminjaman.
- [ ] Memeriksa status peminjaman.
- [ ] Mencatat tanggal pengembalian.
- [ ] Menghitung keterlambatan.
- [ ] Menghitung denda.
- [ ] Mengembalikan stok buku.
- [ ] Memperbarui status transaksi.
- [ ] Menggunakan database transaction.
- [ ] Menguji proses pengembalian.

Checkpoint:
- [ ] Pengembalian berhasil.
- [ ] Stok bertambah secara otomatis.
- [ ] Denda dihitung dengan benar.
- [ ] Pengembalian tidak dapat diproses dua kali.

---

## PHASE 8 - Transaction History

- [ ] Membuat halaman riwayat peminjaman.
- [ ] Membuat halaman riwayat pengembalian.
- [ ] Menampilkan status transaksi.
- [ ] Membuat pencarian transaksi.
- [ ] Membuat filter berdasarkan tanggal.
- [ ] Membuat pagination.
- [ ] Menguji riwayat transaksi.

Checkpoint:
- [ ] Riwayat transaksi dapat ditampilkan.
- [ ] Filter dan pencarian berjalan.

---

## PHASE 9 - Dashboard & Reports

- [ ] Membuat tampilan dashboard.
- [ ] Menampilkan total buku.
- [ ] Menampilkan total anggota.
- [ ] Menampilkan peminjaman aktif.
- [ ] Menampilkan jumlah pengembalian.
- [ ] Menampilkan transaksi terbaru.
- [ ] Membuat laporan peminjaman.
- [ ] Membuat laporan pengembalian.
- [ ] Membuat laporan keterlambatan.

Checkpoint:
- [ ] Dashboard menampilkan data aktual.
- [ ] Laporan dapat ditampilkan.

---

## PHASE 10 - Testing & Quality

- [ ] Menguji authentication.
- [ ] Menguji authorization.
- [ ] Menguji CRUD kategori.
- [ ] Menguji CRUD buku.
- [ ] Menguji CRUD anggota.
- [ ] Menguji peminjaman.
- [ ] Menguji pengembalian.
- [ ] Menguji validasi input.
- [ ] Menguji business rules.
- [ ] Menguji database transaction.
- [ ] Memeriksa error handling.
- [ ] Memeriksa keamanan aplikasi.

Checkpoint:
- [ ] Seluruh fitur utama berjalan.
- [ ] Tidak ada bug kritis.
- [ ] Business rules berjalan sesuai PRD.

---

## PHASE 11 - Documentation & Deployment

- [ ] Membuat README.md.
- [ ] Menjelaskan kebutuhan instalasi.
- [ ] Menjelaskan cara menjalankan aplikasi.
- [ ] Mendokumentasikan fitur.
- [ ] Mendokumentasikan struktur database.
- [ ] Menambahkan screenshot aplikasi.
- [ ] Menyiapkan demo proyek.
- [ ] Menyiapkan deployment.

Checkpoint:
- [ ] Dokumentasi selesai.
- [ ] Proyek siap dipresentasikan.

---

## PROJECT COMPLETION

- [ ] Seluruh phase selesai.
- [ ] Seluruh checkpoint terpenuhi.
- [ ] Aplikasi siap digunakan.
- [ ] Aplikasi siap dimasukkan ke portofolio.
