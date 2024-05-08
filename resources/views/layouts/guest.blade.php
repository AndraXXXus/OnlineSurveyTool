<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-cloak x-data="{ darkMode: false }" x-bind:data-bs-theme="darkMode ? 'dark' : 'light'"  x-init="
    if (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        localStorage.setItem('darkMode', JSON.stringify(true));
    }
    darkMode = JSON.parse(localStorage.getItem('darkMode'));
    $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"> --}}
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<title>
    @if (View::hasSection('title'))
        @yield('title') |
    @endif
    {{ config('app.name', 'Laravel') }}
</title>
<!-- Fonts -->
@vite(['resources/sass/app.scss', 'resources/js/app.js'])

<!-- Scripts -->

</head>
    <body>
        <div id="app">

            @include('layouts.nav.guest-nav-bar')
            <div class="container">
                <main class="py-5">
                    @yield('content')
                </main>
            </div>
        </div>

    </body>
</html>
