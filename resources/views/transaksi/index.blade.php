<x-layouts.app title="Transaksi Parkir">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Transaksi Parkir</flux:heading>
            @can('transaksi:create')
                <flux:button href="{{ route('transaksi.create') }}" variant="ghost" icon="arrows-pointing-out" size="sm">
                    Form Lengkap
                </flux:button>
            @endcan
        </div>

        @if(session('success'))
            <flux:card class="p-4 bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-300">
                {{ session('success') }}
            </flux:card>
        @endif
        @if(session('error'))
            <flux:card class="p-4 bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-300">
                {{ session('error') }}
            </flux:card>
        @endif

        {{-- ─── Quick Input Kendaraan Masuk ─────────────────────────────── --}}
        @can('transaksi:create')
        <flux:card class="border-2 border-dashed border-blue-300 dark:border-blue-700 bg-blue-50/40 dark:bg-blue-950/20">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-1.5 bg-blue-600 rounded-md">
                    <flux:icon name="plus" class="size-4 text-white" />
                </div>
                <flux:heading size="sm" class="text-blue-700 dark:text-blue-300">Catat Kendaraan Masuk</flux:heading>
            </div>

            <form method="POST" action="{{ route('transaksi.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <flux:select name="id_kendaraan" label="Kendaraan" required>
                            <flux:select.option value="">— Pilih Kendaraan —</flux:select.option>
                            @foreach($kendaraans as $k)
                                <flux:select.option value="{{ $k->id_parkir }}" :selected="old('id_kendaraan') == $k->id_parkir">
                                    {{ $k->plat_nomor }} ({{ $k->pemilik }})
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        @error('id_kendaraan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <flux:select name="id_tarif" label="Tarif" required>
                            <flux:select.option value="">— Pilih Tarif —</flux:select.option>
                            @foreach($tarifs as $t)
                                <flux:select.option value="{{ $t->id_tarif }}" :selected="old('id_tarif') == $t->id_tarif">
                                    {{ ucfirst($t->jenis_kendaraan) }} — Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}/jam
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        @error('id_tarif')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <flux:select name="id_area" label="Area Parkir" required>
                            <flux:select.option value="">— Pilih Area —</flux:select.option>
                            @foreach($areas as $a)
                                <flux:select.option value="{{ $a->id_area }}" :selected="old('id_area') == $a->id_area"
                                    :disabled="$a->terisi >= $a->kapasitas">
                                    {{ $a->nama_area }} ({{ $a->kapasitas - $a->terisi }} slot)
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        @error('id_area')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <flux:input name="waktu_masuk" label="Waktu Masuk" type="datetime-local"
                            value="{{ old('waktu_masuk', now()->format('Y-m-d\TH:i')) }}" required />
                        @error('waktu_masuk')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-3">
                    <flux:button type="submit" variant="primary" icon="printer">
                        Catat &amp; Cetak Tiket
                    </flux:button>
                </div>
            </form>
        </flux:card>
        @endcan

        {{-- ─── Filter Status ────────────────────────────────────────────── --}}
        <div class="flex items-center justify-between gap-2">
            <form method="GET" action="{{ route('transaksi.index') }}" class="flex gap-2 items-center">
                <flux:select name="status" class="w-40">
                    <flux:select.option value="">Semua Status</flux:select.option>
                    <flux:select.option value="masuk" :selected="request('status') === 'masuk'">Masuk</flux:select.option>
                    <flux:select.option value="keluar" :selected="request('status') === 'keluar'">Keluar</flux:select.option>
                </flux:select>
                <flux:button type="submit" variant="ghost" icon="funnel">Filter</flux:button>
            </form>
        </div>

        {{-- ─── Tabel Transaksi ──────────────────────────────────────────── --}}
        <flux:card>
            <flux:table :paginate="$transaksis">
                <flux:table.columns>
                    <flux:table.column>Plat Nomor</flux:table.column>
                    <flux:table.column>Area</flux:table.column>
                    <flux:table.column>Waktu Masuk</flux:table.column>
                    <flux:table.column>Waktu Keluar</flux:table.column>
                    <flux:table.column>Durasi</flux:table.column>
                    <flux:table.column>Biaya</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($transaksis as $trx)
                        <flux:table.row>
                            <flux:table.cell class="font-bold">{{ $trx->kendaraan?->plat_nomor ?? '—' }}</flux:table.cell>
                            <flux:table.cell>{{ $trx->areaParkir?->nama_area ?? '—' }}</flux:table.cell>
                            <flux:table.cell class="text-gray-500 whitespace-nowrap">
                                {{ $trx->waktu_masuk?->format('d/m/Y H:i') }}
                            </flux:table.cell>
                            <flux:table.cell class="text-gray-500 whitespace-nowrap">
                                {{ $trx->waktu_keluar?->format('d/m/Y H:i') ?? '—' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $trx->durasi_jam ? $trx->durasi_jam . ' jam' : '—' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $trx->biaya_total ? 'Rp ' . number_format($trx->biaya_total, 0, ',', '.') : '—' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge color="{{ $trx->status === 'masuk' ? 'green' : 'zinc' }}">
                                    {{ ucfirst($trx->status) }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex gap-1">
                                    @if($trx->status === 'masuk')
                                        {{-- Cetak ulang tiket masuk --}}
                                        <flux:button href="{{ route('transaksi.tiket', $trx) }}" variant="ghost" size="sm" icon="ticket">
                                            Tiket
                                        </flux:button>
                                        @can('transaksi:edit')
                                            <flux:button href="{{ route('transaksi.edit', $trx) }}" variant="primary" size="sm" icon="arrow-right-circle">
                                                Keluar
                                            </flux:button>
                                        @endcan
                                    @else
                                        @can('struk:cetak')
                                            <flux:button href="{{ route('transaksi.struk', $trx) }}" variant="ghost" size="sm" icon="printer">
                                                Struk
                                            </flux:button>
                                        @endcan
                                    @endif
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="8" class="py-8 text-center text-gray-500">Belum ada data transaksi.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>
</x-layouts.app>
