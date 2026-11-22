@extends('layouts.app')

@section('title', 'Kategori')
@section('page-title', 'Kategori')

@push('styles')
    <style>
        .category-toolbar {
            max-width: 34rem;
            margin-bottom: 1rem;
        }

        .category-table th,
        .category-table td {
            vertical-align: middle;
        }

        .category-description {
            min-width: 14rem;
            white-space: normal !important;
        }
    </style>
@endpush

@section('content')
    <div class="page-head mb-3">
        <h2>Manajemen Kategori</h2>
        <p>Kelola daftar kategori buku perpustakaan.</p>
        @if (auth()->user()->role->slug === 'admin')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="bi bi-plus-lg"></i>
                Tambah Kategori
            </button>
        @endif
    </div>

    <section class="surface">
        <div class="surface-head">
            <h3>Daftar Kategori</h3>
        </div>

        <form method="GET" action="{{ route('category.index') }}" class="category-toolbar">
            <label for="category-search" class="visually-hidden">Cari kategori</label>
            <div class="input-group">
                <input type="search" id="category-search" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Cari nama kategori">
                <button type="submit" class="btn btn-outline-brass">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    Cari
                </button>
                <select name="sort" class="form-select" style="max-width: 150px;">
                    <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>
                        Nama A–Z
                    </option>

                    <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>
                        Nama Z–A
                    </option>
                </select>
                @if ($search || $sort !== 'asc')
                    <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table category-table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Nama kategori</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Dibuat</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $loop->index }}</td>
                            <td>{{ $category->name }}</td>
                            <td class="category-description">{{ $category->description ?: '—' }}</td>
                            <td>{{ $category->created_at->format('d M Y') }}</td>
                            <td>
                                @if (auth()->user()->role->slug === 'admin')
                                    <div class="d-flex gap-2">
                                        {{-- Tombol Edit --}}
                                        <button type="button" class="btn btn-sm btn-outline-primary" title="Edit kategori"
                                            data-bs-toggle="modal" data-bs-target="#editCategoryModal"
                                            data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                            data-description="{{ $category->description }}"
                                            data-url="{{ route('category.update', $category->id) }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Hapus kategori">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-secondary">
                                {{ $search ? 'Kategori tidak ditemukan.' : 'Belum ada kategori.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
                <span class="small text-secondary">
                    Menampilkan {{ $categories->firstItem() }}–{{ $categories->lastItem() }}
                    dari {{ $categories->total() }} kategori
                </span>
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>

    {{-- Modal Create --}}
    @if (auth()->user()->role->slug === 'admin')
        <div class="modal fade" id="createCategoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form action="{{ route('category.store') }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Kategori</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="create-name" class="form-label">Nama Kategori</label>
                                <input type="text" name="name" id="create-name" class="form-control"
                                    placeholder="Masukkan nama kategori" required>
                            </div>

                            <div class="mb-3">
                                <label for="create-description" class="form-label">Deskripsi</label>
                                <textarea name="description" id="create-description" class="form-control" rows="3"
                                    placeholder="Masukkan deskripsi kategori"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i>
                                Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- Modal Edit --}}
        <div class="modal fade" id="editCategoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form id="editCategoryForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Kategori</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit-name" class="form-label">Nama Kategori</label>
                                <input type="text" name="name" id="edit-name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit-description" class="form-label">Deskripsi</label>
                                <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            const editCategoryModal = document.getElementById('editCategoryModal');

            if (editCategoryModal) {
                editCategoryModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;

                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const description = button.getAttribute('data-description');
                    const url = button.getAttribute('data-url');

                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-description').value = description ?? '';
                    document.getElementById('editCategoryForm').action = url;
                });
            }
        </script>
    @endpush
@endsection
