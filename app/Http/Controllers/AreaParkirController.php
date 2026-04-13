<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use Illuminate\Http\Request;

class AreaParkirController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:area-parkir:read')->only(['index', 'show']);
        $this->middleware('permission:area-parkir:create')->only(['create', 'store']);
        $this->middleware('permission:area-parkir:edit')->only(['edit', 'update']);
        $this->middleware('permission:area-parkir:delete')->only(['destroy']);
    }

    public function index()
    {
        $areas = AreaParkir::latest()->paginate(10);
        return view('area-parkir.index', compact('areas'));
    }

    public function create()
    {
        return view('area-parkir.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_area' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $validated['terisi'] = 0;
        AreaParkir::create($validated);

        return redirect()->route('area-parkir.index')->with('success', 'Area parkir berhasil ditambahkan.');
    }

    public function edit(AreaParkir $areaParkir)
    {
        return view('area-parkir.edit', compact('areaParkir'));
    }

    public function update(Request $request, AreaParkir $areaParkir)
    {
        $validated = $request->validate([
            'nama_area' => 'required|string|max:50',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $areaParkir->update($validated);

        return redirect()->route('area-parkir.index')->with('success', 'Area parkir berhasil diperbarui.');
    }

    public function destroy(AreaParkir $areaParkir)
    {
        $areaParkir->delete();
        return redirect()->route('area-parkir.index')->with('success', 'Area parkir berhasil dihapus.');
    }
}
