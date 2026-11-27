@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')

@push('styles')
    <style>
        .history-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .history-tabs {
            display: flex;
            gap: .35rem;
            margin-bottom: 1rem;
            padding: .35rem;
            background: rgba(10, 17, 40, .55);
            border: 1px solid var(--line);
            border-radius: 12px;
            width: fit-content;
        }

        .history-tabs a {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .55rem .8rem;
            border-radius: 8px;
            color: var(--muted);
            text-decoration: none;
            font-size: .88rem;
            font-weight: 600;
        }

        .history-tabs a:hover,
        .history-tabs a.active {
            background: var(--navy-600);
            color: #fff;
        }

        .history-filter {
            display: grid;
            grid-template-columns: minmax(220px, 1fr) repeat(4, auto);
            gap: .65rem;
            align-items: end;
            margin-bottom: 1rem;
        }

        .history-filter .search-input { min-width: 220px; }

        .history-filter label {
            color: var(--muted);
            font-size: .75rem;
            margin-bottom: .3rem;
        }

        .history-table th,
        .history-table td {
            vertical-align: middle;
        }

        .history-table td { white-space: normal; }

        .transaction-code {
            color: var(--brass);
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: .82rem;
            font-weight: 700;
        }

        .member-name,
        .book-name {
            color: var(--text);
            font-weight: 600;
        }

        .muted-line {
            color: var(--muted);
            font-size: .78rem;
        }

        .history-empty {
            min-height: 240px;
            display: grid;
            place-items: center;
            text-align: center;
            color: var(--muted);
        }

        .history-empty i {
            display: block;
            font-size: 2.2rem;
            margin-bottom: .5rem;
            color: var(--navy-600);
        }

        .history-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .summary-card {
            background: var(--navy-800);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 1rem;
        }

        .summary-card span {
            display: block;
            color: var(--muted);
            font-size: .78rem;
            margin-bottom: .25rem;
        }

        .summary-card strong {
            font-size: 1.35rem;
        }

        .summary-card.danger strong { color: #ff9a9a; }
        .summary-card.success strong { color: #7be0a2; }

        .history-detail dt {
            color: var(--muted);
            font-size: .78rem;
            font-weight: 500;
        }

        .history-detail dd {
            margin-bottom: .75rem;
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            .history-filter {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .history-filter .search-input {
                grid-column: 1 / -1;
                min-width: 0;
            }
        }

        @media (max-width: 575.98px) {
            .history-filter,
            .history-summary {
                grid-template-columns: 1fr;
            }

            .history-head {
                flex-direction: column;
            }

            .history-tabs {
                width: 100%;
                overflow-x: auto;
            }

            .history-tabs a {
                white-space: nowrap;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $visibleLateCount = 0;
        $visibleFine = 0;
        foreach ($transactions as $transaction) {
            if ($type === 'borrowing') {
                $lateDays = $transaction->status === 'overdue'
                    ? max(0, (int) now()->startOfDay()->diffInDays($transaction->due_date->startOfDay(), false))
                    : 0;
                if ($lateDays > 0) {
                    $visibleLateCount++;
                }
                $visibleFine += (float) ($transaction->returnRecord?->fine_amount ?? 0);
            } else {
                if ((int) $transaction->late_days > 0) {
                    $visibleLateCount++;
                }
                $visibleFine += (float) $transaction->fine_amount;
            }
        }
    @endphp

    <div class="page-head history-head mb-3">
        <div>
            <h2>Riwayat Transaksi</h2>
            <p>Pantau riwayat peminjaman dan pengembalian buku.</p>
        </div>
    </div>

    <div class="history-tabs" role="tablist" aria-label="Jenis riwayat">
        <a href="{{ route('history.index', array_filter(['type' => 'borrowing', 'search' => $search, 'status' => $status, 'start_date' => $startDate, 'end_date' => $endDate, 'sort' => $sort, 'per_page' => $perPage])) }}"
            class="{{ $type === 'borrowing' ? 'active' : '' }}">
            <i class="bi bi-journal-arrow-up"></i>
            Peminjaman
        </a>
        <a href="{{ route('history.index', array_filter(['type' => 'return', 'search' => $search, 'status' => $status, 'start_date' => $startDate, 'end_date' => $endDate, 'sort' => $sort, 'per_page' => $perPage])) }}"
            class="{{ $type === 'return' ? 'active' : '' }}">
            <i class="bi bi-journal-arrow-down"></i>
            Pengembalian
        </a>
    </div>

    <div class="history-summary">
        <div class="summary-card">
            <span>{{ $type === 'borrowing' ? 'Total peminjaman' : 'Total pengembalian' }}</span>
            <strong>{{ $transactions->total() }}</strong>
        </div>
        <div class="summary-card danger">
            <span>Terlambat pada halaman ini</span>
            <strong>{{ $visibleLateCount }}</strong>
        </div>
        <div class="summary-card success">
            <span>Denda pada halaman ini</span>
            <strong>Rp {{ number_format($visibleFine, 0, ',', '.') }}</strong>
        </div>
    </div>

    <section class="surface">
        <form method="GET" action="{{ route('history.index') }}" class="history-filter">
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="search-input">
                <label for="historySearch">Pencarian</label>
                <div class="input-group">
                    <input type="search" id="historySearch" name="search" value="{{ $search }}" class="form-control"
                        placeholder="Cari kode atau nama member">
                    <button type="submit" class="btn btn-outline-brass" aria-label="Cari">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <div>
                <label for="historyStatus">Status</label>
                <select id="historyStatus" name="status" class="form-select">
                    <option value="">Semua status</option>
                    @if ($type === 'borrowing')
                        <option value="borrowed" @selected($status === 'borrowed')>Dipinjam</option>
                        <option value="overdue" @selected($status === 'overdue')>Terlambat</option>
                        <option value="returned" @selected($status === 'returned')>Dikembalikan</option>
                    @else
                        <option value="late" @selected($status === 'late')>Terlambat</option>
                        <option value="on_time" @selected($status === 'on_time')>Tepat waktu</option>
                    @endif
                </select>
            </div>

            <div>
                <label for="historyStart">Tanggal mulai</label>
                <input id="historyStart" type="date" name="start_date" value="{{ $startDate }}" class="form-control">
            </div>

            <div>
                <label for="historyEnd">Tanggal selesai</label>
                <input id="historyEnd" type="date" name="end_date" value="{{ $endDate }}" class="form-control">
            </div>

            <div>
                <label for="historySort">Urutan</label>
                <select id="historySort" name="sort" class="form-select">
                    <option value="desc" @selected($sort === 'desc')>Terbaru</option>
                    <option value="asc" @selected($sort === 'asc')>Terlama</option>
                </select>
            </div>

            <div>
                <label for="historyPerPage">Per halaman</label>
                <select id="historyPerPage" name="per_page" class="form-select">
                    @foreach ([10, 25, 50] as $size)
                        <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-outline-brass">Terapkan</button>
                @if ($search || $status || $startDate || $endDate || $sort !== 'desc' || $perPage !== 10)
                    <a href="{{ route('history.index', ['type' => $type]) }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table history-table align-middle mb-0">
                @if ($type === 'borrowing')
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Tanggal pinjam</th>
                            <th>Jatuh tempo</th>
                            <th>Buku</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $borrowing)
                            @php
                                $lateDays = $borrowing->status === 'overdue'
                                    ? max(0, (int) $borrowing->due_date->startOfDay()->diffInDays(now()->startOfDay(), false))
                                    : 0;
                                $statusClass = $borrowing->status === 'returned'
                                    ? 'done'
                                    : ($borrowing->status === 'overdue' ? 'late' : 'ok');
                                $statusLabel = [
                                    'borrowed' => 'Dipinjam',
                                    'overdue' => 'Terlambat',
                                    'returned' => 'Dikembalikan',
                                ][$borrowing->status] ?? ucfirst($borrowing->status);
                            @endphp
                            <tr>
                                <td><span class="transaction-code">{{ $borrowing->borrowing_code }}</span></td>
                                <td>
                                    <div class="member-name">{{ $borrowing->member->name }}</div>
                                    <div class="muted-line">{{ $borrowing->member->member_code }}</div>
                                </td>
                                <td>{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                <td>
                                    {{ $borrowing->due_date->format('d M Y') }}
                                    @if ($lateDays > 0)
                                        <div class="text-danger small">Terlambat {{ $lateDays }} hari</div>
                                    @endif
                                </td>
                                <td>
                                    @foreach ($borrowing->details as $detail)
                                        <div class="book-name">{{ $detail->book->title }}</div>
                                    @endforeach
                                </td>
                                <td><span class="badge-soft {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-brass"
                                        data-bs-toggle="modal" data-bs-target="#borrowingDetail{{ $borrowing->id }}"
                                        aria-label="Lihat detail {{ $borrowing->borrowing_code }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="history-empty">
                                        <div><i class="bi bi-journal-x"></i>Belum ada riwayat peminjaman.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                @else
                    <thead>
                        <tr>
                            <th>Kode peminjaman</th>
                            <th>Anggota</th>
                            <th>Tanggal kembali</th>
                            <th>Keterlambatan</th>
                            <th>Denda</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $return)
                            @php
                                $borrowing = $return->borrowing;
                            @endphp
                            <tr>
                                <td><span class="transaction-code">{{ $borrowing->borrowing_code }}</span></td>
                                <td>
                                    <div class="member-name">{{ $borrowing->member->name }}</div>
                                    <div class="muted-line">{{ $borrowing->member->member_code }}</div>
                                </td>
                                <td>{{ $return->return_date->format('d M Y') }}</td>
                                <td>
                                    @if ((int) $return->late_days > 0)
                                        <span class="badge-soft late">{{ $return->late_days }} hari</span>
                                    @else
                                        <span class="badge-soft done">Tepat waktu</span>
                                    @endif
                                </td>
                                <td>
                                    @if ((float) $return->fine_amount > 0)
                                        <span class="text-danger fw-semibold">Rp {{ number_format($return->fine_amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-brass"
                                        data-bs-toggle="modal" data-bs-target="#returnDetail{{ $return->id }}"
                                        aria-label="Lihat detail {{ $borrowing->borrowing_code }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="history-empty">
                                        <div><i class="bi bi-journal-x"></i>Belum ada riwayat pengembalian.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                @endif
            </table>
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
            <span class="small text-secondary">
                Menampilkan {{ $transactions->firstItem() ?? 0 }}–{{ $transactions->lastItem() ?? 0 }}
                dari {{ $transactions->total() }} transaksi
            </span>
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    </section>

    @if ($type === 'borrowing')
        @foreach ($transactions as $borrowing)
            <div class="modal fade" id="borrowingDetail{{ $borrowing->id }}" tabindex="-1"
                aria-labelledby="borrowingDetailTitle{{ $borrowing->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="borrowingDetailTitle{{ $borrowing->id }}">Detail Peminjaman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body history-detail">
                            <dl class="row">
                                <dt class="col-sm-4">Kode transaksi</dt>
                                <dd class="col-sm-8 transaction-code">{{ $borrowing->borrowing_code }}</dd>
                                <dt class="col-sm-4">Member</dt>
                                <dd class="col-sm-8">{{ $borrowing->member->name }} ({{ $borrowing->member->member_code }})</dd>
                                <dt class="col-sm-4">Tanggal pinjam</dt>
                                <dd class="col-sm-8">{{ $borrowing->borrow_date->format('d M Y') }}</dd>
                                <dt class="col-sm-4">Jatuh tempo</dt>
                                <dd class="col-sm-8">{{ $borrowing->due_date->format('d M Y') }}</dd>
                                <dt class="col-sm-4">Petugas</dt>
                                <dd class="col-sm-8">{{ $borrowing->processedBy->name ?? '-' }}</dd>
                                <dt class="col-sm-4">Catatan</dt>
                                <dd class="col-sm-8">{{ $borrowing->notes ?: '-' }}</dd>
                            </dl>
                            <h6 class="mb-2">Daftar buku</h6>
                            @foreach ($borrowing->details as $detail)
                                <div class="d-flex justify-content-between border-top border-secondary py-2">
                                    <span>{{ $detail->book->title }} <small class="text-secondary">— {{ $detail->book->author }}</small></span>
                                    <span class="badge-soft ok">{{ $detail->quantity }} eksemplar</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        @foreach ($transactions as $return)
            @php $borrowing = $return->borrowing; @endphp
            <div class="modal fade" id="returnDetail{{ $return->id }}" tabindex="-1"
                aria-labelledby="returnDetailTitle{{ $return->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="returnDetailTitle{{ $return->id }}">Detail Pengembalian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body history-detail">
                            <dl class="row">
                                <dt class="col-sm-4">Kode peminjaman</dt>
                                <dd class="col-sm-8 transaction-code">{{ $borrowing->borrowing_code }}</dd>
                                <dt class="col-sm-4">Member</dt>
                                <dd class="col-sm-8">{{ $borrowing->member->name }} ({{ $borrowing->member->member_code }})</dd>
                                <dt class="col-sm-4">Tanggal pinjam</dt>
                                <dd class="col-sm-8">{{ $borrowing->borrow_date->format('d M Y') }}</dd>
                                <dt class="col-sm-4">Tanggal kembali</dt>
                                <dd class="col-sm-8">{{ $return->return_date->format('d M Y') }}</dd>
                                <dt class="col-sm-4">Keterlambatan</dt>
                                <dd class="col-sm-8">{{ (int) $return->late_days > 0 ? $return->late_days.' hari' : 'Tepat waktu' }}</dd>
                                <dt class="col-sm-4">Denda</dt>
                                <dd class="col-sm-8">Rp {{ number_format($return->fine_amount, 0, ',', '.') }}</dd>
                                <dt class="col-sm-4">Petugas</dt>
                                <dd class="col-sm-8">{{ $return->processedBy->name ?? '-' }}</dd>
                            </dl>
                            <h6 class="mb-2">Daftar buku dikembalikan</h6>
                            @foreach ($borrowing->details as $detail)
                                <div class="d-flex justify-content-between border-top border-secondary py-2">
                                    <span>{{ $detail->book->title }}</span>
                                    <span class="badge-soft ok">{{ $detail->quantity }} eksemplar</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endsection
