<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Parkir — {{ $transaksi->kendaraan->plat_nomor }}</title>
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
        .struk {
            background: white;
            width: 320px;
            padding: 24px 20px;
            border: 1px dashed #ccc;
            border-radius: 4px;
        }
        .struk-header {
            text-align: center;
            border-bottom: 1px dashed #999;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        .struk-header h1 { font-size: 18px; font-weight: bold; letter-spacing: 1px; }
        .struk-header p { font-size: 11px; color: #555; margin-top: 2px; }
        .struk-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 6px;
        }
        .struk-row .label { color: #555; }
        .struk-row .value { font-weight: 600; text-align: right; max-width: 55%; }
        .struk-divider {
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }
        .struk-total {
            display: flex;
            justify-content: space-between;
            font-size: 15px;
            font-weight: bold;
            margin-top: 8px;
        }
        .struk-footer {
            text-align: center;
            font-size: 11px;
            color: #777;
            margin-top: 14px;
            border-top: 1px dashed #999;
            padding-top: 10px;
        }
        .btn-print {
            display: block;
            width: 320px;
            margin: 16px auto 0;
            padding: 10px;
            background: #1a1a1a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        .btn-back {
            display: block;
            width: 320px;
            margin: 8px auto 0;
            padding: 10px;
            background: transparent;
            color: #555;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        @media print {
            body { background: white; padding: 0; }
            .struk { border: none; box-shadow: none; }
            .btn-print, .btn-back { display: none; }
        }
    </style>
</head>
<body>
    <div>
        <div class="struk">
            <div class="struk-header">
                <h1>PARKIR 2077</h1>
                <p>Struk Pembayaran Parkir</p>
                <p>{{ now()->format('d/m/Y H:i:s') }}</p>
            </div>

            <div class="struk-row">
                <span class="label">No. Transaksi</span>
                <span class="value">#{{ str_pad($transaksi->id_parkir, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="struk-row">
                <span class="label">Plat Nomor</span>
                <span class="value">{{ $transaksi->kendaraan->plat_nomor }}</span>
            </div>
            <div class="struk-row">
                <span class="label">Jenis</span>
                <span class="value">{{ $transaksi->kendaraan->jenis_kendaraan }}</span>
            </div>
            <div class="struk-row">
                <span class="label">Pemilik</span>
                <span class="value">{{ $transaksi->kendaraan->pemilik }}</span>
            </div>

            <div class="struk-divider"></div>

            <div class="struk-row">
                <span class="label">Area Parkir</span>
                <span class="value">{{ $transaksi->areaParkir->nama_area }}</span>
            </div>
            <div class="struk-row">
                <span class="label">Waktu Masuk</span>
                <span class="value">{{ $transaksi->waktu_masuk->format('d/m/Y H:i') }}</span>
            </div>
            <div class="struk-row">
                <span class="label">Waktu Keluar</span>
                <span class="value">{{ $transaksi->waktu_keluar->format('d/m/Y H:i') }}</span>
            </div>
            <div class="struk-row">
                <span class="label">Durasi</span>
                <span class="value">{{ $transaksi->durasi_jam }} jam</span>
            </div>
            <div class="struk-row">
                <span class="label">Tarif / Jam</span>
                <span class="value">Rp {{ number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}</span>
            </div>

            <div class="struk-divider"></div>

            <div class="struk-total">
                <span>TOTAL BAYAR</span>
                <span>Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}</span>
            </div>

            <div class="struk-divider"></div>

            <div class="struk-row">
                <span class="label">Petugas</span>
                <span class="value">{{ $transaksi->petugas?->name ?? '—' }}</span>
            </div>

            <div class="struk-footer">
                <p>Terima kasih telah menggunakan</p>
                <p>layanan parkir kami.</p>
                <p style="margin-top:6px">★ Parkir 2077 ★</p>
            </div>
        </div>

        <button class="btn-print" onclick="window.print()">🖨️ Cetak Struk</button>
        <a href="{{ route('transaksi.index') }}" class="btn-back">← Kembali ke Transaksi</a>
    </div>
</body>
</html>
