<x-layouts.app title="Users">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Users</flux:heading>
            @can('users:create')
                <flux:button href="{{ route('users.create') }}" variant="primary" icon="plus">Add User</flux:button>
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
            <flux:table :paginate="$users">
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Roles</flux:table.column>
                    <flux:table.column>Joined</flux:table.column>
                    <flux:table.column>Actions</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($users as $user)
                        <flux:table.row>
                            <flux:table.cell class="font-medium items-center flex gap-2">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <flux:badge color="amber" size="sm">You</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell class="text-gray-500">{{ $user->email }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex gap-1 flex-wrap">
                                    @forelse($user->roles as $role)
                                        <flux:badge color="blue" size="sm">{{ $role->name }}</flux:badge>
                                    @empty
                                        <span class="text-gray-400">&mdash;</span>
                                    @endforelse
                                </div>
                            </flux:table.cell>
                            <flux:table.cell class="text-gray-500">{{ $user->created_at->format('M j, Y') }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:dropdown>
                                    <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" />
                                    <flux:menu>
                                        @can('users:edit')
                                            <flux:menu.item href="{{ route('users.edit', $user) }}" icon="pencil-square">Edit
                                            </flux:menu.item>
                                        @endcan
                                        @can('users:delete')
                                            @if($user->id !== auth()->id())
                                                <flux:menu.separator />
                                                <form method="POST" action="{{ route('users.destroy', $user) }}"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                            <flux:table.cell colspan="5" class="py-8 text-center text-gray-500">No users found.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>

        {{-- <div>
            {{ $users->links() }}
        </div> --}}
    </div>
</x-layouts.app>