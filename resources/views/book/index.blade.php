@extends('layouts.app')

@section('title', 'Buku')
@section('page-title', 'Buku')

@push('styles')
    <style>
        .book-toolbar {
            display: grid;
            grid-template-columns: minmax(15rem, 1.4fr) repeat(3, minmax(10rem, .8fr)) auto;
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .book-table th,
        .book-table td {
            vertical-align: middle;
        }

        .book-cell {
            display: flex;
            align-items: center;
            gap: .75rem;
            min-width: 15rem;
        }

        .book-title {
            font-weight: 700;
            color: #fff;
            line-height: 1.25;
        }

        .book-code,
        .book-meta {
            color: var(--muted);
            font-size: .78rem;
        }

        .badge-stock {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            border-radius: 999px;
            padding: .28rem .65rem;
            font-size: .76rem;
            font-weight: 700;
        }

        .badge-stock.available {
            color: #7be0a2;
            background: rgba(74, 222, 128, .14);
        }

        .badge-stock.low {
            color: #fbd38d;
            background: rgba(251, 191, 36, .14);
        }

        .badge-stock.empty {
            color: #ff9a9a;
            background: rgba(248, 113, 113, .15);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .75rem;
        }

        .detail-item {
            background: var(--navy-900);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: .75rem;
        }

        .detail-item .label {
            color: var(--muted);
            font-size: .76rem;
            margin-bottom: .2rem;
        }

        .detail-item .value {
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            .book-toolbar {
                grid-template-columns: 1fr 1fr;
            }

            .book-toolbar .search-field {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 575.98px) {
            .book-toolbar {
                grid-template-columns: 1fr;
            }

            .book-toolbar .search-field {
                grid-column: auto;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-head mb-3">
        <h2>Manajemen Buku</h2>
        <p>Kelola koleksi buku, kategori, dan ketersediaan stok perpustakaan.</p>
        @if (auth()->user()->role->slug === 'admin')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBookModal">
                <i class="bi bi-plus-lg"></i>
                Tambah Buku
            </button>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Periksa kembali data yang dimasukkan.</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <section class="surface">
        <div class="surface-head">
            <h3>Daftar Buku</h3>
            @if (auth()->user()->role->slug === 'admin')
                <form id="bulkDeleteBooksForm" action="{{ route('book.bulkDelete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger"
                        onclick="return confirm('Yakin ingin menghapus buku yang dipilih?')">
                        <i class="bi bi-trash"></i>
                        Bulk Delete
                    </button>
                </form>
            @endif
        </div>

        <form method="GET" action="{{ route('book.index') }}" class="book-toolbar" id="bookFilterForm">
            <div class="input-group search-field">
                <input type="search" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Cari judul, penulis, kode, atau ISBN">
                <button type="submit" class="btn btn-outline-brass">
                    <i class="bi bi-search"></i>
                    Cari
                </button>
            </div>

            <select name="category_id" id="book-category-filter" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) $categoryId === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="sort" id="book-sort" class="form-select">
                <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>Judul A–Z</option>
                <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>Judul Z–A</option>
            </select>

            <select name="stock_status" id="book-stock-filter" class="form-select">
                <option value="">Semua Stok</option>
                <option value="available" {{ $stockStatus === 'available' ? 'selected' : '' }}>Tersedia</option>
                <option value="low" {{ $stockStatus === 'low' ? 'selected' : '' }}>Stok Rendah</option>
                <option value="empty" {{ $stockStatus === 'empty' ? 'selected' : '' }}>Habis</option>
            </select>

            @if ($search || $categoryId || $stockStatus || $sort !== 'asc')
                <a href="{{ route('book.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>
            @else
                <button type="submit" class="btn btn-outline-brass">
                    <i class="bi bi-funnel"></i>
                    Filter
                </button>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table book-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Buku</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        @php
                            $stockState = $book->available_stock === 0
                                ? ['empty', 'Habis']
                                : ($book->available_stock <= 3
                                    ? ['low', 'Stok Rendah']
                                    : ['available', 'Tersedia']);
                        @endphp
                        <tr>
                            <td>{{ $books->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="book-cell">
                                    @if (auth()->user()->role->slug === 'admin')
                                        <input type="checkbox" name="ids[]" value="{{ $book->id }}"
                                            form="bulkDeleteBooksForm" aria-label="Pilih {{ $book->title }}">
                                    @endif
                                    <div>
                                        <div class="book-title">{{ $book->title }}</div>
                                        <div class="book-code">{{ $book->book_code }} · ISBN {{ $book->isbn ?: '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $book->author }}</td>
                            <td><span class="badge-soft ok">{{ $book->category->name }}</span></td>
                            <td>{{ $book->available_stock }} / {{ $book->stock }}</td>
                            <td>
                                <span class="badge-stock {{ $stockState[0] }}">
                                    <i class="bi bi-circle-fill" style="font-size:.45rem"></i>
                                    {{ $stockState[1] }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Lihat detail"
                                        data-bs-toggle="modal" data-bs-target="#detailBookModal"
                                        data-title="{{ $book->title }}" data-code="{{ $book->book_code }}"
                                        data-author="{{ $book->author }}" data-category="{{ $book->category->name }}"
                                        data-publisher="{{ $book->publisher }}" data-year="{{ $book->publication_year }}"
                                        data-isbn="{{ $book->isbn }}" data-stock="{{ $book->available_stock }}"
                                        data-total-stock="{{ $book->stock }}">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if (auth()->user()->role->slug === 'admin')
                                        <button type="button" class="btn btn-sm btn-outline-primary" title="Edit buku"
                                            data-bs-toggle="modal" data-bs-target="#editBookModal"
                                            data-id="{{ $book->id }}" data-category-id="{{ $book->category_id }}"
                                            data-code="{{ $book->book_code }}" data-title="{{ $book->title }}"
                                            data-author="{{ $book->author }}" data-publisher="{{ $book->publisher }}"
                                            data-year="{{ $book->publication_year }}" data-isbn="{{ $book->isbn }}"
                                            data-stock="{{ $book->stock }}" data-url="{{ route('book.update', $book) }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form action="{{ route('book.destroy', $book) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus buku">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-secondary">
                                {{ $search || $categoryId || $stockStatus ? 'Buku tidak ditemukan.' : 'Belum ada buku.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($books->hasPages())
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
                <span class="small text-secondary">
                    Menampilkan {{ $books->firstItem() }}–{{ $books->lastItem() }}
                    dari {{ $books->total() }} buku
                </span>
                {{ $books->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>

    @if (auth()->user()->role->slug === 'admin')
        <div class="modal fade" id="createBookModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form class="modal-content" action="{{ route('book.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="create-title" class="form-label">Judul Buku</label>
                                <input type="text" name="title" id="create-title" class="form-control"
                                    value="{{ old('title') }}" placeholder="Contoh: Laskar Pelangi" required>
                            </div>
                            <div class="col-md-4">
                                <label for="create-code" class="form-label">Kode Buku</label>
                                <input type="text" name="book_code" id="create-code" class="form-control"
                                    value="{{ old('book_code') }}" placeholder="BK-001" required>
                            </div>
                            <div class="col-md-6">
                                <label for="create-author" class="form-label">Penulis</label>
                                <input type="text" name="author" id="create-author" class="form-control"
                                    value="{{ old('author') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="create-category" class="form-label">Kategori</label>
                                <select name="category_id" id="create-category" class="form-select" required>
                                    <option value="">Pilih kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="create-publisher" class="form-label">Penerbit</label>
                                <input type="text" name="publisher" id="create-publisher" class="form-control"
                                    value="{{ old('publisher') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="create-isbn" class="form-label">ISBN</label>
                                <input type="text" name="isbn" id="create-isbn" class="form-control"
                                    value="{{ old('isbn') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="create-year" class="form-label">Tahun Terbit</label>
                                <input type="number" name="publication_year" id="create-year" class="form-control"
                                    value="{{ old('publication_year') }}" min="1000" max="{{ date('Y') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="create-stock" class="form-label">Stok</label>
                                <input type="number" name="stock" id="create-stock" class="form-control"
                                    value="{{ old('stock', 0) }}" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="editBookModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form class="modal-content" id="editBookForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="edit-title" class="form-label">Judul Buku</label>
                                <input type="text" name="title" id="edit-title" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="edit-code" class="form-label">Kode Buku</label>
                                <input type="text" name="book_code" id="edit-code" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit-author" class="form-label">Penulis</label>
                                <input type="text" name="author" id="edit-author" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit-category" class="form-label">Kategori</label>
                                <select name="category_id" id="edit-category" class="form-select" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit-publisher" class="form-label">Penerbit</label>
                                <input type="text" name="publisher" id="edit-publisher" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="edit-isbn" class="form-label">ISBN</label>
                                <input type="text" name="isbn" id="edit-isbn" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="edit-year" class="form-label">Tahun Terbit</label>
                                <input type="number" name="publication_year" id="edit-year" class="form-control"
                                    min="1000" max="{{ date('Y') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="edit-stock" class="form-label">Stok</label>
                                <input type="number" name="stock" id="edit-stock" class="form-control" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="modal fade" id="detailBookModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-book me-2" style="color:var(--brass)"></i>Detail Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h5 id="detail-title" class="mb-1"></h5>
                    <p class="text-secondary mb-3">oleh <strong id="detail-author"></strong></p>
                    <div class="detail-grid">
                        <div class="detail-item"><div class="label">Kode Buku</div><div class="value" id="detail-code"></div></div>
                        <div class="detail-item"><div class="label">Kategori</div><div class="value" id="detail-category"></div></div>
                        <div class="detail-item"><div class="label">ISBN</div><div class="value" id="detail-isbn"></div></div>
                        <div class="detail-item"><div class="label">Tahun Terbit</div><div class="value" id="detail-year"></div></div>
                        <div class="detail-item"><div class="label">Penerbit</div><div class="value" id="detail-publisher"></div></div>
                        <div class="detail-item"><div class="label">Stok Tersedia</div><div class="value" id="detail-stock"></div></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const bookFilterForm = document.getElementById('bookFilterForm');
            ['book-category-filter', 'book-sort', 'book-stock-filter'].forEach((id) => {
                const select = document.getElementById(id);
                if (select) {
                    select.addEventListener('change', () => bookFilterForm.submit());
                }
            });

            const editBookModal = document.getElementById('editBookModal');
            if (editBookModal) {
                editBookModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    document.getElementById('editBookForm').action = button.dataset.url;
                    document.getElementById('edit-category').value = button.dataset.categoryId;
                    document.getElementById('edit-code').value = button.dataset.code;
                    document.getElementById('edit-title').value = button.dataset.title;
                    document.getElementById('edit-author').value = button.dataset.author;
                    document.getElementById('edit-publisher').value = button.dataset.publisher || '';
                    document.getElementById('edit-year').value = button.dataset.year || '';
                    document.getElementById('edit-isbn').value = button.dataset.isbn || '';
                    document.getElementById('edit-stock').value = button.dataset.stock;
                });
            }

            const detailBookModal = document.getElementById('detailBookModal');
            detailBookModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('detail-title').textContent = button.dataset.title;
                document.getElementById('detail-author').textContent = button.dataset.author;
                document.getElementById('detail-code').textContent = button.dataset.code;
                document.getElementById('detail-category').textContent = button.dataset.category;
                document.getElementById('detail-isbn').textContent = button.dataset.isbn || '-';
                document.getElementById('detail-year').textContent = button.dataset.year || '-';
                document.getElementById('detail-publisher').textContent = button.dataset.publisher || '-';
                document.getElementById('detail-stock').textContent =
                    `${button.dataset.stock} tersedia dari ${button.dataset.totalStock} buku`;
            });

            @if ($errors->any())
                new bootstrap.Modal(document.getElementById('createBookModal')).show();
            @endif
        </script>
    @endpush
@endsection
