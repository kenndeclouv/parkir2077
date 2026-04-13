<x-layouts.app title="Tambah Tarif">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Tambah Tarif Parkir</flux:heading>
            <flux:button href="{{ route('tarif.index') }}" variant="ghost" icon="arrow-left">Kembali</flux:button>
        </div>

        <flux:card>
            <form method="POST" action="{{ route('tarif.store') }}" class="space-y-4">
                @csrf

                <flux:select name="jenis_kendaraan" label="Jenis Kendaraan" required>
                    <flux:select.option value="">-- Pilih Jenis --</flux:select.option>
                    <flux:select.option value="motor" :selected="old('jenis_kendaraan') === 'motor'">Motor</flux:select.option>
                    <flux:select.option value="mobil" :selected="old('jenis_kendaraan') === 'mobil'">Mobil</flux:select.option>
                    <flux:select.option value="lainnya" :selected="old('jenis_kendaraan') === 'lainnya'">Lainnya</flux:select.option>
                </flux:select>
                @error('jenis_kendaraan')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror

                <flux:input name="tarif_per_jam" label="Tarif per Jam (Rp)" type="number" min="0" step="500"
                    value="{{ old('tarif_per_jam') }}" placeholder="5000" required />
                @error('tarif_per_jam')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror

                <flux:button type="submit" variant="primary">Simpan Tarif</flux:button>
            </form>
        </flux:card>
    </div>
</x-layouts.app>
