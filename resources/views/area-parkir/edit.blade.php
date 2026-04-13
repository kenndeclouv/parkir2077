<x-layouts.app title="Edit Area Parkir">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Edit Area Parkir</flux:heading>
            <flux:button href="{{ route('area-parkir.index') }}" variant="ghost" icon="arrow-left">Kembali</flux:button>
        </div>

        <flux:card>
            <form method="POST" action="{{ route('area-parkir.update', $areaParkir) }}" class="space-y-4">
                @csrf @method('PUT')

                <flux:input name="nama_area" label="Nama Area" type="text"
                    value="{{ old('nama_area', $areaParkir->nama_area) }}" required />
                @error('nama_area')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror

                <flux:input name="kapasitas" label="Kapasitas (slot)" type="number" min="1"
                    value="{{ old('kapasitas', $areaParkir->kapasitas) }}" required />
                @error('kapasitas')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror

                <flux:button type="submit" variant="primary">Update Area</flux:button>
            </form>
        </flux:card>
    </div>
</x-layouts.app>
