
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="page-head">
        <h2>Ringkasan hari ini</h2>
        <p>Pantau koleksi buku, anggota, dan sirkulasi peminjaman.</p>
    </div>

    {{-- STATISTIK --}}
    <section class="stat-strip">
        <div class="stat">
            <span class="stat-label">Total buku</span>
            <span class="stat-value">
                {{ number_format($totalBooks) }}
            </span>
        </div>

        <div class="stat">
            <span class="stat-label">Total anggota</span>
            <span class="stat-value">
                {{ number_format($totalMembers) }}
            </span>
        </div>

        <div class="stat">
            <span class="stat-label">Peminjaman aktif</span>
            <span class="stat-value">
                {{ number_format($activeBorrowings) }}
            </span>
        </div>

        <div class="stat">
            <span class="stat-label">Jumlah pengembalian</span>
            <span class="stat-value">
                {{ number_format($totalReturns) }}
            </span>
        </div>
    </section>

    {{-- TRANSAKSI TERBARU --}}
    <section class="surface">
        <div class="surface-head">
            <h3>Transaksi terbaru</h3>

            <a href="{{ route('history.index') }}"
                class="btn btn-sm btn-outline-brass">
                Lihat riwayat
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($latestTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction['member'] }}</td>

                            <td>{{ $transaction['books'] }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($transaction['date'])->format('d M Y') }}
                            </td>

                            <td>
                                @if ($transaction['status'] === 'borrowed')
                                    <span class="badge-soft ok">
                                        Dipinjam
                                    </span>
                                @elseif ($transaction['status'] === 'overdue')
                                    <span class="badge-soft late">
                                        Terlambat
                                    </span>
                                @elseif ($transaction['status'] === 'late')
                                    <span class="badge-soft late">
                                        Dikembalikan terlambat
                                    </span>
                                @else
                                    <span class="badge-soft done">
                                        Dikembalikan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                Belum ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
