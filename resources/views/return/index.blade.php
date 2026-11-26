@extends('layouts.app')

@section('title', isset($return) ? 'Detail Pengembalian' : 'Pengembalian')
@section('page-title', isset($return) ? 'Detail Pengembalian' : 'Pengembalian')

@push('styles')
    <style>
        .return-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: start;
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .return-toolbar .input-group {
            max-width: 24rem;
        }

        .return-table th,
        .return-table td {
            vertical-align: middle;
        }

        .return-book-list {
            display: grid;
            gap: .65rem;
        }

        .return-book-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: .8rem 1rem;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--navy-900);
        }

        .return-detail-grid,
        .return-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .return-detail-item {
            min-width: 0;
            padding: .9rem 1rem;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--navy-900);
        }

        .return-detail-item.full {
            grid-column: 1 / -1;
        }

        .return-detail-label {
            display: block;
            margin-bottom: .25rem;
            color: var(--muted);
            font-size: .82rem;
        }

        .return-modal .modal-content {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--navy-800);
        }

        .return-modal .modal-header,
        .return-modal .modal-footer {
            border-color: var(--line);
        }

        .return-modal .modal-dialog {
            max-width: 720px;
        }

        .return-modal .form-label {
            color: var(--muted);
            font-size: .86rem;
            font-weight: 500;
        }

        @media (max-width: 767.98px) {
            .return-toolbar .input-group {
                max-width: none;
                width: 100%;
            }
        }

        @media (max-width: 575.98px) {
            .return-toolbar,
            .return-detail-grid,
            .return-form-grid {
                grid-template-columns: 1fr;
            }

            .return-toolbar {
                display: grid;
            }

            .return-detail-item.full {
                grid-column: auto;
            }
        }
    </style>
@endpush

@section('content')
    @if (isset($return))
        <div class="page-head mb-3">
            <h2>Detail Pengembalian</h2>
            <p>Informasi transaksi pengembalian buku.</p>
            <a href="{{ route('return.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Pengembalian
            </a>
        </div>

        <section class="surface">
            <div class="surface-head">
                <h3>{{ $return->borrowing->borrowing_code }}</h3>
                @if ($return->late_days > 0)
                    <span class="badge-soft late">Terlambat</span>
                @else
                    <span class="badge-soft done">Tepat waktu</span>
                @endif
            </div>

            <div class="return-detail-grid mb-4">
                <div class="return-detail-item">
                    <span class="return-detail-label">Anggota</span>
                    <strong>{{ $return->borrowing->member->name }}</strong>
                    <div class="small text-secondary">{{ $return->borrowing->member->member_code }}</div>
                </div>
                <div class="return-detail-item">
                    <span class="return-detail-label">Petugas pengembalian</span>
                    <strong>{{ $return->processedBy->name }}</strong>
                </div>
                <div class="return-detail-item">
                    <span class="return-detail-label">Tanggal pinjam</span>
                    <strong>{{ $return->borrowing->borrow_date->format('d M Y') }}</strong>
                </div>
                <div class="return-detail-item">
                    <span class="return-detail-label">Jatuh tempo</span>
                    <strong>{{ $return->borrowing->due_date->format('d M Y') }}</strong>
                </div>
                <div class="return-detail-item">
                    <span class="return-detail-label">Tanggal pengembalian</span>
                    <strong>{{ $return->return_date->format('d M Y') }}</strong>
                </div>
                <div class="return-detail-item">
                    <span class="return-detail-label">Keterlambatan</span>
                    <strong>{{ $return->late_days }} hari</strong>
                </div>
                <div class="return-detail-item">
                    <span class="return-detail-label">Denda</span>
                    <strong>Rp {{ number_format($return->fine_amount, 0, ',', '.') }}</strong>
                </div>
                <div class="return-detail-item {{ $return->notes ? 'full' : '' }}">
                    <span class="return-detail-label">Catatan</span>
                    <strong>{{ $return->notes ?: 'Tidak ada catatan.' }}</strong>
                </div>
            </div>

            <h3 class="h6 mb-3">Buku yang dikembalikan</h3>
            <div class="return-book-list">
                @foreach ($return->borrowing->details as $detail)
                    <div class="return-book-item">
                        <div>
                            <strong>{{ $detail->book->title }}</strong>
                            <div class="small text-secondary">{{ $detail->book->author }}</div>
                        </div>
                        <span class="badge-soft ok">{{ $detail->quantity }} buku</span>
                    </div>
                @endforeach
            </div>
        </section>
    @else
        <div class="page-head mb-3">
            <h2>Manajemen Pengembalian</h2>
            <p>Proses pengembalian buku dan lihat riwayat transaksi pengembalian.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="stat-strip">
            <div class="stat">
                <span class="stat-label">Menunggu pengembalian</span>
                <span class="stat-value">{{ $borrowings->total() }}</span>
            </div>
            <div class="stat stat-alert">
                <span class="stat-label">Riwayat sesuai filter</span>
                <span class="stat-value">{{ $returns->total() }}</span>
            </div>
        </div>

        <section class="surface mb-4">
            <div class="surface-head">
                <h3>Peminjaman yang Belum Dikembalikan</h3>
            </div>

            <div class="table-responsive">
                <table class="table return-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tanggal pinjam / jatuh tempo</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($borrowings as $borrowing)
                            @php
                                $lateDays = max(
                                    0,
                                    $borrowing->due_date->copy()->startOfDay()->diffInDays(today(), false)
                                );
                            @endphp
                            <tr>
                                <td><strong>{{ $borrowing->borrowing_code }}</strong></td>
                                <td>
                                    <div>{{ $borrowing->member->name }}</div>
                                    <small class="text-secondary">{{ $borrowing->member->member_code }}</small>
                                </td>
                                <td>
                                    @foreach ($borrowing->details as $detail)
                                        <div>{{ $detail->book->title }} <span class="text-secondary">×{{ $detail->quantity }}</span></div>
                                    @endforeach
                                </td>
                                <td>
                                    <div>{{ $borrowing->borrow_date->format('d M Y') }}</div>
                                    <small class="{{ $lateDays > 0 ? 'text-danger' : 'text-secondary' }}">
                                        Jatuh tempo {{ $borrowing->due_date->format('d M Y') }}
                                        @if ($lateDays > 0)
                                            · terlambat {{ $lateDays }} hari
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    @if ($lateDays > 0 || $borrowing->status === 'overdue')
                                        <span class="badge-soft late">Terlambat</span>
                                    @else
                                        <span class="badge-soft ok">Dipinjam</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal" data-bs-target="#returnModal"
                                        data-borrowing-id="{{ $borrowing->id }}"
                                        data-code="{{ $borrowing->borrowing_code }}"
                                        data-member="{{ $borrowing->member->name }}"
                                        data-member-code="{{ $borrowing->member->member_code }}"
                                        data-borrow-date="{{ $borrowing->borrow_date->format('d M Y') }}"
                                        data-due-date="{{ $borrowing->due_date->format('d M Y') }}"
                                        data-late-days="{{ $lateDays }}"
                                        data-books="{{ $borrowing->details->map(fn ($detail) => [
                                            'title' => $detail->book->title,
                                            'quantity' => $detail->quantity,
                                        ])->values()->toJson() }}">
                                        <i class="bi bi-box-arrow-in-down"></i>
                                        Proses
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-secondary">
                                    Tidak ada peminjaman yang menunggu pengembalian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($borrowings->hasPages())
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
                    <span class="small text-secondary">
                        Menampilkan {{ $borrowings->firstItem() }}–{{ $borrowings->lastItem() }}
                        dari {{ $borrowings->total() }} peminjaman
                    </span>
                    {{ $borrowings->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </section>

        <section class="surface">
            <div class="surface-head">
                <h3>Riwayat Pengembalian</h3>
            </div>

            <form method="GET" action="{{ route('return.index') }}" class="return-toolbar">
                <div class="input-group">
                    <input type="search" name="search" value="{{ $search }}" class="form-control"
                        placeholder="Cari kode pinjam atau anggota" aria-label="Cari riwayat pengembalian">
                    <button type="submit" class="btn btn-outline-brass">
                        <i class="bi bi-search"></i>
                        Cari
                    </button>
                </div>

                <select class="form-select" name="status" aria-label="Filter ketepatan pengembalian">
                    <option value="">Semua status</option>
                    <option value="late" @selected($status === 'late')>Terlambat</option>
                    <option value="on_time" @selected($status === 'on_time')>Tepat waktu</option>
                </select>

                <select class="form-select" name="sort" aria-label="Urutkan tanggal pengembalian">
                    <option value="desc" @selected($sort === 'desc')>Tanggal terbaru</option>
                    <option value="asc" @selected($sort === 'asc')>Tanggal terlama</option>
                </select>

                <select class="form-select" name="per_page" aria-label="Jumlah data per halaman">
                    @foreach ([10, 25, 50] as $pageSize)
                        <option value="{{ $pageSize }}" @selected((int) $perPage === $pageSize)>
                            {{ $pageSize }} per halaman
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-outline-secondary">Terapkan</button>
                @if ($search || $status || $sort !== 'desc' || (int) $perPage !== 10)
                    <a href="{{ route('return.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table return-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Tanggal pengembalian</th>
                            <th>Keterlambatan</th>
                            <th>Denda</th>
                            <th>Petugas</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $returnRecord)
                            <tr>
                                <td><strong>{{ $returnRecord->borrowing->borrowing_code }}</strong></td>
                                <td>
                                    <div>{{ $returnRecord->borrowing->member->name }}</div>
                                    <small class="text-secondary">{{ $returnRecord->borrowing->member->member_code }}</small>
                                </td>
                                <td>{{ $returnRecord->return_date->format('d M Y') }}</td>
                                <td>
                                    @if ($returnRecord->late_days > 0)
                                        <span class="badge-soft late">{{ $returnRecord->late_days }} hari</span>
                                    @else
                                        <span class="badge-soft done">Tepat waktu</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($returnRecord->fine_amount, 0, ',', '.') }}</td>
                                <td>{{ $returnRecord->processedBy->name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('return.show', $returnRecord) }}"
                                        class="btn btn-sm btn-outline-brass" aria-label="Lihat detail pengembalian">
                                        <i class="bi bi-eye"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-secondary">
                                    {{ $search || $status ? 'Riwayat pengembalian tidak ditemukan.' : 'Belum ada riwayat pengembalian.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($returns->hasPages())
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
                    <span class="small text-secondary">
                        Menampilkan {{ $returns->firstItem() }}–{{ $returns->lastItem() }}
                        dari {{ $returns->total() }} pengembalian
                    </span>
                    {{ $returns->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </section>

        <div class="modal fade return-modal" id="returnModal" tabindex="-1"
            aria-labelledby="returnModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <form class="modal-content" action="{{ route('return.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="borrowing_id" id="returnBorrowingId">

                    <div class="modal-header">
                        <h5 class="modal-title" id="returnModalTitle">Proses Pengembalian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="return-detail-grid mb-3">
                            <div class="return-detail-item">
                                <span class="return-detail-label">Kode peminjaman</span>
                                <strong id="returnBorrowingCode">—</strong>
                            </div>
                            <div class="return-detail-item">
                                <span class="return-detail-label">Anggota</span>
                                <strong id="returnMemberName">—</strong>
                                <div class="small text-secondary" id="returnMemberCode"></div>
                            </div>
                            <div class="return-detail-item">
                                <span class="return-detail-label">Tanggal pinjam</span>
                                <strong id="returnBorrowDate">—</strong>
                            </div>
                            <div class="return-detail-item">
                                <span class="return-detail-label">Jatuh tempo</span>
                                <strong id="returnDueDate">—</strong>
                            </div>
                        </div>

                        <div class="mb-3">
                            <span class="form-label d-block">Buku yang dikembalikan</span>
                            <ul class="list-group" id="returnBookList"></ul>
                        </div>

                        <div class="return-form-grid">
                            <div>
                                <label class="form-label" for="returnNotes">Catatan (opsional)</label>
                                <textarea class="form-control" id="returnNotes" name="notes" rows="2"
                                    placeholder="Catatan pengembalian...">{{ old('notes') }}</textarea>
                            </div>
                            <div class="return-detail-item align-self-start">
                                <span class="return-detail-label">Perhitungan denda</span>
                                <strong>Dihitung otomatis saat pengembalian diproses.</strong>
                                <div class="small text-secondary mt-1">Pengembalian terlambat dikenakan denda sesuai jumlah hari dan buku.</div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle"></i>
                            Konfirmasi Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@if (!isset($return))
    @push('scripts')
        <script>
            const returnModal = document.getElementById('returnModal');

            returnModal.addEventListener('show.bs.modal', event => {
                const trigger = event.relatedTarget;
                const books = JSON.parse(trigger.dataset.books || '[]');

                document.getElementById('returnBorrowingId').value = trigger.dataset.borrowingId;
                document.getElementById('returnBorrowingCode').textContent = trigger.dataset.code;
                document.getElementById('returnMemberName').textContent = trigger.dataset.member;
                document.getElementById('returnMemberCode').textContent = trigger.dataset.memberCode;
                document.getElementById('returnBorrowDate').textContent = trigger.dataset.borrowDate;
                document.getElementById('returnDueDate').textContent = trigger.dataset.dueDate;

                const bookList = document.getElementById('returnBookList');
                bookList.replaceChildren();

                books.forEach(book => {
                    const item = document.createElement('li');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center';

                    const title = document.createElement('span');
                    title.textContent = book.title;
                    const quantity = document.createElement('span');
                    quantity.className = 'badge-soft ok';
                    quantity.textContent = `${book.quantity} buku`;

                    item.append(title, quantity);
                    bookList.append(item);
                });
            });
        </script>
    @endpush
@endif
