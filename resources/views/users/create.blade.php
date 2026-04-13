<x-layouts.app title="Add User">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">Add User</flux:heading>
            <flux:button href="{{ route('users.index') }}" variant="ghost" icon="arrow-left">Back to Users</flux:button>
        </div>

        <flux:card>
            <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                @csrf

                <flux:input name="name" label="Full Name" type="text" placeholder="kenn de clouv"
                    value="{{ old('name') }}" required autofocus />

                <flux:input name="email" label="Email Address" type="email" placeholder="kenndeclouv@gmail.com"
                    value="{{ old('email') }}" required />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <flux:input name="password" label="Password" type="password" placeholder="••••••••" required />


                    <flux:input name="password_confirmation" label="Confirm Password" type="password"
                        placeholder="••••••••" required />
                </div>

                <div class="pt-4 border-t dark:border-[#3E3E3A]">
                    <flux:heading size="sm" class="mb-4">Assign Roles</flux:heading>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach($roles as $id => $name)
                            <flux:checkbox name="roles[]" value="{{ $id }}" label="{{ $name }}" />
                        @endforeach
                    </div>
                    @error('roles')<flux:text class="mt-2 text-sm text-red-500">{{ $message }}</flux:text>@enderror
                </div>

                <flux:button type="submit" variant="primary" class="mt-4">Create User</flux:button>
            </form>

        </flux:card>
    </div>
</x-layouts.app>