<x-layouts.app title="Laporan Transaksi">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Rekap Transaksi</flux:heading>
        </div>

        {{-- Filter Tanggal --}}
        <flux:card class="p-4">
            <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap gap-3 items-end">
                <flux:input name="dari" label="Dari Tanggal" type="date" value="{{ $dari }}" class="w-44" />
                <flux:input name="sampai" label="Sampai Tanggal" type="date" value="{{ $sampai }}" class="w-44" />
                <flux:button type="submit" variant="primary" icon="magnifying-glass">Tampilkan Rekap</flux:button>
            </form>
        </flux:card>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card class="p-5">
                <flux:text class="text-gray-500 text-sm">Total Transaksi</flux:text>
                <flux:heading size="xl" class="mt-1">{{ number_format($totalTransaksi) }}</flux:heading>
                <flux:text class="text-xs text-gray-400 mt-1">{{ $dari }} s/d {{ $sampai }}</flux:text>
            </flux:card>
            <flux:card class="p-5">
                <flux:text class="text-gray-500 text-sm">Total Pendapatan</flux:text>
                <flux:heading size="xl" class="mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</flux:heading>
                <flux:text class="text-xs text-gray-400 mt-1">Semua kendaraan keluar</flux:text>
            </flux:card>
            <flux:card class="p-5">
                <flux:text class="text-gray-500 text-sm">Rata-rata per Transaksi</flux:text>
                <flux:heading size="xl" class="mt-1">
                    Rp {{ $totalTransaksi > 0 ? number_format($totalPendapatan / $totalTransaksi, 0, ',', '.') : '0' }}
                </flux:heading>
                <flux:text class="text-xs text-gray-400 mt-1">Per transaksi selesai</flux:text>
            </flux:card>
        </div>

        {{-- Per Jenis Kendaraan --}}
        @if($perJenis->count() > 0)
            <flux:card>
                <flux:heading size="sm" class="mb-3">Breakdown per Jenis Kendaraan</flux:heading>
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Jenis Kendaraan</flux:table.column>
                        <flux:table.column>Jumlah Transaksi</flux:table.column>
                        <flux:table.column>Total Pendapatan</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach($perJenis as $jenis)
                            <flux:table.row>
                                <flux:table.cell class="font-medium capitalize">{{ $jenis->jenis_kendaraan }}</flux:table.cell>
                                <flux:table.cell>{{ $jenis->jumlah }}</flux:table.cell>
                                <flux:table.cell>Rp {{ number_format($jenis->total, 0, ',', '.') }}</flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </flux:card>
        @endif

        {{-- Detail Transaksi --}}
        <flux:card>
            <flux:heading size="sm" class="mb-3">Detail Transaksi</flux:heading>
            <flux:table :paginate="$transaksis">
                <flux:table.columns>
                    <flux:table.column>Plat Nomor</flux:table.column>
                    <flux:table.column>Jenis</flux:table.column>
                    <flux:table.column>Masuk</flux:table.column>
                    <flux:table.column>Keluar</flux:table.column>
                    <flux:table.column>Durasi</flux:table.column>
                    <flux:table.column>Biaya</flux:table.column>
                    <flux:table.column>Petugas</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($transaksis as $trx)
                        <flux:table.row>
                            <flux:table.cell class="font-bold">{{ $trx->kendaraan?->plat_nomor ?? '—' }}</flux:table.cell>
                            <flux:table.cell>{{ $trx->kendaraan?->jenis_kendaraan ?? '—' }}</flux:table.cell>
                            <flux:table.cell class="text-gray-500 whitespace-nowrap">{{ $trx->waktu_masuk?->format('d/m H:i') }}</flux:table.cell>
                            <flux:table.cell class="text-gray-500 whitespace-nowrap">{{ $trx->waktu_keluar?->format('d/m H:i') }}</flux:table.cell>
                            <flux:table.cell>{{ $trx->durasi_jam }} jam</flux:table.cell>
                            <flux:table.cell class="font-medium">Rp {{ number_format($trx->biaya_total, 0, ',', '.') }}</flux:table.cell>
                            <flux:table.cell class="text-gray-500">{{ $trx->petugas?->name ?? '—' }}</flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="7" class="py-8 text-center text-gray-500">
                                Tidak ada transaksi pada periode ini.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>
</x-layouts.app>
