<x-layouts.app title="Area Parkir">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Area Parkir</flux:heading>
            @can('area-parkir:create')
                <flux:button href="{{ route('area-parkir.create') }}" variant="primary" icon="plus">Tambah Area</flux:button>
            @endcan
        </div>

        @if(session('success'))
            <flux:card class="p-4 bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-300">
                {{ session('success') }}
            </flux:card>
        @endif

        <flux:card>
            <flux:table :paginate="$areas">
                <flux:table.columns>
                    <flux:table.column>Nama Area</flux:table.column>
                    <flux:table.column>Kapasitas</flux:table.column>
                    <flux:table.column>Terisi</flux:table.column>
                    <flux:table.column>Tersedia</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($areas as $area)
                        <flux:table.row>
                            <flux:table.cell class="font-medium">{{ $area->nama_area }}</flux:table.cell>
                            <flux:table.cell>{{ $area->kapasitas }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge color="{{ $area->terisi >= $area->kapasitas ? 'red' : 'blue' }}">
                                    {{ $area->terisi }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>{{ $area->kapasitas - $area->terisi }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" />
                                    <flux:menu>
                                        @can('area-parkir:edit')
                                            <flux:menu.item href="{{ route('area-parkir.edit', $area) }}" icon="pencil-square">Edit</flux:menu.item>
                                        @endcan
                                        @can('area-parkir:delete')
                                            <flux:menu.separator />
                                            <form method="POST" action="{{ route('area-parkir.destroy', $area) }}"
                                                onsubmit="return confirm('Hapus area ini?')">
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
                            <flux:table.cell colspan="5" class="py-8 text-center text-gray-500">Belum ada data area parkir.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>
</x-layouts.app>
