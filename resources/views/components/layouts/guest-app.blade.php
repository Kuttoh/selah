<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            {{ $header ?? '' }}
        </header>
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            {{ $slot }}
        </div>
        <footer class="mt-6 text-xs text-zinc-500 dark:text-zinc-400">
            <span>{{ config('app.name') }} © {{ now()->year }}</span>
            @if (!Route::is('home'))
                 <span class="mx-2">·</span>
                <a class="hover:underline" href="{{ route('home') }}">Home</a>
            @endif
            <span class="mx-2">·</span>
            <a class="hover:underline" href="{{ route('prayers.index') }}">Admin</a>
        </footer>
        @fluxScripts
    </body>
</html>
