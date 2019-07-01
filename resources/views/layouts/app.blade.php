<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/yo.css') }}" rel="stylesheet">
    @role('manager')<link href="{{ asset('css/manager.css') }}" rel="stylesheet">@endrole
    @role('admin')<link href="{{ asset('css/admin.css') }}" rel="stylesheet">@endrole
    @role('owner')<link href="{{ asset('css/owner.css') }}" rel="stylesheet">@endrole

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="128x128" href="/favicon.png">
</head>
<body>
    <div id="app">        

        <div class="fw-background">{{-- from https://datatables.net/examples/basic_init/table_sorting.html --}}
            <div class="container relative">
                <a class="logo" href="{{ url('/') }}">
                    <img src="/laravel_white.png" alt="logo">
                </a>
            </div>
        </div>

        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="container">

                {{-- <a class="navbar-brand grey" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a> --}}

                @include('menu.main')

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

                        {{-- @role('user') --}}
                        <a href="{{ route('cart.show') }}" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge">{{ Session::has('cart') ? count(Session::get('cart')->items) : '' }}</span>
                        </a>
                        {{-- @endrole --}}


                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif

                        @else

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->roles->first()->name }}
                                    {{ Auth::user()->name }}<span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('users.show', ['user' => Auth::user()->id]) }}">Profile</a>

                                    <a class="dropdown-item" href="{{ route('orders.index') }}">Orders</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                </div>
                            </li>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="height2em"></div>
       
        @if ($errors->any())
            <div class="container">             
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Holy guacamole!</strong> Something went wrong..
                    <ol>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ol>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif

        {{-- @section('sidebar')
            This is the master sidebar.
        @show --}}


        <main class="py-4">

            {{-- @alert(['type' => 'primary', 'title' => 'roles/create'])
                SQLSTATE[HY000]: General error: 1364 Field 'rank' doesn't have a default value.
            @endalert --}}
            @if( !empty($success))
            @alert(['type' => 'primary', 'title' => 'success'])
                {{ $success }}
            @endalert
            @endif

            @if( session('message'))
                <div class="container">
                    <div class="alert alert-success">
                        <div class="alert-title">{{ session('message') }}</div>
                    </div>
                </div>
            @endif

            

            <div class="container">
                <div class="row">
                <div class="col col-sm-1 p-0">

                    @include('layouts.partials.nav')
                    @include('layouts.partials.filter-manufacturer')

                </div>
                <div class="col col-sm-11 pr-0">

                    @yield('content')

                </div>
                </div>
            </div>

        </main>

        <div class="footer relative">

            <div class="container">

                <div class="copy">© Some_text</div>

                <div class="row">

                    @include('menu.main')

                    <a aria-label="Homepage" title="GitHub" class="footer-octicon d-none d-lg-block mx-lg-4" href="https://github.com/yakoffka/kk">
                        <svg width="34" height="34" class="octicon octicon-mark-github" viewBox="0 0 16 16" version="1.1" aria-hidden="true"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg>
                    </a>
                </div>
            </div>

            <div class="skew"></div><div class="skew-bg"></div>

        </div>
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
    {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script> --}}
    {{-- <script>window.jQuery || document.write('<script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/jquery-slim.min.js"><\/script>')</script> --}}
    {{-- <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script><script src="/docs/4.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.js"></script> --}}
    {{-- <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js" crossorigin="anonymous"></script> --}}
    {{-- <script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/docsearch.js@2/dist/cdn/docsearch.min.js"></script> --}}
    {{-- <script src="https://getbootstrap.com/docs/4.0/assets/js/docs.min.js"></script> --}}


</body>
</html>
