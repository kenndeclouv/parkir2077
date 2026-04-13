<x-layouts.auth title="Home">
    <div class="flex items-center justify-center min-h-[70vh]">
        <div class="text-center">
            <flux:heading size="2xl" class="mb-4">Welcome to Parkir2077</flux:heading>
            <flux:text class="mb-8">Fast, secure, and modern parking management.</flux:text>
            
            <div class="flex gap-4 justify-center">
                @auth
                    <flux:button href="{{ route('home') }}" variant="primary">Go to Home</flux:button>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <flux:button type="submit" variant="ghost">Logout</flux:button>
                    </form>
                @else
                    <flux:button href="{{ route('login') }}" variant="primary">Sign In</flux:button>
                    <flux:button href="{{ route('register') }}" variant="ghost">Create Account</flux:button>
                @endauth
            </div>
        </div>
    </div>
</x-layouts.auth>