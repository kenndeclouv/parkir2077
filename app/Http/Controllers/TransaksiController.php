<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use App\Models\Tarif;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:transaksi:read')->only(['index', 'show']);
        $this->middleware('permission:transaksi:create')->only(['create', 'store']);
        $this->middleware('permission:transaksi:edit')->only(['edit', 'update']);
        $this->middleware('permission:transaksi:read')->only(['tiket']);
        $this->middleware('permission:struk:cetak')->only(['struk']);
    }

    /**
     * Daftar semua transaksi (bisa filter by status).
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['kendaraan', 'tarif', 'petugas', 'areaParkir'])
            ->latest('waktu_masuk');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transaksis = $query->paginate(15)->withQueryString();

        // Data untuk quick-input form
        $kendaraans = Kendaraan::orderBy('plat_nomor')->get();
        $tarifs = Tarif::all();
        $areas = AreaParkir::all();

        return view('transaksi.index', compact('transaksis', 'kendaraans', 'tarifs', 'areas'));
    }

    /**
     * Form kendaraan masuk parkir.
     */
    public function create()
    {
        $kendaraans = Kendaraan::orderBy('plat_nomor')->get();
        $tarifs = Tarif::all();
        $areas = AreaParkir::all();

        return view('transaksi.create', compact('kendaraans', 'tarifs', 'areas'));
    }

    /**
     * Simpan kendaraan masuk.
     */
    public function store(Request $request)
    {
        $isNewKendaraan = $request->input('new_kendaraan') === '1';

        if ($isNewKendaraan) {
            // Validasi + buat kendaraan baru
            $kendaraanData = $request->validate([
                'plat_nomor' => 'required|string|max:15|unique:tb_kendaraan,plat_nomor',
                'jenis_kendaraan_baru' => 'required|in:motor,mobil,lainnya',
                'warna' => 'required|string|max:20',
                'pemilik' => 'required|string|max:100',
                'id_tarif' => 'required|exists:tb_tarif,id_tarif',
                'id_area' => 'required|exists:tb_area_parkir,id_area',
                'waktu_masuk' => 'required|date',
            ]);

            $kendaraan = Kendaraan::create([
                'plat_nomor' => $kendaraanData['plat_nomor'],
                'jenis_kendaraan' => $kendaraanData['jenis_kendaraan_baru'],
                'warna' => $kendaraanData['warna'],
                'pemilik' => $kendaraanData['pemilik'],
                'id_user' => null,
            ]);

            $validated = [
                'id_kendaraan' => $kendaraan->id_parkir,
                'id_tarif' => $kendaraanData['id_tarif'],
                'id_area' => $kendaraanData['id_area'],
                'waktu_masuk' => $kendaraanData['waktu_masuk'],
            ];
        } else {
            $validated = $request->validate([
                'id_kendaraan' => 'required|exists:tb_kendaraan,id_parkir',
                'id_tarif' => 'required|exists:tb_tarif,id_tarif',
                'id_area' => 'required|exists:tb_area_parkir,id_area',
                'waktu_masuk' => 'required|date',
            ]);
        }

        $validated['id_user'] = auth()->id();
        $validated['status'] = 'masuk';
        $validated['waktu_masuk'] = $validated['waktu_masuk'] ?? now();

        $transaksi = Transaksi::create($validated);
        $transaksi->load(['kendaraan', 'areaParkir', 'tarif', 'petugas']);

        // Update jumlah terisi di area
        $transaksi->areaParkir->increment('terisi');

        // Catat log aktivitas
        LogAktivitas::create([
            'id_user' => auth()->id(),
            'aktivitas' => 'Kendaraan masuk — plat: ' . $transaksi->kendaraan->plat_nomor,
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('transaksi.tiket', $transaksi)->with('success', 'Kendaraan masuk berhasil dicatat.');
    }


    /**
     * Form kendaraan keluar — hitung biaya.
     */
    public function edit(Transaksi $transaksi)
    {
        if ($transaksi->status === 'keluar') {
            return redirect()->route('transaksi.index')->with('error', 'Kendaraan ini sudah keluar.');
        }

        $transaksi->load(['kendaraan', 'tarif', 'areaParkir']);

        return view('transaksi.edit', compact('transaksi'));
    }

    /**
     * Proses kendaraan keluar — hitung durasi & biaya.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'waktu_keluar' => 'required|date|after:' . $transaksi->waktu_masuk,
        ]);

        $waktuMasuk = $transaksi->waktu_masuk;
        $waktuKeluar = \Carbon\Carbon::parse($request->waktu_keluar);

        // Hitung durasi dalam jam (minimal 1 jam)
        $durasi = (int) ceil($waktuMasuk->diffInMinutes($waktuKeluar) / 60);
        $durasi = max(1, $durasi);
        $biayaTotal = $durasi * $transaksi->tarif->tarif_per_jam;

        $transaksi->update([
            'waktu_keluar' => $waktuKeluar,
            'durasi_jam' => $durasi,
            'biaya_total' => $biayaTotal,
            'status' => 'keluar',
        ]);

        // Kurangi jumlah terisi di area
        $transaksi->areaParkir->decrement('terisi');

        // Catat log aktivitas
        LogAktivitas::create([
            'id_user' => auth()->id(),
            'aktivitas' => 'Kendaraan keluar — plat: ' . $transaksi->kendaraan->plat_nomor . ' | Biaya: Rp ' . number_format($biayaTotal, 0, ',', '.'),
            'waktu_aktivitas' => now(),
        ]);

        return redirect()->route('transaksi.struk', $transaksi)->with('success', 'Kendaraan berhasil keluar.');
    }

    /**
     * Tampilkan tiket masuk parkir.
     */
    public function tiket(Transaksi $transaksi)
    {
        $transaksi->load(['kendaraan', 'tarif', 'petugas', 'areaParkir']);

        return view('transaksi.tiket', compact('transaksi'));
    }

    /**
     * Tampilkan struk keluar parkir.
     */
    public function struk(Transaksi $transaksi)
    {
        // $this->authorize('struk:cetak');

        $transaksi->load(['kendaraan', 'tarif', 'petugas', 'areaParkir']);

        return view('transaksi.struk', compact('transaksi'));
    }
}
