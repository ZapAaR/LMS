@extends('layouts.app')

@section('title', 'Peminjaman')
@section('page-title', 'Peminjaman')

@push('styles')
    <style>
        .borrowing-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: start;
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .borrowing-toolbar .input-group {
            max-width: 24rem;
        }

        .borrowing-table th,
        .borrowing-table td {
            vertical-align: middle;
        }

        .borrowing-status-select {
            min-width: 9.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="page-head mb-3">
        <h2>Manajemen Peminjaman</h2>
        <p>Kelola transaksi peminjaman dan pengembalian buku.</p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">
            <i class="bi bi-plus-lg"></i>
            Peminjaman Baru
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <section class="surface">
        <div class="surface-head">
            <h3>Daftar Peminjaman</h3>
        </div>

        <form method="GET" action="{{ route('borrowing.index') }}" class="borrowing-toolbar" id="filterForm">
            <div class="input-group">
                <input type="search" name="search" id="searchInput" value="{{ $search }}" class="form-control"
                    placeholder="Cari kode atau nama member">
                <button type="submit" class="btn btn-outline-brass">
                    <i class="bi bi-search"></i>
                    Cari
                </button>
            </div>

            <select class="form-select" id="statusFilter" name="status" aria-label="Filter status"
                style="max-width: 12rem;">
                <option value="">Semua Status</option>
                <option value="borrowed" @selected($status === 'borrowed')>Dipinjam</option>
                <option value="overdue" @selected($status === 'overdue')>Terlambat</option>
                <option value="returned" @selected($status === 'returned')>Dikembalikan</option>
            </select>

            <select class="form-select" id="sortSelect" name="sort" aria-label="Urutkan tanggal"
                style="max-width: 13rem;">
                <option value="desc" @selected($sort === 'desc')>Tanggal terbaru</option>
                <option value="asc" @selected($sort === 'asc')>Tanggal terlama</option>
            </select>

            <select class="form-select" id="perPageSelect" name="per_page" aria-label="Jumlah data per halaman"
                style="max-width: 9rem;">
                @foreach ([10, 25, 50] as $pageSize)
                    <option value="{{ $pageSize }}" @selected((int) $perPage === $pageSize)>
                        {{ $pageSize }} per halaman
                    </option>
                @endforeach
            </select>

            @if ($search || $status || $sort !== 'desc' || (int) $perPage !== 10)
                <a class="btn btn-outline-secondary" href="{{ route('borrowing.index') }}">Reset</a>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table borrowing-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Member</th>
                        <th>Tanggal Pinjam / Harus Kembali</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($borrowings as $borrowing)
                        @php
                            $lateDays = $borrowing->status === 'overdue'
                                ? max(0, (int) $borrowing->due_date->startOfDay()->diffInDays(now()->startOfDay(), false))
                                : 0;
                            $fine = $borrowing->returnRecord?->fine_amount ?? ($lateDays * 2000);
                            $statusLabels = [
                                'borrowed' => 'Dipinjam',
                                'overdue' => 'Terlambat',
                                'returned' => 'Dikembalikan',
                            ];
                        @endphp
                        <tr>
                            <td>{{ $borrowings->firstItem() + $loop->index }}</td>
                            <td>
                                <div>{{ $borrowing->member->name }}</div>
                                <small class="text-secondary">{{ $borrowing->member->member_code }}</small>
                            </td>
                            <td>
                                <div>Pinjam: {{ $borrowing->borrow_date->format('d M Y') }}</div>
                                <small class="text-secondary">
                                    Harus kembali: {{ $borrowing->due_date->format('d M Y') }}
                                </small>
                                @if ($lateDays > 0)
                                    <small class="text-danger d-block">Terlambat {{ $lateDays }} hari</small>
                                @endif
                            </td>
                            <td>
                                @if ($borrowing->status === 'returned')
                                    <span class="badge-soft done">{{ $statusLabels[$borrowing->status] }}</span>
                                @else
                                    <form action="{{ route('borrowing.updateStatus', $borrowing) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm borrowing-status-select"
                                            aria-label="Ubah status {{ $borrowing->borrowing_code }}"
                                            onchange="this.form.submit()">
                                            <option value="{{ $borrowing->status }}" selected disabled>
                                                {{ $statusLabels[$borrowing->status] }}
                                            </option>
                                            <option value="returned">Dikembalikan</option>
                                        </select>
                                    </form>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-sm btn-outline-brass"
                                        title="Lihat peminjaman"
                                        aria-label="Lihat peminjaman {{ $borrowing->borrowing_code }}"
                                        data-bs-toggle="modal" data-bs-target="#detailBorrowingModal"
                                        data-code="{{ $borrowing->borrowing_code }}"
                                        data-member="{{ $borrowing->member->name }}"
                                        data-member-code="{{ $borrowing->member->member_code }}"
                                        data-email="{{ $borrowing->member->email }}"
                                        data-processed-by="{{ $borrowing->processedBy->name }}"
                                        data-borrow-date="{{ $borrowing->borrow_date->format('d M Y') }}"
                                        data-due-date="{{ $borrowing->due_date->format('d M Y') }}"
                                        data-return-date="{{ $borrowing->returnRecord?->return_date?->format('d M Y') ?? '—' }}"
                                        data-status="{{ $statusLabels[$borrowing->status] ?? ucfirst($borrowing->status) }}"
                                        data-status-class="{{ $borrowing->status === 'returned' ? 'done' : ($borrowing->status === 'overdue' ? 'late' : 'ok') }}"
                                        data-late-days="{{ $borrowing->returnRecord?->late_days ?? $lateDays }}"
                                        data-fine="{{ $fine }}"
                                        data-notes="{{ $borrowing->notes }}"
                                        data-books="{{ $borrowing->details->map(fn ($detail) => [
                                            'title' => $detail->book->title,
                                            'author' => $detail->book->author,
                                            'quantity' => $detail->quantity,
                                        ])->values()->toJson() }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <form action="{{ route('borrowing.destroy', $borrowing) }}" method="POST"
                                        class="ms-2" onsubmit="return confirm('Hapus data peminjaman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            aria-label="Hapus peminjaman {{ $borrowing->borrowing_code }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-secondary">
                                {{ $search || $status ? 'Peminjaman tidak ditemukan.' : 'Belum ada peminjaman.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
            <span class="small text-secondary">
                Menampilkan {{ $borrowings->firstItem() ?? 0 }}–{{ $borrowings->lastItem() ?? 0 }}
                dari {{ $borrowings->total() }} peminjaman
            </span>
            {{ $borrowings->links('pagination::bootstrap-5') }}
        </div>
    </section>

    <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" id="loanForm" action="{{ route('borrowing.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalTitle">Peminjaman Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="loanMember">Member</label>
                            <select class="form-select" id="loanMember" name="member_id" required>
                                <option value="">Pilih member</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}" @selected(old('member_id') == $member->id)>
                                        {{ $member->name }} — {{ $member->member_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="loanBook">Buku</label>
                            <select class="form-select" id="loanBook" name="books[0][book_id]" required>
                                <option value="">Pilih buku</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}" @selected(old('books.0.book_id') == $book->id)>
                                        {{ $book->title }} — {{ $book->author }} (stok {{ $book->available_stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="loanQuantity">Jumlah</label>
                            <input type="number" class="form-control" id="loanQuantity" name="books[0][quantity]"
                                min="1" max="3" value="{{ old('books.0.quantity', 1) }}" required>
                            <div class="form-text">Maksimal 3 buku per peminjaman.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="loanBorrowDate">Tanggal Pinjam</label>
                            <input type="date" class="form-control" id="loanBorrowDate" name="borrow_date"
                                value="{{ old('borrow_date', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="loanDueDate">Harus Kembali</label>
                            <input type="date" class="form-control" id="loanDueDate" name="due_date"
                                value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="loanNotes">Catatan (opsional)</label>
                            <textarea class="form-control" id="loanNotes" name="notes" rows="2"
                                placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="detailBorrowingModal" tabindex="-1"
        aria-labelledby="detailBorrowingModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailBorrowingModalTitle">Detail Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                        <div>
                            <small class="text-secondary d-block">Kode Peminjaman</small>
                            <span class="font-monospace" id="detail-code"></span>
                        </div>
                        <span class="badge-soft" id="detail-status"></span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Member</small>
                            <div id="detail-member"></div>
                            <small class="text-secondary" id="detail-member-code"></small>
                            <small class="text-secondary d-block" id="detail-email"></small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Diproses oleh</small>
                            <div id="detail-processed-by"></div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-secondary d-block">Tanggal Pinjam</small>
                            <div id="detail-borrow-date"></div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-secondary d-block">Harus Kembali</small>
                            <div id="detail-due-date"></div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-secondary d-block">Tanggal Kembali</small>
                            <div id="detail-return-date"></div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-secondary d-block">Denda</small>
                            <div id="detail-fine"></div>
                        </div>
                    </div>

                    <h6>Daftar Buku</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Judul Buku</th>
                                    <th>Penulis</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="detail-books"></tbody>
                        </table>
                    </div>

                    <div class="mt-3" id="detail-notes-wrap" hidden>
                        <small class="text-secondary d-block">Catatan</small>
                        <div id="detail-notes" class="text-break"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const filterForm = document.getElementById('filterForm');
        const statusFilter = document.getElementById('statusFilter');
        const sortSelect = document.getElementById('sortSelect');
        const perPageSelect = document.getElementById('perPageSelect');
        const searchInput = document.getElementById('searchInput');

        statusFilter.addEventListener('change', () => filterForm.submit());
        sortSelect.addEventListener('change', () => filterForm.submit());
        perPageSelect.addEventListener('change', () => filterForm.submit());

        let searchTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => filterForm.submit(), 350);
        });

        document.getElementById('loanBorrowDate').addEventListener('change', event => {
            if (!event.target.value) return;

            const dueDate = new Date(`${event.target.value}T00:00:00`);
            dueDate.setDate(dueDate.getDate() + 7);
            document.getElementById('loanDueDate').value = [
                dueDate.getFullYear(),
                String(dueDate.getMonth() + 1).padStart(2, '0'),
                String(dueDate.getDate()).padStart(2, '0')
            ].join('-');
        });

        document.getElementById('detailBorrowingModal').addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const setText = (id, value) => {
                document.getElementById(id).textContent = value || '—';
            };

            setText('detail-code', button.dataset.code);
            setText('detail-member', button.dataset.member);
            setText('detail-member-code', button.dataset.memberCode);
            setText('detail-email', button.dataset.email);
            setText('detail-processed-by', button.dataset.processedBy);
            setText('detail-borrow-date', button.dataset.borrowDate);
            setText('detail-due-date', button.dataset.dueDate);
            setText('detail-return-date', button.dataset.returnDate);

            const status = document.getElementById('detail-status');
            status.textContent = button.dataset.status;
            status.className = `badge-soft ${button.dataset.statusClass}`;

            const fine = Number(button.dataset.fine || 0);
            const lateDays = Number(button.dataset.lateDays || 0);
            setText(
                'detail-fine',
                fine > 0
                    ? `Rp ${fine.toLocaleString('id-ID')}${lateDays > 0 ? ` (${lateDays} hari terlambat)` : ''}`
                    : '—'
            );

            const books = JSON.parse(button.dataset.books || '[]');
            const bookRows = document.getElementById('detail-books');
            bookRows.replaceChildren();

            books.forEach(book => {
                const row = document.createElement('tr');
                [book.title, book.author, book.quantity].forEach(value => {
                    const cell = document.createElement('td');
                    cell.textContent = value;
                    row.appendChild(cell);
                });
                bookRows.appendChild(row);
            });

            const notes = button.dataset.notes;
            document.getElementById('detail-notes').textContent = notes || '';
            document.getElementById('detail-notes-wrap').hidden = !notes;
        });

        @if ($errors->hasAny(['member_id', 'books.0.book_id', 'books.0.quantity', 'borrow_date', 'due_date', 'notes']))
            bootstrap.Modal.getOrCreateInstance(document.getElementById('formModal')).show();
        @endif
    </script>
@endpush
