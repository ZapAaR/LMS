<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Login Admin & Petugas</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;800&family=Manrope:wght@400;500;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --midnight: #0B1437;
            --navy: #16245C;
            --steel: #3A4F9E;
            --ice: #E8EDFB;
            --gold: #F2B84B;
            --panel: #FFFFFF;
            --text: #0B1437;
            --muted: #5B6486;
            --field: #EEF1FA;
            --page: #0B1437;
            box-sizing: border-box;
            padding-top: env(safe-area-inset-top, 0px);
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        @media (prefers-color-scheme:dark) {
            :root:not([data-theme="light"]) {
                --panel: #101A45;
                --text: #EAF0FF;
                --muted: #9AA6D1;
                --field: #1B2858;
                --page: #060B22;
            }
        }

        :root[data-theme="dark"] {
            --panel: #101A45;
            --text: #EAF0FF;
            --muted: #9AA6D1;
            --field: #1B2858;
            --page: #060B22;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit
        }

        html {
            scroll-padding-top: env(safe-area-inset-top, 0px)
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background: var(--page);
            font-family: 'Manrope', system-ui, sans-serif;
            color: var(--text)
        }

        .box {
            position: relative;
            width: min(920px, 100%);
            height: 560px;
            background: var(--panel);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .45)
        }

        /* Forms */
        .form {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 52px;
            text-align: center;
            transition: opacity .5s ease, transform .7s cubic-bezier(.65, 0, .25, 1), visibility .5s
        }

        .form.petugas {
            left: 0
        }

        .form.admin {
            left: 50%
        }

        /* Mode admin: overlay di kiri, form admin tampil */
        .box .form.petugas {
            opacity: 0;
            visibility: hidden;
            transform: translateX(18%)
        }

        .box.petugas-mode .form.petugas {
            opacity: 1;
            visibility: visible;
            transform: none
        }

        .box.petugas-mode .form.admin {
            opacity: 0;
            visibility: hidden;
            transform: translateX(-18%)
        }

        .box:not(.petugas-mode) .form.admin {
            opacity: 1;
            visibility: visible;
            transform: none
        }

        h1 {
            font: 800 2.1rem/1.1 'Sora', sans-serif;
            margin: 0 0 8px
        }

        .sub {
            margin: 0 0 26px;
            color: var(--muted);
            font-size: .92rem
        }

        .field {
            width: 100%;
            position: relative;
            margin-bottom: 14px
        }

        .field input {
            width: 100%;
            border: 2px solid transparent;
            background: var(--field);
            color: var(--text);
            padding: 15px 16px;
            font: 500 .95rem 'Manrope', sans-serif;
            border-radius: 10px;
            outline: none;
            transition: border-color .2s, background .2s
        }

        .field input:focus {
            border-color: var(--steel)
        }

        .field.has-eye input {
            padding-right: 48px
        }

        .eye {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border: 0;
            background: none;
            color: var(--muted);
            cursor: pointer;
            border-radius: 8px;
            display: grid;
            place-items: center
        }

        .eye:hover {
            color: var(--text)
        }

        .eye:focus-visible,
        .btn:focus-visible,
        .ghost:focus-visible {
            outline: 3px solid var(--gold);
            outline-offset: 2px
        }

        .eye svg {
            width: 20px;
            height: 20px
        }

        .eye .off {
            display: none
        }

        .eye.on .off {
            display: block
        }

        .eye.on .open {
            display: none
        }

        .btn {
            position: relative;
            overflow: hidden;
            margin-top: 10px;
            border: 0;
            cursor: pointer;
            color: #fff;
            background: linear-gradient(135deg, var(--steel), var(--navy));
            font: 700 .85rem 'Manrope', sans-serif;
            letter-spacing: .06em;
            padding: 14px 44px;
            border-radius: 999px;
            transition: transform .15s, box-shadow .2s
        }

        .btn:hover {
            box-shadow: 0 8px 22px rgba(22, 36, 92, .45)
        }

        .btn:active {
            transform: scale(.95)
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .45);
            transform: scale(0);
            animation: rip .6s ease-out forwards;
            pointer-events: none
        }

        @keyframes rip {
            to {
                transform: scale(4);
                opacity: 0
            }
        }

        /* Overlay */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            z-index: 5;
            color: #fff;
            background: radial-gradient(120% 90% at 0% 0%, var(--steel) 0%, var(--navy) 45%, var(--midnight) 100%);
            transition: transform .7s cubic-bezier(.65, 0, .25, 1)
        }

        .box.petugas-mode .overlay {
            transform: translateX(100%)
        }

        .overlay::after {
            content: "";
            position: absolute;
            inset: auto -20% -30% auto;
            width: 70%;
            aspect-ratio: 1;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .12);
            box-shadow: 0 0 0 38px rgba(255, 255, 255, .03), 0 0 0 80px rgba(255, 255, 255, .02)
        }

        .msg {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 52px;
            text-align: center;
            transition: opacity .4s ease .15s
        }

        .msg p {
            margin: 0 0 28px;
            color: #C9D3F5;
            line-height: 1.55
        }

        .msg h2 {
            font: 800 2.1rem/1.1 'Sora', sans-serif;
            margin: 0 0 14px
        }

        .msg.to-admin {
            opacity: 0;
            pointer-events: none
        }

        .box.petugas-mode .msg.to-admin {
            opacity: 1;
            pointer-events: auto
        }

        .box.petugas-mode .msg.to-petugas {
            opacity: 0;
            pointer-events: none
        }

        .ghost {
            position: relative;
            overflow: hidden;
            border: 1.5px solid #fff;
            background: transparent;
            color: #fff;
            cursor: pointer;
            font: 700 .85rem 'Manrope', sans-serif;
            letter-spacing: .06em;
            padding: 13px 40px;
            border-radius: 999px;
            transition: background .2s, color .2s, transform .15s
        }

        .ghost:hover {
            background: #fff;
            color: var(--navy)
        }

        .ghost:active {
            transform: scale(.95)
        }

        .toast {
            position: fixed;
            left: 50%;
            bottom: calc(24px + env(safe-area-inset-bottom, 0px));
            transform: translate(-50%, 30px);
            opacity: 0;
            background: var(--gold);
            color: var(--midnight);
            font-weight: 700;
            padding: 12px 22px;
            border-radius: 999px;
            transition: .35s;
            pointer-events: none
        }

        .toast.show {
            opacity: 1;
            transform: translate(-50%, 0)
        }

        /* Mobile */
        @media (max-width:720px) {
            .box {
                height: auto;
                display: flex;
                flex-direction: column
            }

            .form {
                position: static;
                width: 100%;
                padding: 44px 28px 30px;
                transform: none !important
            }

            .box:not(.petugas-mode) .form.petugas,
            .box.petugas-mode .form.admin {
                display: none
            }

            .overlay {
                position: relative;
                width: 100%;
                height: 230px;
                order: 2;
                transform: none !important
            }

            .msg {
                padding: 0 28px
            }

            .msg h2 {
                font-size: 1.6rem
            }

            .msg p {
                margin-bottom: 18px
            }
        }

        @media (prefers-reduced-motion:reduce) {
            * {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important
            }
        }
    </style>
</head>

<body>
    <div class="box" id="box">

        <!-- Form petugas (kiri) -->
        <form class="form petugas" data-role="Petugas" autocomplete="off">
            <h1>Masuk Petugas</h1>
            <p class="sub">Gunakan akun petugas Anda.</p>
            <div class="field"><input type="text" placeholder="Username atau email" aria-label="Username petugas"
                    required></div>
            <div class="field has-eye">
                <input type="password" placeholder="Kata sandi" aria-label="Kata sandi petugas" required>
                <button type="button" class="eye" aria-label="Tampilkan kata sandi">
                    <svg class="open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg class="off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M17.94 17.94A10.9 10.9 0 0 1 12 20C5 20 1 12 1 12a18.5 18.5 0 0 1 5.06-5.94M9.9 4.24A9.1 9.1 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22" />
                    </svg>
                </button>
            </div>
            <button class="btn" type="submit">MASUK</button>
        </form>

        <!-- Form admin (kanan) -->
        <form class="form admin" data-role="Admin" autocomplete="off">
            <h1>Masuk Admin</h1>
            <p class="sub">Gunakan akun administrator.</p>
            <div class="field"><input type="text" placeholder="Username atau email" aria-label="Username admin"
                    required></div>
            <div class="field has-eye">
                <input type="password" placeholder="Kata sandi" aria-label="Kata sandi admin" required>
                <button type="button" class="eye" aria-label="Tampilkan kata sandi">
                    <svg class="open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg class="off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M17.94 17.94A10.9 10.9 0 0 1 12 20C5 20 1 12 1 12a18.5 18.5 0 0 1 5.06-5.94M9.9 4.24A9.1 9.1 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22" />
                    </svg>
                </button>
            </div>
            <button class="btn" type="submit">MASUK</button>
        </form>

        <!-- Panel geser -->
        <div class="overlay">
            <div class="msg to-petugas">
                <h2>Halo, Petugas?</h2>
                <p>Masuk di sini bila Anda bertugas di lapangan atau loket.</p>
                <button class="ghost" type="button" data-go="petugas">LOGIN PETUGAS</button>
            </div>
            <div class="msg to-admin">
                <h2>Halo, Admin?</h2>
                <p>Masuk di sini untuk mengelola data dan akun pengguna.</p>
                <button class="ghost" type="button" data-go="admin">LOGIN ADMIN</button>
            </div>
        </div>
    </div>
    <div class="toast" id="toast" role="status"></div>

    <script>
        const box = document.getElementById('box');
        const toast = document.getElementById('toast');

        // Efek ripple saat tombol diklik
        function ripple(e) {
            const b = e.currentTarget,
                r = b.getBoundingClientRect(),
                s = Math.max(r.width, r.height);
            const d = document.createElement('span');
            d.className = 'ripple';
            d.style.cssText =
                `width:${s}px;height:${s}px;left:${e.clientX - r.left - s/2}px;top:${e.clientY - r.top - s/2}px`;
            b.appendChild(d);
            setTimeout(() => d.remove(), 600);
        }
        document.querySelectorAll('.btn,.ghost').forEach(b => b.addEventListener('click', ripple));

        // Geser panel antara Admin dan Petugas
        document.querySelectorAll('[data-go]').forEach(b => b.addEventListener('click', () => {
            box.classList.toggle('petugas-mode', b.dataset.go === 'petugas');
        }));

        // Show / hide password
        document.querySelectorAll('.eye').forEach(btn => btn.addEventListener('click', () => {
            const input = btn.parentElement.querySelector('input');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.classList.toggle('on', show);
            btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        }));

        // Submit 
        document.querySelectorAll('form').forEach(f => f.addEventListener('submit', e => {
            e.preventDefault();
            // TODO: kirim data ke server sesuai role (f.dataset.role)
            toast.textContent = 'Login ' + f.dataset.role + ' berhasil ';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2200);
        }));
    </script>
</body>

</html>
