<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TarifController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tarif:read')->only(['index', 'show']);
        $this->middleware('permission:tarif:create')->only(['create', 'store']);
        $this->middleware('permission:tarif:edit')->only(['edit', 'update']);
        $this->middleware('permission:tarif:delete')->only(['destroy']);
    }

    public function index()
    {
        $tarifs = Tarif::latest()->paginate(10);
        return view('tarif.index', compact('tarifs'));
    }

    public function create()
    {
        return view('tarif.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_kendaraan' => ['required', Rule::in(['motor', 'mobil', 'lainnya'])],
            'tarif_per_jam'   => 'required|numeric|min:0',
        ]);

        Tarif::create($validated);

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil ditambahkan.');
    }

    public function edit(Tarif $tarif)
    {
        return view('tarif.edit', compact('tarif'));
    }

    public function update(Request $request, Tarif $tarif)
    {
        $validated = $request->validate([
            'jenis_kendaraan' => ['required', Rule::in(['motor', 'mobil', 'lainnya'])],
            'tarif_per_jam'   => 'required|numeric|min:0',
        ]);

        $tarif->update($validated);

        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil diperbarui.');
    }

    public function destroy(Tarif $tarif)
    {
        $tarif->delete();
        return redirect()->route('tarif.index')->with('success', 'Tarif berhasil dihapus.');
    }
}
