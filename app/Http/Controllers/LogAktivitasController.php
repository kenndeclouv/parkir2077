<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;

class LogAktivitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:log-aktivitas:read');
    }

    public function index()
    {
        $logs = LogAktivitas::with('user')
            ->latest('waktu_aktivitas')
            ->paginate(20);

        return view('log-aktivitas.index', compact('logs'));
    }
}
