<x-layouts.app title="Create Role">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Create Role</flux:heading>
            <flux:button href="{{ route('roles.index') }}" variant="ghost" icon="arrow-left">Back to Roles</flux:button>
        </div>

        <flux:card>

            <form method="POST" action="{{ route('roles.store') }}" class="space-y-4">
                @csrf

                <flux:input name="name" badge="Required" label="Role Name" type="text" placeholder="manager"
                    value="{{ old('name') }}" description="Must be unique (e.g. manager, editor)." required autofocus />

                <div class="pt-6 border-t dark:border-[#3E3E3A]">
                    <flux:heading size="lg" class="mb-6">Permissions</flux:heading>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($permissions as $group => $perms)
                            <div
                                class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-5 border border-transparent dark:border-gray-700">
                                <flux:heading size="sm" class="mb-4 uppercase tracking-wider text-gray-500 font-semibold">
                                    {{ $group }}
                                </flux:heading>
                                <div class="space-y-3">
                                    @foreach($perms as $permission)
                                        <flux:checkbox name="permissions[]" value="{{ $permission->name }}"
                                            label="{{ explode(':', $permission->name)[1] ?? $permission->name }}" />
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('permissions')
                        <flux:text class="mt-4 text-sm text-red-500">{{ $message }}</flux:text>
                    @enderror
                </div>

                <flux:button type="submit" variant="primary" class="mt-4">Create Role</flux:button>
            </form>
        </flux:card>
    </div>
</x-layouts.app>