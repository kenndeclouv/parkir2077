<x-layouts.app title="Kendaraan Keluar">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Proses Kendaraan Keluar</flux:heading>
            <flux:button href="{{ route('transaksi.index') }}" variant="ghost" icon="arrow-left">Kembali</flux:button>
        </div>

        {{-- Info Kendaraan --}}
        <flux:card class="p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <flux:text class="text-gray-500">Plat Nomor</flux:text>
                    <flux:heading size="sm">{{ $transaksi->kendaraan->plat_nomor }}</flux:heading>
                </div>
                <div>
                    <flux:text class="text-gray-500">Jenis</flux:text>
                    <flux:heading size="sm">{{ $transaksi->kendaraan->jenis_kendaraan }}</flux:heading>
                </div>
                <div>
                    <flux:text class="text-gray-500">Area Parkir</flux:text>
                    <flux:heading size="sm">{{ $transaksi->areaParkir->nama_area }}</flux:heading>
                </div>
                <div>
                    <flux:text class="text-gray-500">Tarif / Jam</flux:text>
                    <flux:heading size="sm">Rp {{ number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}</flux:heading>
                </div>
                <div>
                    <flux:text class="text-gray-500">Waktu Masuk</flux:text>
                    <flux:heading size="sm">{{ $transaksi->waktu_masuk->format('d/m/Y H:i') }}</flux:heading>
                </div>
            </div>
        </flux:card>

        {{-- Form Keluar --}}
        <flux:card>
            <form method="POST" action="{{ route('transaksi.update', $transaksi) }}" class="space-y-4">
                @csrf @method('PUT')

                <flux:input name="waktu_keluar" label="Waktu Keluar" type="datetime-local"
                    value="{{ old('waktu_keluar', now()->format('Y-m-d\TH:i')) }}" required />
                @error('waktu_keluar')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror

                <flux:button type="submit" variant="primary" icon="arrow-up-tray">Proses Keluar & Cetak Struk</flux:button>
            </form>
        </flux:card>
    </div>
</x-layouts.app>
