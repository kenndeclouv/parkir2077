<x-layouts.app title="Dashboard">
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Dashboard</flux:heading>
                <flux:text class="text-gray-500 mt-1">
                    Selamat datang, <strong>{{ auth()->user()->name }}</strong> —
                    <flux:badge color="blue" size="sm">{{ auth()->user()->getRoleNames()->first() ?? 'user' }}</flux:badge>
                </flux:text>
            </div>
            <flux:text class="text-gray-400 text-sm">{{ now()->translatedFormat('l, d F Y') }}</flux:text>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            @can('users:read')
            <flux:card class="p-5">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <flux:icon name="users" class="size-5 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <flux:text class="text-xs text-gray-500">Total User</flux:text>
                        <flux:heading size="lg">{{ \App\Models\User::count() }}</flux:heading>
                    </div>
                </div>
            </flux:card>
            @endcan

            @can('kendaraan:read')
            <flux:card class="p-5">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <flux:icon name="truck" class="size-5 text-green-600 dark:text-green-400" />
                    </div>
                    <div>
                        <flux:text class="text-xs text-gray-500">Total Kendaraan</flux:text>
                        <flux:heading size="lg">{{ \App\Models\Kendaraan::count() }}</flux:heading>
                    </div>
                </div>
            </flux:card>
            @endcan

            @canany(['transaksi:read', 'laporan:read'])
            <flux:card class="p-5">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <flux:icon name="arrows-right-left" class="size-5 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <div>
                        <flux:text class="text-xs text-gray-500">Transaksi Hari Ini</flux:text>
                        <flux:heading size="lg">
                            {{ \App\Models\Transaksi::whereDate('waktu_masuk', today())->count() }}
                        </flux:heading>
                    </div>
                </div>
            </flux:card>

            <flux:card class="p-5">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <flux:icon name="currency-dollar" class="size-5 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div>
                        <flux:text class="text-xs text-gray-500">Pendapatan Hari Ini</flux:text>
                        <flux:heading size="lg">
                            Rp {{ number_format(\App\Models\Transaksi::where('status', 'keluar')->whereDate('waktu_keluar', today())->sum('biaya_total'), 0, ',', '.') }}
                        </flux:heading>
                    </div>
                </div>
            </flux:card>
            @endcanany

        </div>

        {{-- Kendaraan Masih Di Dalam (status: masuk) --}}
        @canany(['transaksi:read', 'transaksi:create'])
        <flux:card>
            <div class="flex items-center justify-between mb-3">
                <flux:heading size="sm">Kendaraan Masih di Dalam</flux:heading>
                <flux:badge color="green">{{ \App\Models\Transaksi::where('status', 'masuk')->count() }} aktif</flux:badge>
            </div>

            @php
                $aktif = \App\Models\Transaksi::with(['kendaraan', 'areaParkir'])
                    ->where('status', 'masuk')
                    ->latest('waktu_masuk')
                    ->limit(5)
                    ->get();
            @endphp

            @if($aktif->count() > 0)
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Plat Nomor</flux:table.column>
                        <flux:table.column>Area</flux:table.column>
                        <flux:table.column>Waktu Masuk</flux:table.column>
                        <flux:table.column>Durasi</flux:table.column>
                        @can('transaksi:edit')
                        <flux:table.column>Aksi</flux:table.column>
                        @endcan
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach($aktif as $trx)
                            <flux:table.row>
                                <flux:table.cell class="font-bold">{{ $trx->kendaraan?->plat_nomor }}</flux:table.cell>
                                <flux:table.cell>{{ $trx->areaParkir?->nama_area }}</flux:table.cell>
                                <flux:table.cell class="text-gray-500">{{ $trx->waktu_masuk?->format('H:i') }}</flux:table.cell>
                                <flux:table.cell>{{ $trx->waktu_masuk ? now()->diffForHumans($trx->waktu_masuk, true) : '—' }}</flux:table.cell>
                                @can('transaksi:edit')
                                <flux:table.cell>
                                    <flux:button href="{{ route('transaksi.edit', $trx) }}" variant="primary" size="sm">Keluar</flux:button>
                                </flux:table.cell>
                                @endcan
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>

                @if(\App\Models\Transaksi::where('status', 'masuk')->count() > 5)
                    <div class="mt-3">
                        <flux:button href="{{ route('transaksi.index', ['status' => 'masuk']) }}" variant="ghost" size="sm">
                            Lihat semua →
                        </flux:button>
                    </div>
                @endif
            @else
                <flux:text class="text-gray-400 text-sm py-4 text-center">Tidak ada kendaraan yang sedang parkir.</flux:text>
            @endif
        </flux:card>
        @endcanany

        {{-- Area Parkir Status --}}
        @can('area-parkir:read')
        @php $areas = \App\Models\AreaParkir::all(); @endphp
        @if($areas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($areas as $area)
                @php $pct = $area->kapasitas > 0 ? ($area->terisi / $area->kapasitas) * 100 : 0; @endphp
                <flux:card class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <flux:heading size="sm">{{ $area->nama_area }}</flux:heading>
                        <flux:badge color="{{ $pct >= 90 ? 'red' : ($pct >= 60 ? 'yellow' : 'green') }}" size="sm">
                            {{ $area->terisi }}/{{ $area->kapasitas }}
                        </flux:badge>
                    </div>
                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $pct >= 90 ? 'bg-red-500' : ($pct >= 60 ? 'bg-yellow-400' : 'bg-green-500') }}"
                            style="width: {{ min($pct, 100) }}%"></div>
                    </div>
                    <flux:text class="text-xs text-gray-500 mt-1">{{ $area->kapasitas - $area->terisi }} slot tersedia</flux:text>
                </flux:card>
            @endforeach
        </div>
        @endif
        @endcan

    </div>
</x-layouts.app>
