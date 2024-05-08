

<nav class="navbar x-cloak sticky-top navbar-expand-lg navbar-light bg-body-tertiary shadow-sm border-bottom border-gray-100">
    {{-- x-cloak  --}}
    <div class="container">

        <div class="flex-shrink-0 flex items-center">
            @include('layouts.nav.partials.logo-and-name')
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            @auth
            @include('layouts.nav.partials.main-nav')
            @endauth



            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">





                <!-- Authentication Links -->
                @guest
                    @include('layouts.nav.partials.guest-nav')
                @else
                    @include('layouts.nav.partials.loggedin')
                @endguest

            </ul>
        </div>
    </div>
</nav>
