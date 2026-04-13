<x-layouts.app title="Tambah Kendaraan">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Tambah Kendaraan</flux:heading>
            <flux:button href="{{ route('kendaraan.index') }}" variant="ghost" icon="arrow-left">Kembali</flux:button>
        </div>

        <flux:card>
            <form method="POST" action="{{ route('kendaraan.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input name="plat_nomor" label="Plat Nomor" type="text" placeholder="B 1234 XYZ"
                        value="{{ old('plat_nomor') }}" required autofocus />
                    @error('plat_nomor')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror

                    <flux:input name="jenis_kendaraan" label="Jenis Kendaraan" type="text" placeholder="Motor / Mobil / Pick Up"
                        value="{{ old('jenis_kendaraan') }}" required />
                    @error('jenis_kendaraan')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror

                    <flux:input name="warna" label="Warna" type="text" placeholder="Hitam"
                        value="{{ old('warna') }}" required />
                    @error('warna')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror

                    <flux:input name="pemilik" label="Nama Pemilik" type="text" placeholder="Nama lengkap pemilik"
                        value="{{ old('pemilik') }}" required />
                    @error('pemilik')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror
                </div>

                <flux:select name="id_user" label="Akun User (opsional)">
                    <flux:select.option value="">-- Tidak ditautkan --</flux:select.option>
                    @foreach($users as $id => $name)
                        <flux:select.option value="{{ $id }}" :selected="old('id_user') == $id">{{ $name }}</flux:select.option>
                    @endforeach
                </flux:select>
                @error('id_user')<flux:text class="text-sm text-red-500">{{ $message }}</flux:text>@enderror

                <flux:button type="submit" variant="primary">Simpan Kendaraan</flux:button>
            </form>
        </flux:card>
    </div>
</x-layouts.app>
