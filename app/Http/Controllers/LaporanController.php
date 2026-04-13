<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan:read');
    }

    /**
     * Rekap transaksi dengan filter rentang tanggal.
     */
    public function index(Request $request)
    {
        $request->validate([
            'dari'   => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
        ]);

        $dari   = $request->filled('dari') ? $request->dari : now()->startOfMonth()->toDateString();
        $sampai = $request->filled('sampai') ? $request->sampai : now()->toDateString();

        $transaksis = Transaksi::with(['kendaraan', 'tarif', 'petugas', 'areaParkir'])
            ->where('status', 'keluar')
            ->whereBetween('waktu_keluar', [$dari . ' 00:00:00', $sampai . ' 23:59:59'])
            ->latest('waktu_keluar')
            ->paginate(20)
            ->withQueryString();

        // Summary
        $totalTransaksi  = $transaksis->total();
        $totalPendapatan = Transaksi::where('status', 'keluar')
            ->whereBetween('waktu_keluar', [$dari . ' 00:00:00', $sampai . ' 23:59:59'])
            ->sum('biaya_total');

        // Per jenis kendaraan
        $perJenis = Transaksi::selectRaw('tb_kendaraan.jenis_kendaraan, COUNT(*) as jumlah, SUM(tb_transaksi.biaya_total) as total')
            ->join('tb_kendaraan', 'tb_transaksi.id_kendaraan', '=', 'tb_kendaraan.id_parkir')
            ->where('tb_transaksi.status', 'keluar')
            ->whereBetween('tb_transaksi.waktu_keluar', [$dari . ' 00:00:00', $sampai . ' 23:59:59'])
            ->groupBy('tb_kendaraan.jenis_kendaraan')
            ->get();

        return view('laporan.index', compact(
            'transaksis',
            'dari',
            'sampai',
            'totalTransaksi',
            'totalPendapatan',
            'perJenis',
        ));
    }
}
