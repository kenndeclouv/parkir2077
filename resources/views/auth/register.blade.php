<x-layouts.auth title="Register" description="Enter your details below to create your account">
    <div class="flex flex-col justify-center gap-6">
        {{-- <div class="text-center">
            <flux:heading size="xl" class="font-semibold">{{ __('Create an account') }}</flux:heading>
            <flux:text class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Enter your details below to create
                your account') }}</flux:text>
        </div> --}}

        @if (session('status'))
            <div class="text-center text-sm font-medium text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input name="name" :label="__('Name')" :value="old('name')" type="text" required autofocus
                autocomplete="name" :placeholder="__('Full name')" />

            <!-- Email Address -->
            <flux:input name="email" :label="__('Email address')" :value="old('email')" type="email" required
                autocomplete="email" placeholder="email@example.com" />

            <!-- Password -->
            <flux:input name="password" :label="__('Password')" type="password" required autocomplete="new-password"
                :placeholder="__('Password')" viewable />

            <!-- Confirm Password -->
            <flux:input name="password_confirmation" :label="__('Confirm password')" type="password" required
                autocomplete="new-password" :placeholder="__('Confirm password')" viewable />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')">{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>