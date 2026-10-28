<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="@yield('body-class', 'min-h-screen bg-slate-50 font-sans text-slate-800 antialiased')">
    <div class="min-h-screen lg:grid lg:grid-cols-[18rem_minmax(0,1fr)]">
        @include('partials.sidebar')

        <button
            type="button"
            data-sidebar-backdrop
            aria-label="Tutup menu"
            class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"
        ></button>

        <div class="flex min-h-screen min-w-0 flex-col">
            @include('partials.header')

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                <div class="mx-auto w-full max-w-7xl">
                    @yield('content')

                    @include('partials.footer')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
