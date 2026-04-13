<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Masuk — {{ $transaksi->kendaraan->plat_nomor }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 2rem;
        }
        .wrapper { display: flex; flex-direction: column; align-items: center; gap: 12px; }
        .tiket {
            background: white;
            width: 320px;
            padding: 24px 20px;
            border: 1px dashed #ccc;
            border-radius: 4px;
            position: relative;
        }
        /* Notch kiri-kanan seperti tiket bioskop */
        .tiket::before,
        .tiket::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            background: #f5f5f5;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }
        .tiket::before { left: -9px; }
        .tiket::after  { right: -9px; }

        .tiket-header {
            text-align: center;
            border-bottom: 2px dashed #222;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        .tiket-header .badge {
            display: inline-block;
            background: #1a1a1a;
            color: white;
            font-size: 10px;
            letter-spacing: 2px;
            padding: 3px 10px;
            border-radius: 2px;
            margin-bottom: 6px;
        }
        .tiket-header h1 { font-size: 20px; font-weight: bold; letter-spacing: 2px; }
        .tiket-header .sub { font-size: 11px; color: #555; margin-top: 2px; }

        .plat {
            text-align: center;
            margin: 14px 0;
            padding: 10px;
            border: 2px solid #1a1a1a;
            border-radius: 6px;
        }
        .plat span { font-size: 28px; font-weight: bold; letter-spacing: 4px; }

        .tiket-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 6px;
        }
        .tiket-row .label { color: #555; }
        .tiket-row .value { font-weight: 600; text-align: right; max-width: 55%; }

        .tiket-divider {
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }

        .no-tiket {
            text-align: center;
            font-size: 11px;
            color: #888;
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
            letter-spacing: 1px;
        }
        .no-tiket strong { font-size: 16px; color: #222; display: block; margin-top: 2px; }

        .tiket-footer {
            text-align: center;
            font-size: 10px;
            color: #aaa;
            margin-top: 10px;
            letter-spacing: 1px;
        }

        /* Tombol aksi */
        .btn-print {
            display: block;
            width: 320px;
            padding: 11px;
            background: #1a1a1a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-family: 'Courier New', monospace;
        }
        .btn-back {
            display: block;
            width: 320px;
            padding: 11px;
            background: transparent;
            color: #555;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-family: 'Courier New', monospace;
        }
        @media print {
            body { background: white; padding: 0; }
            .tiket { border: 1px dashed #ccc; box-shadow: none; }
            .tiket::before, .tiket::after { display: none; }
            .btn-print, .btn-back { display: none; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="tiket">
            <div class="tiket-header">
                <div class="badge">TIKET MASUK</div>
                <h1>PARKIR 2077</h1>
                <div class="sub">{{ $transaksi->waktu_masuk->translatedFormat('l, d F Y') }}</div>
            </div>

            <div class="plat">
                <span>{{ $transaksi->kendaraan->plat_nomor }}</span>
            </div>

            <div class="tiket-divider"></div>

            <div class="tiket-row">
                <span class="label">Jenis Kendaraan</span>
                <span class="value">{{ ucfirst($transaksi->kendaraan->jenis_kendaraan) }}</span>
            </div>
            <div class="tiket-row">
                <span class="label">Pemilik</span>
                <span class="value">{{ $transaksi->kendaraan->pemilik }}</span>
            </div>

            <div class="tiket-divider"></div>

            <div class="tiket-row">
                <span class="label">Area Parkir</span>
                <span class="value">{{ $transaksi->areaParkir->nama_area }}</span>
            </div>
            <div class="tiket-row">
                <span class="label">Waktu Masuk</span>
                <span class="value">{{ $transaksi->waktu_masuk->format('H:i') }}</span>
            </div>
            <div class="tiket-row">
                <span class="label">Tarif / Jam</span>
                <span class="value">Rp {{ number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}</span>
            </div>
            <div class="tiket-row">
                <span class="label">Petugas</span>
                <span class="value">{{ $transaksi->petugas?->name ?? '—' }}</span>
            </div>

            <div class="no-tiket">
                NO. TIKET
                <strong>#{{ str_pad($transaksi->id_parkir, 6, '0', STR_PAD_LEFT) }}</strong>
            </div>

            <div class="tiket-footer">
                ★ SIMPAN TIKET INI ★<br>
                Tunjukkan saat kendaraan keluar
            </div>
        </div>

        <button class="btn-print" onclick="window.print()">🖨️ Cetak Tiket</button>
        <a href="{{ route('transaksi.index') }}" class="btn-back">← Kembali ke Transaksi</a>
    </div>

    <script>
        // Auto-print begitu halaman terbuka
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>
