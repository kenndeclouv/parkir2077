<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:kendaraan:read')->only(['index', 'show']);
        $this->middleware('permission:kendaraan:create')->only(['create', 'store']);
        $this->middleware('permission:kendaraan:edit')->only(['edit', 'update']);
        $this->middleware('permission:kendaraan:delete')->only(['destroy']);
    }

    public function index()
    {
        $kendaraans = Kendaraan::with('user')->latest()->paginate(10);
        return view('kendaraan.index', compact('kendaraans'));
    }

    public function create()
    {
        $users = User::aktif()->pluck('name', 'id');
        return view('kendaraan.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plat_nomor'      => 'required|string|max:15|unique:tb_kendaraan,plat_nomor',
            'jenis_kendaraan' => 'required|string|max:30',
            'warna'           => 'required|string|max:20',
            'pemilik'         => 'required|string|max:100',
            'id_user'         => 'nullable|exists:users,id',
        ]);

        Kendaraan::create($validated);

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Kendaraan $kendaraan)
    {
        $users = User::aktif()->pluck('name', 'id');
        return view('kendaraan.edit', compact('kendaraan', 'users'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $validated = $request->validate([
            'plat_nomor'      => 'required|string|max:15|unique:tb_kendaraan,plat_nomor,' . $kendaraan->id_parkir . ',id_parkir',
            'jenis_kendaraan' => 'required|string|max:30',
            'warna'           => 'required|string|max:20',
            'pemilik'         => 'required|string|max:100',
            'id_user'         => 'nullable|exists:users,id',
        ]);

        $kendaraan->update($validated);

        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();
        return redirect()->route('kendaraan.index')->with('success', 'Kendaraan berhasil dihapus.');
    }
}
