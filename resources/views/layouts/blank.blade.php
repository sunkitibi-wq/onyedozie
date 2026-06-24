<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="p-6 max-w-7xl mx-auto">
            {{ $slot }}
        </div>
        @fluxScripts
    </body>
</html>
