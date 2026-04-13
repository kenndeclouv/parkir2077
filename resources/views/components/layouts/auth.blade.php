<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $seoTitle = isset($title) ? $title . config('seo.title_separator') . config('seo.title') : config('seo.title');
        $seoDescription = $description ?? config('seo.description');
        $seoKeywords = $keywords ?? config('seo.keywords');
        $seoAuthor = $author ?? config('seo.author');
        $seoImage = asset($image ?? config('seo.image'));
    @endphp

    <title>{{ $seoTitle }}</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="{{ $seoTitle }}">
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="keywords" content="{{ $seoKeywords }}">
    <meta name="author" content="{{ $seoAuthor }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $ogType ?? config('seo.og.type') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoImage }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $seoTitle }}">
    <meta property="twitter:description" content="{{ $seoDescription }}">
    <meta property="twitter:image" content="{{ $seoImage }}">
    <meta property="twitter:site" content="{{ config('seo.twitter.site') }}">
    <meta property="twitter:creator" content="{{ config('seo.twitter.creator') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @fluxAppearance
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 antialiased">
    {{-- {{ $slot }} --}}
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div
            class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-zinc-700">
            <div class="absolute inset-0 bg-zinc-900"></div>
            <a href="{{ route('login') }}" class="relative z-20 flex items-center text-lg font-medium">
                {{ config('app.name') }}
            </a>

            @php
                [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
            @endphp

            <div class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <flux:heading size="lg">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                    <footer>
                        <flux:heading>{{ trim($author) }}</flux:heading>
                    </footer>
                </blockquote>
            </div>
        </div>
        <div class="w-full lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden">
                    <span class="sr-only">{{ config('app.name') }}</span>
                </a>
                <div class="flex w-full flex-col text-center">
                    <flux:heading size="xl">{{ $title ?? '' }}</flux:heading>
                    <flux:subheading>{{ $description ?? '' }}</flux:subheading>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>