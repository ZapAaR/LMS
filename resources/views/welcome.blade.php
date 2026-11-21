
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PerpusKita - Sistem Manajemen Perpustakaan</title>

    <style>
        :root {
            --primary: #15803d;
            --primary-dark: #14532d;
            --light-green: #dcfce7;
            --background: #f8faf8;
            --text: #17251c;
            --muted: #64748b;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--background);
            color: var(--text);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            width: 90%;
            max-width: 1150px;
            margin: auto;
        }

        /* NAVBAR */
        header {
            background: rgba(255,255,255,0.95);
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        nav {
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: bold;
            color: var(--primary-dark);
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            background: var(--light-green);
            border-radius: 10px;
            font-size: 21px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            font-size: 14px;
            color: #475569;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .btn {
            display: inline-block;
            padding: 12px 23px;
            background: var(--primary);
            color: white;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            transition: .2s;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--light-green);
            color: var(--primary-dark);
        }

        /* HERO */
        .hero {
            padding: 85px 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 60px;
        }

        .hero h1 {
            font-size: clamp(35px, 5vw, 55px);
            line-height: 1.15;
            margin-bottom: 22px;
            letter-spacing: -1.5px;
        }

        .hero h1 span {
            color: var(--primary);
        }

        .hero p {
            color: var(--muted);
            font-size: 16px;
            max-width: 500px;
            margin-bottom: 30px;
        }

        .hero-buttons {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .hero-visual {
            min-height: 390px;
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .library-art {
            font-size: 125px;
            text-align: center;
        }

        .library-art p {
            font-size: 16px;
            font-weight: bold;
            color: var(--primary-dark);
            margin-top: 10px;
        }

        .floating-card {
            position: absolute;
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
            font-size: 13px;
        }

        .floating-card strong {
            display: block;
            color: var(--primary-dark);
            font-size: 20px;
        }

        .card-one {
            top: 45px;
            left: 20px;
        }

        .card-two {
            bottom: 45px;
            right: 20px;
        }

        /* FEATURES */
        .features {
            padding: 85px 0;
            background: white;
        }

        .section-heading {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 45px;
        }

        .section-heading span {
            color: var(--primary);
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .section-heading h2 {
            font-size: 34px;
            margin: 10px 0;
        }

        .section-heading p {
            color: var(--muted);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .feature-card {
            padding: 28px;
            border: 1px solid #e5e7eb;
            border-radius: 15px;
            transition: .2s;
            background: white;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: #86efac;
            box-shadow: 0 12px 25px rgba(21,128,61,.08);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            display: grid;
            place-items: center;
            background: var(--light-green);
            border-radius: 12px;
            font-size: 24px;
            margin-bottom: 18px;
        }

        .feature-card h3 {
            font-size: 17px;
            margin-bottom: 8px;
        }

        .feature-card p {
            font-size: 14px;
            color: var(--muted);
        }

        /* FOOTER */
        footer {
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 25px 0;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: 13px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .nav-links {
                gap: 15px;
            }

            .nav-links a:not(.btn) {
                display: none;
            }

            .hero {
                grid-template-columns: 1fr;
                padding: 60px 0;
                gap: 40px;
            }

            .hero-visual {
                min-height: 300px;
            }

            .library-art {
                font-size: 90px;
            }

            .feature-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .about-content {
                padding: 35px 20px;
            }
        }

        @media (max-width: 480px) {
            .feature-grid {
                grid-template-columns: 1fr;
            }

            .section-heading h2 {
                font-size: 27px;
            }

            .hero h1 {
                font-size: 38px;
            }

            .card-one {
                top: 20px;
                left: 10px;
            }

            .card-two {
                bottom: 20px;
                right: 10px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <header>
        <div class="container">
            <nav>
                <a href="#" class="logo">
                    <span class="logo-icon">📚</span>
                    PerpusKita
                </a>

                <div class="nav-links">
                    <a href="#beranda">Beranda</a>
                    <a href="#fitur">Fitur</a>
                    <a href="#tentang">Tentang</a>
                    <a href="/login" class="btn">Login</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- HERO -->
    <main>
        <section class="container hero" id="beranda">
            <div>

                <h1>
                    Kelola Perpustakaan Lebih <span>Mudah</span> dan Terorganisir
                </h1>

                <p>
                    Kelola data buku, anggota, peminjaman, dan pengembalian
                    dalam satu sistem yang praktis, terstruktur, dan mudah digunakan.
                </p>

                <div class="hero-buttons">
                    <a href="/login" class="btn">Mulai Sekarang →</a>
                    <a href="#fitur" class="btn btn-outline">Pelajari Fitur</a>
                </div>
            </div>

            <div class="hero-visual">
                <div class="library-art">
                    📚
                    <p>Perpustakaan Digital</p>
                </div>

                <div class="floating-card card-one">
                    Total Buku
                    <strong>1.250+</strong>
                </div>

                <div class="floating-card card-two">
                    Pengelolaan
                    <strong>Lebih Mudah</strong>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="features" id="fitur">
            <div class="container">
                <div class="section-heading">
                    <span>Fitur Aplikasi</span>
                    <h2>Semua Kebutuhan Perpustakaan dalam Satu Sistem</h2>
                    <p>
                        Berbagai fitur untuk membantu proses pengelolaan
                        perpustakaan menjadi lebih efisien.
                    </p>
                </div>

                <div class="feature-grid">

                    <div class="feature-card">
                        <div class="feature-icon">📖</div>
                        <h3>Manajemen Buku</h3>
                        <p>
                            Kelola informasi buku, kategori, stok,
                            dan data koleksi perpustakaan.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">👥</div>
                        <h3>Data Anggota</h3>
                        <p>
                            Simpan dan kelola informasi anggota
                            perpustakaan secara terstruktur.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">📥</div>
                        <h3>Peminjaman Buku</h3>
                        <p>
                            Catat transaksi peminjaman buku
                            dengan proses yang lebih praktis.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">📤</div>
                        <h3>Pengembalian Buku</h3>
                        <p>
                            Kelola pengembalian dan pantau
                            status transaksi peminjaman.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">📋</div>
                        <h3>Riwayat Transaksi</h3>
                        <p>
                            Lihat catatan peminjaman dan pengembalian
                            yang pernah dilakukan.
                        </p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>Laporan Perpustakaan</h3>
                        <p>
                            Pantau ringkasan aktivitas dan
                            informasi pengelolaan perpustakaan.
                        </p>
                    </div>

                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer>
        <div class="container footer-content">
            <div class="logo">
                <span class="logo-icon">📚</span>
                PerpusKita
            </div>

            <p>
                © <span id="year"></span> PerpusKita. All rights reserved.
            </p>
        </div>
    </footer>

    <script>
        document.getElementById("year").textContent =
            new Date().getFullYear();
    </script>

</body>
</html>
