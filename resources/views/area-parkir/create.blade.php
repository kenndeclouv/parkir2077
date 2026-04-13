<x-layouts.app title="Tambah Area Parkir">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Tambah Area Parkir</flux:heading>
            <flux:button href="{{ route('area-parkir.index') }}" variant="ghost" icon="arrow-left">Kembali</flux:button>
        </div>

        <flux:card>
            <form method="POST" action="{{ route('area-parkir.store') }}" class="space-y-4">
                @csrf

                <flux:input name="nama_area" label="Nama Area" type="text" placeholder="Lantai 1 - Blok A"
                    value="{{ old('nama_area') }}" required autofocus />
                @error('nama_area')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror

                <flux:input name="kapasitas" label="Kapasitas (slot)" type="number" min="1"
                    value="{{ old('kapasitas') }}" placeholder="50" required />
                @error('kapasitas')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror

                <flux:button type="submit" variant="primary">Simpan Area</flux:button>
            </form>
        </flux:card>
    </div>
</x-layouts.app>
