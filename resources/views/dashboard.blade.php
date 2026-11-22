{{-- resources/views/dashboard.blade.php — isi halaman dashboard --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="page-head">
        <h2>Ringkasan hari ini</h2>
        <p>Pantau koleksi buku, anggota, dan sirkulasi peminjaman.</p>
    </div>

    {{-- Ganti angka dengan data dari controller --}}
    <section class="stat-strip">
        <div class="stat"><span class="stat-label">Total buku</span><span class="stat-value">1.248</span></div>
        <div class="stat"><span class="stat-label">Anggota aktif</span><span class="stat-value">356</span></div>
        <div class="stat"><span class="stat-label">Sedang dipinjam</span><span class="stat-value">87</span></div>
        <div class="stat stat-alert"><span class="stat-label">Terlambat</span><span class="stat-value">9</span></div>
    </section>

    <section class="surface">
        <div class="surface-head">
            <h3>Transaksi terbaru</h3>
            <a href="{{ url('/riwayat') }}" class="btn btn-sm btn-outline-brass">Lihat riwayat</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Anggota</th><th>Buku</th><th>Tanggal</th><th>Status</th></tr></thead>
                <tbody>
                    <tr><td>Rina Maharani</td><td>Laskar Pelangi</td><td>22 Nov 2026</td><td><span class="badge-soft ok">Dipinjam</span></td></tr>
                    <tr><td>Andi Pratama</td><td>Bumi Manusia</td><td>21 Nov 2026</td><td><span class="badge-soft done">Dikembalikan</span></td></tr>
                    <tr><td>Siti Nurhaliza</td><td>Atomic Habits</td><td>14 Nov 2026</td><td><span class="badge-soft late">Terlambat</span></td></tr>
                </tbody>
            </table>
        </div>
    </section>
@endsection
