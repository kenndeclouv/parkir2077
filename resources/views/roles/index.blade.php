<x-layouts.app title="Roles">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Roles & Permissions</flux:heading>
            @can('roles:create')
                <flux:button href="{{ route('roles.create') }}" variant="primary" icon="plus">Create Role</flux:button>
            @endcan
        </div>

        @if(session('success'))
            <flux:card
                class="p-4 mb-4 bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-300">
                {{ session('success') }}
            </flux:card>
        @endif

        @if(session('error'))
            <flux:card
                class="p-4 mb-4 bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-300">
                {{ session('error') }}
            </flux:card>
        @endif

        <flux:card>
            <flux:table :paginate="$roles">
                <flux:table.columns>
                    <flux:table.column>Role Name</flux:table.column>
                    <flux:table.column>Permissions Overview</flux:table.column>
                    <flux:table.column>Created</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($roles as $role)
                        <flux:table.row>
                            <flux:table.cell class="font-medium text-lg text-[#1b1b18] dark:text-[#EDEDEC] capitalize">
                                {{ $role->name }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex gap-1 flex-wrap max-w-sm">
                                    @forelse($role->permissions->take(6) as $permission)
                                        <flux:badge color="zinc" size="sm" class="text-xs">{{ str_replace(':', ' ', $permission->name) }}</flux:badge>
                                    @empty
                                        <span class="text-gray-400">&mdash; No permissions</span>
                                    @endforelse

                                    @if($role->permissions->count() > 6)
                                        <flux:badge color="zinc" size="sm" class="text-xs">
                                            +{{ $role->permissions->count() - 6 }} more</flux:badge>
                                    @endif

                                    {{-- @if($role->name === 'admin')
                                    <flux:badge color="red" size="sm" class="font-bold">ALL ACCESS</flux:badge>
                                    @endif --}}
                                </div>
                            </flux:table.cell>
                            <flux:table.cell class="text-sm text-gray-500">
                                {{ $role->created_at->format('M j, Y') }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" />
                                    <flux:menu>
                                        @can('roles:edit')
                                            <flux:menu.item href="{{ route('roles.edit', $role) }}" icon="pencil-square">Edit
                                                Role</flux:menu.item>
                                        @endcan
                                        @can('roles:delete')
                                            @if($role->name !== 'admin')
                                                <flux:menu.separator />
                                                <form method="POST" action="{{ route('roles.destroy', $role) }}"
                                                    onsubmit="return confirm('WARNING: Deleting this role revokes it from all assigned users. Are you absolutely sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <flux:menu.item as="button" type="submit" icon="trash" variant="danger">Delete
                                                    </flux:menu.item>
                                                </form>
                                            @endif
                                        @endcan
                                    </flux:menu>
                                </flux:dropdown>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="py-8 text-center text-gray-500">No roles found.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

        </flux:card>
    </div>
</x-layouts.app>