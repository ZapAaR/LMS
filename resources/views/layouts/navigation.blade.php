{{-- resources/views/layouts/navigation.blade.php — sidebar, kartu user, dan navbar --}}
@php
    $user    = auth()->user();
    $name    = $user->name  ?? 'Nama Pengguna';
    $email   = $user->email ?? 'email@contoh.com';
    $role    = $user->role->slug  ?? 'Administrator';
    $photo   = $user->photo_url ?? null; // ganti dengan kolom foto profil Anda
    $initial = strtoupper(mb_substr($name, 0, 1));

    $dataOpen      = request()->is('kategori*', 'category*', 'buku*', 'anggota*');
    $transaksiOpen = request()->is('peminjaman*', 'pengembalian*');
@endphp

{{-- ================= SIDEBAR ================= --}}
<aside id="sidebar" class="app-sidebar" aria-label="Navigasi utama">
    <div class="sb-head">
        <a href="{{ url('/dashboard') }}" class="sb-logo" aria-label="Perpustakaan">
            <img src="{{ asset('images/logo-perpustakaan.svg') }}" alt="Logo Perpustakaan">
        </a>
        <div class="brand-text">
            <div class="brand-name">Perpustakaan</div>
            <div class="brand-sub">Sistem informasi</div>
        </div>
        <button type="button" class="btn icon-btn sb-close" data-sidebar-close aria-label="Tutup menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <ul class="menu list-unstyled">
        <li>
            <a href="{{ url('/dashboard') }}" class="menu-link {{ request()->is('dashboard') ? 'active' : '' }}" title="Dashboard">
                <i class="bi bi-grid-1x2"></i><span>Dashboard</span>
            </a>
        </li>

        <li>
            <a class="menu-link {{ $dataOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#menu-data" role="button"
               aria-expanded="{{ $dataOpen ? 'true' : 'false' }}" title="Data">
                <i class="bi bi-database"></i><span>Data</span><i class="bi bi-chevron-down caret"></i>
            </a>
            <ul id="menu-data" class="collapse submenu list-unstyled {{ $dataOpen ? 'show' : '' }}">
                <li><a href="{{ route('category.index') }}" class="sub-link {{ request()->is('category*') ? 'active' : '' }}">Kategori</a></li>
                <li><a href="{{ url('/buku') }}" class="sub-link {{ request()->is('buku*') ? 'active' : '' }}">Buku</a></li>
                <li><a href="{{ url('/anggota') }}" class="sub-link {{ request()->is('anggota*') ? 'active' : '' }}">Anggota</a></li>
            </ul>
        </li>

        <li>
            <a class="menu-link {{ $transaksiOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#menu-transaksi" role="button"
               aria-expanded="{{ $transaksiOpen ? 'true' : 'false' }}" title="Transaksi">
                <i class="bi bi-arrow-left-right"></i><span>Transaksi</span><i class="bi bi-chevron-down caret"></i>
            </a>
            <ul id="menu-transaksi" class="collapse submenu list-unstyled {{ $transaksiOpen ? 'show' : '' }}">
                <li><a href="{{ url('/peminjaman') }}" class="sub-link {{ request()->is('peminjaman*') ? 'active' : '' }}">Peminjaman</a></li>
                <li><a href="{{ url('/pengembalian') }}" class="sub-link {{ request()->is('pengembalian*') ? 'active' : '' }}">Pengembalian</a></li>
            </ul>
        </li>

        <li>
            <a href="{{ url('/laporan') }}" class="menu-link {{ request()->is('laporan*') ? 'active' : '' }}" title="Laporan">
                <i class="bi bi-file-earmark-bar-graph"></i><span>Laporan</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/riwayat') }}" class="menu-link {{ request()->is('riwayat*') ? 'active' : '' }}" title="Riwayat">
                <i class="bi bi-clock-history"></i><span>Riwayat</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/pengguna') }}" class="menu-link {{ request()->is('pengguna*') ? 'active' : '' }}" title="Kelola akun pengguna">
                <i class="bi bi-person-gear"></i><span>Kelola akun pengguna</span>
            </a>
        </li>
    </ul>

    {{-- Info pengguna di bawah sidebar --}}
    <div class="user-card">
        <span class="user-mini" title="{{ $name }} · {{ $role }}">{{ $initial }}</span>
        <div class="user-info">
            <div class="user-name" title="{{ $name }}">{{ $name }}</div>
            <div class="user-email" title="{{ $email }}">{{ $email }}</div>
            <span class="user-role">{{ $role }}</span>
        </div>
    </div>
</aside>
<div class="sidebar-backdrop" data-sidebar-close></div>

{{-- ================= NAVBAR ================= --}}
<header class="app-topbar">
    <button id="sidebarToggle" class="btn icon-btn" type="button" aria-controls="sidebar" aria-expanded="true" aria-label="Buka/tutup menu">
        <i class="bi bi-list fs-4"></i>
    </button>

    <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>

    <div class="dropdown ms-auto">
        <button class="profile-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            @if ($photo)
                <img src="{{ $photo }}" alt="Foto {{ $name }}" class="avatar">
            @else
                <span class="avatar avatar-initial">{{ $initial }}</span>
            @endif
            <span class="d-none d-md-inline profile-name">{{ $name }}</span>
            <i class="bi bi-chevron-down small"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end profile-menu">
            <li>
                <a class="dropdown-item" href="{{ url('/profile') }}"><i class="bi bi-person me-2"></i>Profile</a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                {{-- Route "logout" bawaan Laravel Breeze/UI (method POST) --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                </form>
            </li>
        </ul>
    </div>
</header>
