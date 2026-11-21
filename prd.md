# Product Requirements Document (PRD)
# Library Management System

## 1. Project Overview

### 1.1 Deskripsi Aplikasi

Library Management System adalah aplikasi berbasis web untuk membantu pengelolaan operasional perpustakaan.

Aplikasi memungkinkan admin dan petugas mengelola data buku, kategori, anggota, peminjaman, pengembalian, dan laporan secara terpusat.

### 1.2 Tujuan

- Mempermudah pengelolaan data perpustakaan.
- Mengelola transaksi peminjaman dan pengembalian.
- Memantau ketersediaan stok buku.
- Mengurangi kesalahan pencatatan manual.
- Menyediakan riwayat transaksi perpustakaan.

### 1.3 Target Pengguna

- Admin
- Petugas perpustakaan

---

## 2. User Roles & Permissions

### 2.1 Admin

Admin memiliki akses penuh terhadap sistem.

Hak akses:
- Mengelola akun pengguna.
- Mengelola kategori buku.
- Mengelola data buku.
- Mengelola data anggota.
- Mengelola transaksi peminjaman.
- Mengelola transaksi pengembalian.
- Melihat laporan.
- Melihat dashboard.

### 2.2 Petugas

Petugas bertanggung jawab terhadap operasional perpustakaan.

Hak akses:
- Melihat dashboard.
- Mengelola data buku.
- Mengelola data kategori.
- Mengelola data anggota.
- Memproses peminjaman.
- Memproses pengembalian.
- Melihat riwayat transaksi.

---

## 3. Functional Requirements

### 3.1 Authentication & Authorization

- Login.
- Logout.
- Role-based access control.
- Pembatasan akses berdasarkan role.

### 3.2 Dashboard

- Menampilkan total buku.
- Menampilkan total anggota.
- Menampilkan peminjaman aktif.
- Menampilkan jumlah pengembalian.
- Menampilkan transaksi terbaru.

### 3.3 Category Management

- Menambahkan kategori.
- Melihat daftar kategori.
- Mengubah kategori.
- Menghapus kategori.

### 3.4 Book Management

- Menambahkan buku.
- Melihat daftar buku.
- Mengubah informasi buku.
- Menghapus buku.
- Mencari buku.
- Filter berdasarkan kategori.
- Pagination.
- Mengelola stok buku.

### 3.5 Member Management

- Menambahkan anggota.
- Melihat daftar anggota.
- Mengubah data anggota.
- Menghapus anggota.
- Mencari anggota.

### 3.6 Borrowing Management

- Memilih anggota.
- Memilih buku.
- Memvalidasi status anggota.
- Memvalidasi ketersediaan stok.
- Memvalidasi batas maksimal peminjaman.
- Menentukan tanggal peminjaman.
- Menentukan tanggal jatuh tempo.
- Menyimpan transaksi.
- Mengurangi stok buku.

### 3.7 Return Management

- Mencari transaksi peminjaman.
- Memvalidasi status transaksi.
- Mencatat tanggal pengembalian.
- Menghitung keterlambatan.
- Menghitung denda.
- Mengembalikan stok buku.
- Memperbarui status transaksi.

### 3.8 Transaction History

- Melihat riwayat peminjaman.
- Melihat riwayat pengembalian.
- Melihat status transaksi.
- Mencari transaksi berdasarkan anggota.

### 3.9 Reports

- Laporan buku.
- Laporan anggota.
- Laporan peminjaman.
- Laporan pengembalian.
- Laporan keterlambatan.

---

## 4. Business Rules

| Aturan | Ketentuan |
|---|---|
| Maksimal peminjaman | 3 buku per anggota |
| Lama peminjaman | 7 hari |
| Stok minimum | 0 |
| Stok kosong | Tidak dapat dipinjam |
| Anggota tidak aktif | Tidak dapat meminjam |
| Anggota terlambat | Tidak dapat meminjam lagi |
| Pengembalian | Tidak boleh diproses dua kali |

### Borrowing Rules

1. Anggota harus terdaftar dan aktif.
2. Jumlah buku yang dipinjam tidak boleh melebihi batas.
3. Buku harus memiliki stok tersedia.
4. Transaksi dan pengurangan stok harus berhasil secara bersamaan.
5. Jika proses gagal, perubahan stok harus dibatalkan.

### Return Rules

1. Transaksi harus berstatus dipinjam.
2. Buku tidak boleh dikembalikan dua kali.
3. Sistem mencatat tanggal pengembalian.
4. Jika melewati tanggal jatuh tempo, sistem menghitung denda.
5. Stok buku dikembalikan setelah pengembalian berhasil.

---

## 5. Non-Functional Requirements

- Validasi input pada setiap proses.
- Password disimpan menggunakan hashing.
- Akses halaman dilindungi middleware.
- Proses transaksi menggunakan database transaction.
- Mencegah stok buku menjadi negatif.
- Menggunakan pagination untuk data berjumlah besar.
- Struktur kode mudah dipelihara.
- Menangani error dengan response yang sesuai.

---

## 6. Tech Stack

| Kategori | Teknologi |
|---|---|
| Backend | Laravel 12 |
| Language | PHP 8.2+ |
| Database | MySQL |
| Frontend | Laravel Blade |
| CSS | Bootstrap / Tailwind CSS |
| Authentication | Laravel Breeze |
| Authorization | Middleware / Policy |
| Architecture | Monolithic |
| Code Pattern | Service Layer |
| API | Laravel REST API |
| Testing | PHPUnit / Pest |
| API Testing | Postman |
| Version Control | Git & GitHub |

---

## 7. System Architecture

Aplikasi menggunakan Monolithic Architecture dengan pendekatan Service Layer.

### Request Flow

Browser
  ↓
Route
  ↓
Middleware
  ↓
Controller
  ↓
Service
  ↓
Model / Eloquent
  ↓
MySQL

### Component Responsibilities

| Komponen | Tanggung Jawab |
|---|---|
| Route | Mengatur endpoint |
| Middleware | Autentikasi dan otorisasi |
| Controller | Mengelola request dan response |
| Form Request | Validasi input |
| Service | Business logic |
| Model | Interaksi database |
| Database | Penyimpanan data |
| Blade | Antarmuka pengguna |

---

## 8. Database Entities

Rencana entitas:

- users
- roles
- members
- categories
- books
- borrowings
- borrowing_details
- returns

Relasi database akan ditentukan lebih detail pada tahap database design.

---

## 9. Out of Scope

Fitur yang tidak termasuk dalam versi awal:

- Pembayaran online.
- Aplikasi mobile.
- Integrasi WhatsApp.
- Integrasi RFID.
- Integrasi barcode scanner.
- Multi-branch library.
- Notifikasi SMS.

---

## 10. Project Acceptance Criteria

Aplikasi dianggap memenuhi kebutuhan awal apabila:

- Admin dan petugas dapat login.
- Hak akses setiap role berjalan.
- CRUD buku dan kategori berjalan.
- CRUD anggota berjalan.
- Peminjaman berhasil mengurangi stok.
- Pengembalian berhasil menambah stok.
- Validasi business rules berjalan.
- Riwayat transaksi tersimpan.
- Laporan dapat ditampilkan.
- Tidak terjadi stok negatif.
- Proses transaksi aman dari perubahan data parsial.

---

## 11. Project Status

Status: Planning

Dokumen ini menjadi acuan utama pengembangan Library Management System.
