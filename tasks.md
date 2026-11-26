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
