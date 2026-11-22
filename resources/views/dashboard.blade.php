{{-- resources/views/dashboard.blade.php — satu file: HTML + Bootstrap + CSS + JS + Blade --}}
@php
    $user    = auth()->user();
    $name    = $user->name  ?? 'Nama Pengguna';
    $email   = $user->email ?? 'email@contoh.com';
    $role    = $user->role->slug  ?? 'Administrator';
    $photo   = $user->photo_url ?? null; // ganti dengan kolom foto profil Anda
    $initial = strtoupper(mb_substr($name, 0, 1));

    $dataOpen      = request()->is('kategori*', 'buku*', 'anggota*');
    $transaksiOpen = request()->is('peminjaman*', 'pengembalian*');
@endphp
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard · Perpustakaan</title>

    {{-- Pulihkan status sidebar sebelum halaman digambar (tanpa kedip) --}}
    <script>
        try { if (localStorage.getItem('sidebar') === 'collapsed') document.documentElement.classList.add('sidebar-collapsed'); } catch (e) {}
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --navy-950: #060b1c;
            --navy-900: #0a1128;
            --navy-800: #0e1a3a;
            --navy-700: #15234a;
            --navy-600: #1f3163;
            --line: rgba(143, 165, 255, .14);
            --text: #e8ecf9;
            --muted: #8f9cc4;
            --brass: #e3b862;
            --sidebar-w: 280px;
            --sidebar-w-mini: 76px;
        }

        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background: var(--navy-900); color: var(--text); }

        [data-bs-theme="dark"] {
            --bs-body-bg: var(--navy-900);
            --bs-body-color: var(--text);
            --bs-border-color: var(--line);
            --bs-dropdown-bg: var(--navy-700);
            --bs-dropdown-border-color: var(--line);
            --bs-dropdown-link-hover-bg: var(--navy-600);
        }

        :focus-visible { outline: 2px solid var(--brass); outline-offset: 2px; }

        /* ---------- Sidebar ---------- */
        .app-sidebar {
            position: fixed; inset: 0 auto 0 0;
            width: var(--sidebar-w);
            display: flex; flex-direction: column;
            background: var(--navy-800);
            border-right: 1px solid var(--line);
            z-index: 1030;
            transition: width .25s ease, transform .25s ease;
            overflow: hidden;
        }

        .sb-head {
            display: flex; align-items: center; gap: .75rem;
            height: 64px; padding: 0 1rem; flex: 0 0 64px;
            border-bottom: 1px solid var(--line);
        }
        .sb-logo {
            width: 44px; height: 44px; flex: 0 0 44px;
            display: grid; place-items: center;
            border-radius: 12px; background: #fff; overflow: hidden;
            border: 1px solid rgba(227,184,98,.65);
        }
        .sb-logo img { width: 100%; height: 100%; object-fit: cover; }
        .brand-text { min-width: 0; white-space: nowrap; }
        .brand-name { font-weight: 700; font-size: 1.02rem; line-height: 1.2; }
        .brand-sub  { font-size: .76rem; color: var(--muted); }
        .sb-close { margin-left: auto; }

        .menu { flex: 1 1 auto; min-height: 0; overflow-y: auto; overflow-x: hidden; margin: 0; padding: .9rem .75rem; }
        .menu-link {
            display: flex; align-items: center; gap: .8rem;
            padding: .7rem .85rem; border-radius: 10px;
            color: var(--muted); text-decoration: none; white-space: nowrap;
            font-weight: 500; font-size: .92rem;
            transition: background .15s, color .15s;
        }
        .menu-link > i:first-child { font-size: 1.15rem; width: 1.3rem; text-align: center; flex: 0 0 1.3rem; }
        .menu-link:hover { background: var(--navy-700); color: var(--text); }
        .menu-link.active { background: var(--navy-600); color: #fff; box-shadow: inset 3px 0 0 var(--brass); }
        .menu-link .caret { margin-left: auto; font-size: .75rem; transition: transform .2s; }
        .menu-link[aria-expanded="true"] { color: var(--text); }
        .menu-link[aria-expanded="true"] .caret { transform: rotate(180deg); }

        .submenu { margin: .15rem 0 .35rem 1.65rem; padding-left: .9rem; border-left: 1px solid var(--line); }
        .sub-link { display: block; padding: .45rem .7rem; border-radius: 8px; color: var(--muted); font-size: .88rem; text-decoration: none; white-space: nowrap; }
        .sub-link:hover { color: var(--text); background: var(--navy-700); }
        .sub-link.active { color: var(--brass); font-weight: 600; }

        /* Info pengguna di bawah sidebar */
        .user-card {
            display: flex; align-items: center; gap: .75rem;
            margin: .75rem; padding: .8rem .9rem;
            background: var(--navy-950); border: 1px solid var(--line); border-radius: 14px;
        }
        .user-mini {
            display: none; width: 38px; height: 38px; border-radius: 50%;
            background: var(--brass); color: var(--navy-950); font-weight: 700;
            place-items: center; flex: 0 0 38px;
        }
        .user-info { min-width: 0; }
        .user-name  { font-weight: 600; font-size: .92rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .user-email { font-size: .8rem; color: var(--muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-bottom: .5rem; }
        .user-role  { display: inline-block; padding: .15rem .6rem; border-radius: 999px; font-size: .75rem; font-weight: 600; background: rgba(227,184,98,.14); color: var(--brass); }

        .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(2, 6, 20, .65); z-index: 1025; }

        /* ---------- Topbar ---------- */
        .app-main { transition: margin-left .25s ease; }
        .app-topbar {
            position: sticky; top: 0; z-index: 1020;
            display: flex; align-items: center; gap: .75rem;
            height: 64px; padding: 0 1rem;
            background: rgba(10, 17, 40, .88); backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--line);
        }
        .topbar-title { font-size: 1.05rem; font-weight: 600; margin: 0; }
        .icon-btn { color: var(--text); border: 0; padding: .35rem .55rem; border-radius: 10px; }
        .icon-btn:hover { background: var(--navy-700); color: #fff; }

        .profile-trigger {
            display: flex; align-items: center; gap: .6rem;
            background: var(--navy-800); border: 1px solid var(--line);
            border-radius: 999px; padding: .25rem .8rem .25rem .3rem; color: var(--text);
        }
        .profile-trigger:hover { background: var(--navy-700); }
        .avatar { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; display: inline-grid; place-items: center; }
        .avatar-initial { background: var(--brass); color: var(--navy-950); font-weight: 700; }
        .profile-name { font-size: .88rem; font-weight: 500; }
        .profile-menu { min-width: 180px; padding: .4rem; border-radius: 12px; }
        .profile-menu .dropdown-item { border-radius: 8px; font-size: .9rem; padding: .5rem .75rem; }

        /* ---------- Content ---------- */
        .app-content { padding: 1.25rem 1rem 2rem; }
        .page-head h2 { font-size: 1.35rem; font-weight: 700; margin: 0 0 .2rem; }
        .page-head p  { color: var(--muted); margin-bottom: 1.25rem; }

        .stat-strip {
            display: grid; grid-template-columns: repeat(2, 1fr);
            background: var(--navy-800); border: 1px solid var(--line);
            border-radius: 16px; overflow: hidden; margin-bottom: 1.25rem;
        }
        .stat { padding: 1.1rem 1.25rem; border-right: 1px solid var(--line); border-bottom: 1px solid var(--line); }
        .stat:nth-child(2n) { border-right: 0; }
        .stat:nth-last-child(-n+2) { border-bottom: 0; }
        .stat-label { display: block; color: var(--muted); font-size: .82rem; margin-bottom: .25rem; }
        .stat-value { font-size: 1.8rem; font-weight: 700; line-height: 1.1; }
        .stat-alert .stat-value { color: var(--brass); }

        .surface { background: var(--navy-800); border: 1px solid var(--line); border-radius: 16px; padding: 1.1rem 1.25rem; }
        .surface-head { display: flex; align-items: center; justify-content: space-between; gap: .75rem; margin-bottom: .75rem; }
        .surface-head h3 { font-size: 1rem; font-weight: 600; margin: 0; }

        .table { --bs-table-bg: transparent; --bs-table-color: var(--text); --bs-table-border-color: var(--line); font-size: .9rem; }
        .table thead th { color: var(--muted); font-weight: 500; font-size: .82rem; white-space: nowrap; }
        .table td { white-space: nowrap; }

        .btn-outline-brass { color: var(--brass); border-color: rgba(227,184,98,.45); }
        .btn-outline-brass:hover { background: var(--brass); color: var(--navy-950); }

        .badge-soft { padding: .22rem .65rem; border-radius: 999px; font-size: .76rem; font-weight: 600; }
        .badge-soft.ok   { background: rgba(96,165,250,.15); color: #8ec1ff; }
        .badge-soft.done { background: rgba(74,222,128,.14); color: #7be0a2; }
        .badge-soft.late { background: rgba(248,113,113,.15); color: #ff9a9a; }

        /* ---------- Mobile: sidebar tersembunyi, tombol hamburger membukanya ---------- */
        @media (max-width: 991.98px) {
            .app-sidebar { width: min(var(--sidebar-w), 86vw); transform: translateX(-100%); }
            body.sidebar-open .app-sidebar { transform: none; }
            body.sidebar-open .sidebar-backdrop { display: block; }
            body.sidebar-open { overflow: hidden; }
        }

        /* ---------- Desktop: hamburger = ciutkan ke ikon / tampilkan nama ---------- */
        @media (min-width: 992px) {
            .sb-close { display: none; }
            .app-main { margin-left: var(--sidebar-w); }
            .app-topbar { padding: 0 1.75rem 0 1.25rem; }
            .app-content { padding: 1.75rem; }
            .stat-strip { grid-template-columns: repeat(4, 1fr); }
            .stat, .stat:nth-child(2n) { border-bottom: 0; border-right: 1px solid var(--line); }
            .stat:last-child { border-right: 0; }

            .sidebar-collapsed .app-sidebar { width: var(--sidebar-w-mini); }
            .sidebar-collapsed .app-main { margin-left: var(--sidebar-w-mini); }

            .sidebar-collapsed .brand-text,
            .sidebar-collapsed .menu-link span,
            .sidebar-collapsed .menu-link .caret,
            .sidebar-collapsed .submenu,
            .sidebar-collapsed .user-info { display: none !important; }

            .sidebar-collapsed .sb-head { justify-content: center; padding: 0; }
            .sidebar-collapsed .menu { padding: .9rem .6rem; }
            .sidebar-collapsed .menu-link { justify-content: center; padding: .75rem 0; }
            .sidebar-collapsed .menu-link.active { box-shadow: none; }
            .sidebar-collapsed .user-card { justify-content: center; padding: .6rem 0; margin: .75rem .6rem; background: transparent; border-color: transparent; }
            .sidebar-collapsed .user-mini { display: grid; }
        }

        @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
    </style>
</head>
<body>

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
                    <li><a href="{{ url('/kategori') }}" class="sub-link {{ request()->is('kategori*') ? 'active' : '' }}">Kategori</a></li>
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

    {{-- ================= MAIN ================= --}}
    <div class="app-main">

        {{-- Navbar --}}
        <header class="app-topbar">
            <button id="sidebarToggle" class="btn icon-btn" type="button" aria-controls="sidebar" aria-expanded="true" aria-label="Buka/tutup menu">
                <i class="bi bi-list fs-4"></i>
            </button>

            <h1 class="topbar-title">Dashboard</h1>

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

        {{-- Konten --}}
        <main class="app-content">
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
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const root    = document.documentElement;
            const body    = document.body;
            const toggle  = document.getElementById('sidebarToggle');
            const desktop = window.matchMedia('(min-width: 992px)');

            const isCollapsed = () => root.classList.contains('sidebar-collapsed');

            function syncAria() {
                const expanded = desktop.matches ? !isCollapsed() : body.classList.contains('sidebar-open');
                toggle.setAttribute('aria-expanded', expanded);
            }

            function setCollapsed(state) {
                root.classList.toggle('sidebar-collapsed', state);
                try { localStorage.setItem('sidebar', state ? 'collapsed' : 'expanded'); } catch (e) {}
                syncAria();
            }

            // Hamburger: desktop = ciutkan/lebarkan, mobile = buka/tutup
            toggle.addEventListener('click', () => {
                if (desktop.matches) setCollapsed(!isCollapsed());
                else { body.classList.toggle('sidebar-open'); syncAria(); }
            });

            // Tutup di mobile (tombol X, backdrop, Esc)
            const closeMobile = () => { body.classList.remove('sidebar-open'); syncAria(); };
            document.querySelectorAll('[data-sidebar-close]').forEach(el => el.addEventListener('click', closeMobile));
            document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMobile(); });
            desktop.addEventListener('change', () => { closeMobile(); syncAria(); });

            // Saat sidebar berupa ikon, klik Data/Transaksi melebarkan sidebar lalu membuka dropdown-nya
            document.querySelectorAll('.menu-link[data-bs-toggle="collapse"]').forEach(link => {
                link.addEventListener('click', e => {
                    if (desktop.matches && isCollapsed()) {
                        e.preventDefault();
                        e.stopPropagation();
                        setCollapsed(false);
                        const target = document.querySelector(link.getAttribute('href'));
                        bootstrap.Collapse.getOrCreateInstance(target, { toggle: false }).show();
                    }
                });
            });

            syncAria();
        });
    </script>
</body>
</html>
