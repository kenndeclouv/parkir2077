<x-layouts.app title="Log Aktivitas">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Log Aktivitas</flux:heading>
        </div>

        <flux:card>
            <flux:table :paginate="$logs">
                <flux:table.columns>
                    <flux:table.column>Waktu</flux:table.column>
                    <flux:table.column>User</flux:table.column>
                    <flux:table.column>Aktivitas</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($logs as $log)
                        <flux:table.row>
                            <flux:table.cell class="text-gray-500 whitespace-nowrap">
                                {{ $log->waktu_aktivitas->format('d M Y H:i:s') }}
                            </flux:table.cell>
                            <flux:table.cell class="font-medium">{{ $log->user?->name ?? '—' }}</flux:table.cell>
                            <flux:table.cell>{{ $log->aktivitas }}</flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="3" class="py-8 text-center text-gray-500">Belum ada log aktivitas.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </div>
</x-layouts.app>
