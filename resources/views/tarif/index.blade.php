<x-layouts.app title="Tarif Parkir">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Tarif Parkir</flux:heading>
            @can('tarif:create')
                <flux:button href="{{ route('tarif.create') }}" variant="primary" icon="plus">Tambah Tarif</flux:button>
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

        <flux:card>
            <flux:table :paginate="$tarifs">
                <flux:table.columns>
                    <flux:table.column>Jenis Kendaraan</flux:table.column>
                    <flux:table.column>Tarif / Jam</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($tarifs as $tarif)
                        <flux:table.row>
                            <flux:table.cell class="font-medium capitalize">{{ $tarif->jenis_kendaraan }}</flux:table.cell>
                            <flux:table.cell>Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" />
                                    <flux:menu>
                                        @can('tarif:edit')
                                            <flux:menu.item href="{{ route('tarif.edit', $tarif) }}" icon="pencil-square">Edit</flux:menu.item>
                                        @endcan
                                        @can('tarif:delete')
                                            <flux:menu.separator />
                                            <form method="POST" action="{{ route('tarif.destroy', $tarif) }}"
                                                onsubmit="return confirm('Hapus tarif ini?')">
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
                            <flux:table.cell colspan="3" class="py-8 text-center text-gray-500">Belum ada data tarif.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>
</x-layouts.app>
