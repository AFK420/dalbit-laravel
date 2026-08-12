<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale ?? app()->getLocale()) }}"
      dir="{{ ($locale ?? app()->getLocale()) === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DALBIT') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!--
            Brand color variables — PLACEHOLDER values for Feature 1.
            Feature 2 (Language/Theme Swap) is responsible for actually
            swapping these between the two brand palettes based on
            locale/theme (Off-white/Lavender <-> Deep Lavender/Off-white).
            Everything below reads from these variables so that swap can
            be a single attribute/class toggle rather than a template rewrite.
        -->
        <style>
            :root {
                --color-bg: #2B2748;          /* deep navy-lavender, header/footer */
                --color-surface: #FAF8F5;     /* off-white, content sections/cards */
                --color-text: #2B2748;
                --color-text-inverse: #FAF8F5;
                --color-accent: #8B7EC4;      /* lavender, hero/tile backgrounds */
                --color-accent-dark: #EFC24E; /* gold, CTAs and links */
                --color-border: #E8E3EF;
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans" style="background-color: var(--color-surface); color: var(--color-text);">
        <header style="background-color: var(--color-bg);">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <a href="{{ route('storefront.index') }}" class="text-xl font-semibold" style="color: var(--color-text-inverse);">
                    {{ config('app.name', 'DALBIT') }}
                </a>
                <form action="{{ route('locale.toggle') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit"
                            class="text-sm font-medium hover:underline bg-transparent border-0 p-0 cursor-pointer"
                            style="color: var(--color-accent-dark);">
                        {{ ($locale ?? app()->getLocale()) === 'ar' ? 'EN' : 'عربي' }}
                    </button>
                </form>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer style="background-color: var(--color-bg);">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-sm text-center" style="color: var(--color-text-inverse);">
                &copy; {{ date('Y') }} {{ config('app.name', 'DALBIT') }}
            </div>
        </footer>
    </body>
</html>
