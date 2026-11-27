
@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')

    <div class="page-head">
        <h2>Laporan Perpustakaan</h2>
        <p>Pantau dan lihat laporan transaksi perpustakaan.</p>
    </div>

    {{-- TAB LAPORAN --}}
    <section class="surface">

        <div class="surface-head">
            <h3>Jenis Laporan</h3>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('reports.index', ['type' => 'borrowing']) }}"
                class="btn btn-sm {{ $type === 'borrowing' ? 'btn-outline-brass' : 'btn-outline-secondary' }}">
                Laporan Peminjaman
            </a>

            <a href="{{ route('reports.index', ['type' => 'return']) }}"
                class="btn btn-sm {{ $type === 'return' ? 'btn-outline-brass' : 'btn-outline-secondary' }}">
                Laporan Pengembalian
            </a>

            <a href="{{ route('reports.index', ['type' => 'overdue']) }}"
                class="btn btn-sm {{ $type === 'overdue' ? 'btn-outline-brass' : 'btn-outline-secondary' }}">
                Laporan Keterlambatan
            </a>
        </div>

        {{-- FILTER --}}
        <form action="{{ route('reports.index') }}" method="GET">

            <input type="hidden" name="type" value="{{ $type }}">

            <div class="row g-2 align-items-end">

                <div class="col-md-4">
                    <label class="form-label">Pencarian</label>
                    <input type="text"
                        name="search"
                        class="form-control"
                        value="{{ $search }}"
                        placeholder="Kode transaksi / nama anggota">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Dari tanggal</label>
                    <input type="date"
                        name="start_date"
                        class="form-control"
                        value="{{ $startDate }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sampai tanggal</label>
                    <input type="date"
                        name="end_date"
                        class="form-control"
                        value="{{ $endDate }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Urutan</label>
                    <select name="sort" class="form-select">
                        <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>
                            Terbaru
                        </option>
                        <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>
                            Terlama
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Per halaman</label>
                    <select name="per_page" class="form-select">
                        @foreach ([10, 25, 50] as $number)
                            <option value="{{ $number }}"
                                {{ $perPage == $number ? 'selected' : '' }}>
                                {{ $number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-10 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-brass">
                        Filter
                    </button>

                    <a href="{{ route('reports.index', ['type' => $type]) }}"
                        class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </div>
        </form>

        <hr>

        {{-- LAPORAN PEMINJAMAN --}}
        @if ($type === 'borrowing')

            <div class="surface-head">
                <h3>Laporan Peminjaman</h3>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Peminjaman</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transactions->firstItem() + $loop->index }}</td>
                                <td>{{ $transaction->borrowing_code }}</td>
                                <td>{{ $transaction->member->name }}</td>
                                <td>
                                    @foreach ($transaction->details as $detail)
                                        <div>
                                            {{ $detail->book->title }}
                                            ({{ $detail->quantity }})
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ \Carbon\Carbon::parse($transaction->borrow_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaction->due_date)->format('d M Y') }}</td>
                                <td>
                                    @if ($transaction->status === 'borrowed')
                                        <span class="badge-soft ok">Dipinjam</span>
                                    @elseif ($transaction->status === 'overdue')
                                        <span class="badge-soft late">Terlambat</span>
                                    @else
                                        <span class="badge-soft done">Dikembalikan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    Tidak ada data peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        {{-- LAPORAN PENGEMBALIAN --}}
        @elseif ($type === 'return')

            <div class="surface-head">
                <h3>Laporan Pengembalian</h3>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Peminjaman</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tanggal Kembali</th>
                            <th>Hari Terlambat</th>
                            <th>Denda</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transactions->firstItem() + $loop->index }}</td>
                                <td>{{ $transaction->borrowing->borrowing_code }}</td>
                                <td>{{ $transaction->borrowing->member->name }}</td>
                                <td>
                                    @foreach ($transaction->borrowing->details as $detail)
                                        <div>
                                            {{ $detail->book->title }}
                                            ({{ $detail->quantity }})
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ \Carbon\Carbon::parse($transaction->return_date)->format('d M Y') }}</td>
                                <td>{{ $transaction->late_days }} hari</td>
                                <td>Rp{{ number_format($transaction->fine_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    Tidak ada data pengembalian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        {{-- LAPORAN KETERLAMBATAN --}}
        @else

            <div class="surface-head">
                <h3>Laporan Keterlambatan</h3>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Peminjaman</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Terlambat</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transactions->firstItem() + $loop->index }}</td>
                                <td>{{ $transaction->borrowing_code }}</td>
                                <td>{{ $transaction->member->name }}</td>
                                <td>
                                    @foreach ($transaction->details as $detail)
                                        <div>
                                            {{ $detail->book->title }}
                                            ({{ $detail->quantity }})
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ \Carbon\Carbon::parse($transaction->borrow_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaction->due_date)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge-soft late">
                                        {{ \Carbon\Carbon::parse($transaction->due_date)->diffInDays(today()) }}
                                        hari
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    Tidak ada peminjaman yang terlambat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @endif

        {{-- PAGINATION --}}
        <div class="mt-3">
            {{ $transactions->links() }}
        </div>

    </section>

@endsection
