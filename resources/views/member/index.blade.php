@extends('layouts.app')

@section('title', 'Anggota')
@section('page-title', 'Anggota')

@push('styles')
    <style>
        .member-toolbar {
            display: grid;
            grid-template-columns: minmax(14rem, 1.5fr) minmax(9rem, .7fr) minmax(9rem, .7fr) auto;
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .member-table th,
        .member-table td {
            vertical-align: middle;
        }

        .member-cell {
            display: flex;
            align-items: center;
            gap: .75rem;
            min-width: 14rem;
        }

        .member-avatar {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            flex: 0 0 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            font-weight: 700;
        }

        .member-name {
            color: #fff;
            font-weight: 600;
        }

        .member-meta {
            color: var(--muted);
            font-size: .8rem;
        }

        .member-status-select {
            min-width: 9rem;
            border-radius: 999px;
            font-size: .8rem;
        }

        .member-status-select.active {
            background: rgba(74, 222, 128, .12);
            color: #4ade80;
        }

        .member-status-select.inactive {
            background: rgba(142, 160, 189, .12);
            color: var(--muted);
        }

        .member-actions {
            display: flex;
            justify-content: flex-end;
            gap: .35rem;
        }

        .member-action {
            display: inline-grid;
            place-items: center;
            width: 32px;
            height: 32px;
            padding: 0;
        }

        .member-empty {
            padding: 2rem !important;
            color: var(--muted) !important;
            text-align: center;
        }

        .member-detail {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .75rem;
        }

        .member-detail-item {
            padding: .75rem;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--navy-900);
            overflow-wrap: anywhere;
        }

        .member-detail-item.full {
            grid-column: 1 / -1;
        }

        .member-detail-label {
            margin-bottom: .2rem;
            color: var(--muted);
            font-size: .76rem;
        }

        @media (max-width: 767.98px) {
            .member-toolbar {
                grid-template-columns: 1fr 1fr;
            }

            .member-toolbar .member-search {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 575.98px) {

            .member-toolbar,
            .member-detail {
                grid-template-columns: 1fr;
            }

            .member-toolbar .member-search,
            .member-detail-item.full {
                grid-column: auto;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-head mb-3">
        <h2>Manajemen Member</h2>
        <p>Kelola data dan status anggota perpustakaan.</p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMemberModal">
            <i class="bi bi-plus-lg"></i>
            Tambah Member
        </button>
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
            <h3>Daftar Member</h3>

            <div class="d-flex flex-wrap align-items-center gap-2">

                <form id="bulkMemberStatusForm" action="{{ route('member.bulkUpdateStatus') }}" method="POST"
                    class="d-flex align-items-center gap-2">

                    @csrf
                    @method('PATCH')

                    <select name="status" class="form-select" aria-label="Status untuk member terpilih" required>
                        <option value="" selected disabled>Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak aktif</option>
                    </select>

                    <button type="submit" class="btn btn-outline-brass" title="Terapkan status"
                        aria-label="Terapkan status">
                        <i class="bi bi-check-lg"></i>
                    </button>
                </form>

                <form id="bulkMemberDeleteForm" action="{{ route('member.bulkDelete') }}" method="POST">

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-danger" title="Hapus terpilih">
                        <i class="bi bi-trash"></i>
                        Hapus Terpilih
                    </button>
                </form>

            </div>
        </div>

        <form method="GET" action="{{ route('member.index') }}" class="member-toolbar" id="memberFilterForm">
            <div class="input-group member-search">
                <input type="search" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Cari nama, kode, email, atau telepon">
                <button type="submit" class="btn btn-outline-brass">
                    <i class="bi bi-search"></i>
                    Cari
                </button>
            </div>

            <select name="status" id="member-status-filter" class="form-select">
                <option value="">Semua Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Tidak aktif</option>
            </select>

            <select name="sort" id="member-sort" class="form-select">
                <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>Nama A–Z</option>
                <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>Nama Z–A</option>
            </select>

            @if ($search || $status || $sort !== 'asc')
                <a href="{{ route('member.index') }}" class="btn btn-outline-secondary">
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
            <table class="table member-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAllMembers" aria-label="Pilih semua member">
                        </th>
                        <th>No.</th>
                        <th>Member</th>
                        <th>Telepon</th>
                        <th>Bergabung</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>
                                <input type="checkbox" class="member-row-select" value="{{ $member->id }}"
                                    aria-label="Pilih {{ $member->name }}">
                            </td>
                            <td>{{ $members->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="member-cell">
                                    <div class="member-avatar">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="member-name">{{ $member->name }}</div>
                                        <div class="member-meta">
                                            {{ $member->member_code }}
                                            @if ($member->email)
                                                · {{ $member->email }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $member->phone ?: '—' }}</td>
                            <td>{{ $member->created_at?->format('d M Y') ?: '—' }}</td>
                            <td>
                                <form action="{{ route('member.updateStatus', $member) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status"
                                        class="form-select form-select-sm member-status-select {{ $member->status }}"
                                        aria-label="Ubah status {{ $member->name }}" onchange="this.form.submit()">
                                        <option value="active" {{ $member->status === 'active' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="inactive" {{ $member->status === 'inactive' ? 'selected' : '' }}>
                                            Tidak aktif</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <div class="member-actions">
                                    <button type="button" class="btn btn-sm btn-outline-brass member-action"
                                        title="Lihat member" aria-label="Lihat member" data-bs-toggle="modal"
                                        data-bs-target="#showMemberModal" data-code="{{ $member->member_code }}"
                                        data-name="{{ $member->name }}" data-email="{{ $member->email }}"
                                        data-phone="{{ $member->phone }}" data-address="{{ $member->address }}"
                                        data-status="{{ $member->status }}">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-primary member-action"
                                        title="Edit member" aria-label="Edit member" data-bs-toggle="modal"
                                        data-bs-target="#editMemberModal"
                                        data-url="{{ route('member.update', $member) }}"
                                        data-code="{{ $member->member_code }}" data-name="{{ $member->name }}"
                                        data-email="{{ $member->email }}" data-phone="{{ $member->phone }}"
                                        data-address="{{ $member->address }}" data-status="{{ $member->status }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form action="{{ route('member.destroy', $member) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus member ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger member-action"
                                            title="Hapus member" aria-label="Hapus member">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('member.pdf', $member) }}" target="_blank" rel="noopener"
                                        class="btn btn-sm btn-outline-secondary member-action" title="Cetak / simpan PDF"
                                        aria-label="Cetak atau simpan PDF">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="member-empty">
                                {{ $search || $status ? 'Member tidak ditemukan.' : 'Belum ada member.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($members->hasPages())
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
                <span class="small text-secondary">
                    Menampilkan {{ $members->firstItem() }}–{{ $members->lastItem() }}
                    dari {{ $members->total() }} member
                </span>
                {{ $members->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>

    <div class="modal fade" id="showMemberModal" tabindex="-1" aria-labelledby="showMemberTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showMemberTitle">Detail Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="member-detail">
                        <div class="member-detail-item">
                            <div class="member-detail-label">Kode member</div>
                            <div class="fw-semibold" id="showMemberCode"></div>
                        </div>
                        <div class="member-detail-item">
                            <div class="member-detail-label">Status</div>
                            <div class="fw-semibold" id="showMemberStatus"></div>
                        </div>
                        <div class="member-detail-item">
                            <div class="member-detail-label">Nama</div>
                            <div class="fw-semibold" id="showMemberName"></div>
                        </div>
                        <div class="member-detail-item">
                            <div class="member-detail-label">Email</div>
                            <div class="fw-semibold" id="showMemberEmail"></div>
                        </div>
                        <div class="member-detail-item">
                            <div class="member-detail-label">Telepon</div>
                            <div class="fw-semibold" id="showMemberPhone"></div>
                        </div>
                        <div class="member-detail-item full">
                            <div class="member-detail-label">Alamat</div>
                            <div class="fw-semibold" id="showMemberAddress"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createMemberModal" tabindex="-1" aria-labelledby="createMemberTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('member.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createMemberTitle">Tambah Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create-member-code" class="form-label">Kode Member</label>
                            <input type="text" name="member_code" id="create-member-code" class="form-control"
                                value="{{ old('member_code') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="create-member-name" class="form-label">Nama</label>
                            <input type="text" name="name" id="create-member-name" class="form-control"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="create-member-email" class="form-label">Email</label>
                            <input type="email" name="email" id="create-member-email" class="form-control"
                                value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label for="create-member-phone" class="form-label">Telepon</label>
                            <input type="text" name="phone" id="create-member-phone" class="form-control"
                                value="{{ old('phone') }}">
                        </div>
                        <div class="mb-3">
                            <label for="create-member-address" class="form-label">Alamat</label>
                            <textarea name="address" id="create-member-address" class="form-control" rows="3">{{ old('address') }}</textarea>
                        </div>
                        <div>
                            <label for="create-member-status" class="form-label">Status</label>
                            <select name="status" id="create-member-status" class="form-select" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tidak aktif
                                </option>
                            </select>
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
    </div>

    <div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editMemberForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMemberTitle">Edit Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-member-code" class="form-label">Kode Member</label>
                            <input type="text" name="member_code" id="edit-member-code" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-member-name" class="form-label">Nama</label>
                            <input type="text" name="name" id="edit-member-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-member-email" class="form-label">Email</label>
                            <input type="email" name="email" id="edit-member-email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit-member-phone" class="form-label">Telepon</label>
                            <input type="text" name="phone" id="edit-member-phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit-member-address" class="form-label">Alamat</label>
                            <textarea name="address" id="edit-member-address" class="form-control" rows="3"></textarea>
                        </div>
                        <div>
                            <label for="edit-member-status" class="form-label">Status</label>
                            <select name="status" id="edit-member-status" class="form-select" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak aktif</option>
                            </select>
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
    </div>

    @push('scripts')
        <script>
            const memberFilterForm = document.getElementById('memberFilterForm');
            document.getElementById('member-status-filter').addEventListener('change', () => memberFilterForm.submit());
            document.getElementById('member-sort').addEventListener('change', () => memberFilterForm.submit());

            const memberRowSelections = document.querySelectorAll('.member-row-select');
            const selectAllMembers = document.getElementById('selectAllMembers');

            const updateSelectAllMembers = () => {
                const selectedCount = document.querySelectorAll('.member-row-select:checked').length;
                selectAllMembers.checked = memberRowSelections.length > 0 && selectedCount === memberRowSelections.length;
                selectAllMembers.indeterminate = selectedCount > 0 && selectedCount < memberRowSelections.length;
            };

            selectAllMembers.addEventListener('change', () => {
                memberRowSelections.forEach(checkbox => {
                    checkbox.checked = selectAllMembers.checked;
                });
                updateSelectAllMembers();
            });

            memberRowSelections.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectAllMembers);
            });

            const prepareBulkMemberForm = form => {
                form.addEventListener('submit', event => {
                    const selectedIds = Array.from(document.querySelectorAll('.member-row-select:checked'))
                        .map(checkbox => checkbox.value);

                    if (selectedIds.length === 0) {
                        event.preventDefault();
                        alert('Pilih minimal satu member terlebih dahulu.');
                        return;
                    }

                    form.querySelectorAll('input[name="ids[]"]').forEach(input => input.remove());
                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                });
            };

            prepareBulkMemberForm(document.getElementById('bulkMemberStatusForm'));
            prepareBulkMemberForm(document.getElementById('bulkMemberDeleteForm'));
            document.getElementById('bulkMemberDeleteForm').addEventListener('submit', event => {
                if (!confirm('Yakin ingin menghapus member yang dipilih?')) {
                    event.preventDefault();
                }
            });

            const showMemberModal = document.getElementById('showMemberModal');
            showMemberModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const values = {
                    showMemberCode: button.dataset.code,
                    showMemberName: button.dataset.name,
                    showMemberEmail: button.dataset.email,
                    showMemberPhone: button.dataset.phone,
                    showMemberAddress: button.dataset.address,
                    showMemberStatus: button.dataset.status === 'active' ? 'Aktif' : 'Tidak aktif',
                };

                Object.entries(values).forEach(([id, value]) => {
                    document.getElementById(id).textContent = value || '—';
                });
            });

            const editMemberModal = document.getElementById('editMemberModal');
            editMemberModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const form = document.getElementById('editMemberForm');

                form.action = button.dataset.url;
                document.getElementById('edit-member-code').value = button.dataset.code || '';
                document.getElementById('edit-member-name').value = button.dataset.name || '';
                document.getElementById('edit-member-email').value = button.dataset.email || '';
                document.getElementById('edit-member-phone').value = button.dataset.phone || '';
                document.getElementById('edit-member-address').value = button.dataset.address || '';
                document.getElementById('edit-member-status').value = button.dataset.status || 'active';
            });
        </script>
    @endpush
@endsection
