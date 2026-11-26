<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Member {{ $member->member_code }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 2rem;
            background: #f3f4f6;
            color: #172033;
            font-family: Arial, sans-serif;
        }

        .profile {
            width: min(100%, 760px);
            margin: 0 auto;
            padding: 2.5rem;
            border: 1px solid #d9deea;
            border-radius: 14px;
            background: #fff;
        }

        .profile-header {
            padding-bottom: 1.25rem;
            border-bottom: 2px solid #e3b862;
        }

        h1 {
            margin: 0 0 .35rem;
            font-size: 1.6rem;
        }

        .subtitle,
        .label {
            color: #68738a;
        }

        .details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .item {
            padding: .8rem;
            border: 1px solid #e3e7ef;
            border-radius: 8px;
            overflow-wrap: anywhere;
        }

        .item.full {
            grid-column: 1 / -1;
        }

        .label {
            margin-bottom: .35rem;
            font-size: .82rem;
        }

        .value {
            font-weight: 600;
        }

        .print-button {
            display: block;
            margin: 1.25rem auto 0;
            padding: .7rem 1rem;
            border: 0;
            border-radius: 8px;
            background: #15234a;
            color: white;
            cursor: pointer;
            font-size: .95rem;
        }

        @media (max-width: 560px) {
            body {
                padding: 1rem;
            }

            .profile {
                padding: 1.25rem;
            }

            .details {
                grid-template-columns: 1fr;
            }

            .item.full {
                grid-column: auto;
            }
        }

        @media print {
            body {
                padding: 0;
                background: #fff;
            }

            .profile {
                width: 100%;
                padding: 0;
                border: 0;
            }

            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <main class="profile">
        <header class="profile-header">
            <h1>Profil Member Perpustakaan</h1>
            <div class="subtitle">Informasi keanggotaan perpustakaan</div>
        </header>

        <section class="details" aria-label="Informasi member">
            <div class="item">
                <div class="label">Kode Member</div>
                <div class="value">{{ $member->member_code }}</div>
            </div>
            <div class="item">
                <div class="label">Status</div>
                <div class="value">{{ $member->status === 'active' ? 'Aktif' : 'Tidak aktif' }}</div>
            </div>
            <div class="item">
                <div class="label">Nama</div>
                <div class="value">{{ $member->name }}</div>
            </div>
            <div class="item">
                <div class="label">Email</div>
                <div class="value">{{ $member->email ?: '—' }}</div>
            </div>
            <div class="item">
                <div class="label">Telepon</div>
                <div class="value">{{ $member->phone ?: '—' }}</div>
            </div>
            <div class="item">
                <div class="label">Terdaftar</div>
                <div class="value">{{ $member->created_at?->format('d M Y') ?: '—' }}</div>
            </div>
            <div class="item full">
                <div class="label">Alamat</div>
                <div class="value">{{ $member->address ?: '—' }}</div>
            </div>
        </section>

        <button type="button" class="print-button" onclick="window.print()">
            Cetak / Simpan sebagai PDF
        </button>
    </main>
    <script>
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>