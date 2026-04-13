<x-layouts.app title="Kendaraan">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Data Kendaraan</flux:heading>
            @can('kendaraan:create')
                <flux:button href="{{ route('kendaraan.create') }}" variant="primary" icon="plus">Tambah Kendaraan</flux:button>
            @endcan
        </div>

        @if(session('success'))
            <flux:card class="p-4 bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-300">
                {{ session('success') }}
            </flux:card>
        @endif

        <flux:card>
            <flux:table :paginate="$kendaraans">
                <flux:table.columns>
                    <flux:table.column>Plat Nomor</flux:table.column>
                    <flux:table.column>Jenis</flux:table.column>
                    <flux:table.column>Warna</flux:table.column>
                    <flux:table.column>Pemilik</flux:table.column>
                    <flux:table.column>User Terdaftar</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($kendaraans as $kendaraan)
                        <flux:table.row>
                            <flux:table.cell class="font-bold tracking-wide">{{ $kendaraan->plat_nomor }}</flux:table.cell>
                            <flux:table.cell>{{ $kendaraan->jenis_kendaraan }}</flux:table.cell>
                            <flux:table.cell>{{ $kendaraan->warna }}</flux:table.cell>
                            <flux:table.cell>{{ $kendaraan->pemilik }}</flux:table.cell>
                            <flux:table.cell class="text-gray-500">
                                {{ $kendaraan->user?->name ?? '—' }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" />
                                    <flux:menu>
                                        @can('kendaraan:edit')
                                            <flux:menu.item href="{{ route('kendaraan.edit', $kendaraan) }}" icon="pencil-square">Edit</flux:menu.item>
                                        @endcan
                                        @can('kendaraan:delete')
                                            <flux:menu.separator />
                                            <form method="POST" action="{{ route('kendaraan.destroy', $kendaraan) }}"
                                                onsubmit="return confirm('Hapus kendaraan ini?')">
                                                @csrf @method('DELETE')
                                                <flux:menu.item as="button" type="submit" icon="trash" variant="danger">Hapus</flux:menu.item>
                                            </form>
                                        @endcan
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="6" class="py-8 text-center text-gray-500">Belum ada data kendaraan.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>
</x-layouts.app>
