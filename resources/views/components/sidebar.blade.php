<flux:sidebar sticky collapsible class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.header>
        <flux:sidebar.brand href="#" logo:dark="https://fluxui.dev/faviconcircle32x32.png"
            name="{{ config('app.name') }}" />
        <flux:sidebar.collapse />
    </flux:sidebar.header>

    <flux:sidebar.nav>

        <flux:sidebar.item icon="home" href="{{ route('home') }}" :current="request()->routeIs('home')">
            Dashboard
        </flux:sidebar.item>

        @canany(['users:read', 'roles:read', 'tarif:read', 'area-parkir:read', 'kendaraan:read', 'log-aktivitas:read'])
            <div class="mt-3 mb-1">
                <div class="px-3 text-xs tracking-widest text-zinc-400 in-data-flux-sidebar-collapsed-desktop:hidden">
                    Administrasi
                </div>
                <div class="text-center hidden in-data-flux-sidebar-collapsed-desktop:block text-zinc-400 text-xs">──</div>
            </div>
        @endcanany

        @can('users:read')
            <flux:sidebar.item icon="users" href="{{ route('users.index') }}" :current="request()->routeIs('users.*')">
                Manajemen User
            </flux:sidebar.item>
        @endcan

        @can('roles:read')
            <flux:sidebar.item icon="shield-check" href="{{ route('roles.index') }}"
                :current="request()->routeIs('roles.*')">
                Manajemen Role
            </flux:sidebar.item>
        @endcan

        @can('tarif:read')
            <flux:sidebar.item icon="currency-dollar" href="{{ route('tarif.index') }}"
                :current="request()->routeIs('tarif.*')">
                Tarif Parkir
            </flux:sidebar.item>
        @endcan

        @can('area-parkir:read')
            <flux:sidebar.item icon="map-pin" href="{{ route('area-parkir.index') }}"
                :current="request()->routeIs('area-parkir.*')">
                Area Parkir
            </flux:sidebar.item>
        @endcan

        @can('kendaraan:read')
            <flux:sidebar.item icon="truck" href="{{ route('kendaraan.index') }}"
                :current="request()->routeIs('kendaraan.*')">
                Data Kendaraan
            </flux:sidebar.item>
        @endcan

        @can('log-aktivitas:read')
            <flux:sidebar.item icon="clipboard-document-list" href="{{ route('log-aktivitas.index') }}"
                :current="request()->routeIs('log-aktivitas.*')">
                Log Aktivitas
            </flux:sidebar.item>
        @endcan

        @can('transaksi:read')
            <div class="mt-3 mb-1">
                <div class="px-3 text-xs tracking-widest text-zinc-400 in-data-flux-sidebar-collapsed-desktop:hidden">
                    Operasional
                </div>
                <div class="text-center hidden in-data-flux-sidebar-collapsed-desktop:block text-zinc-400 text-xs">──</div>
            </div>

            <flux:sidebar.item icon="arrows-right-left" href="{{ route('transaksi.index') }}"
                :current="request()->routeIs('transaksi.*')">
                Transaksi Parkir
            </flux:sidebar.item>
        @endcan

        @can('laporan:read')
            <div class="mt-3 mb-1">
                <div class="px-3 text-xs tracking-widest text-zinc-400 in-data-flux-sidebar-collapsed-desktop:hidden">
                    Laporan
                </div>
                <div class="text-center hidden in-data-flux-sidebar-collapsed-desktop:block text-zinc-400 text-xs">──</div>
            </div>

            <flux:sidebar.item icon="chart-bar" href="{{ route('laporan.index') }}"
                :current="request()->routeIs('laporan.*')">
                Rekap Transaksi
            </flux:sidebar.item>
        @endcan

    </flux:sidebar.nav>

    <flux:sidebar.spacer />

    {{-- Profile & Logout (Desktop) --}}
    <flux:dropdown position="top" align="start" class="max-lg:hidden">
        <flux:sidebar.profile avatar="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}"
            name="{{ auth()->user()->name ?? 'User' }}"
            subtitle="{{ auth()->user()->getRoleNames()->first() ?? '' }}" />

        <flux:menu>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                    Logout
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>

{{-- Mobile Header --}}
<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
    <flux:spacer />
    <flux:dropdown position="top" align="start">
        <flux:profile avatar="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}" />
        <flux:menu>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                    Logout
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:header>