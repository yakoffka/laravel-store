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
    <script type="text/javascript" src="https://kk.dragoon.pw/src/js/jquery-1.11.2.min.js"></script>

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
                <a class="logo d-none d-md-block" href="{{ url('/') }}">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
                    <img src="/laravel_white.png" alt="logo">
                </a>
            </div>
        </div>

        <nav class="navbar navbar-expand-md navbar-dark">
        {{-- <nav class="navbar navbar-expand-md navbar-dark d-md-none">d-md-none - Скрыто на экранах шире md --}}
            <div class="container">
                
                {{-- logo --}}
                    <div class="logo_mob d-md-none">{{-- d-md-none - Скрыто на экранах шире md --}}
                        <img src="/laravel_white.png" width="40" height="40" alt="logo">
                        {{-- <span class="logo_name">{{ config('app.name', 'Laravel') }}</span> --}}
                    </div>
                    <div class="logo_mob d-md-none">{{-- d-md-none - Скрыто на экранах шире md --}}
                        {{-- <img src="/laravel_white.png" width="40" height="40" alt="logo"> --}}
                        <span class="logo_name">{{ config('app.name', 'Laravel') }}</span>
                    </div>
                {{-- logo --}}


                {{-- <a class="navbar-brand grey" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a> --}}

                
                {{-- main_menu --}}
                <ul class="main_menu d-none d-md-block">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
                    @include('menu.main')
                </ul>
                {{-- main_menu --}}

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item d-md-none">{{-- d-md-none - Скрыто на экранах шире md --}}
                            <a class="nav-link" href="/home">Home</a>
                        <li>

                        <li class="nav-item d-md-none">{{-- d-md-none - Скрыто на экранах шире md --}}
                            <a class="nav-link" href="/products">Catalog</a>
                        <li>

                        {{-- cart --}}
                        <li class="nav-item d-none d-md-block">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
                            <a href="{{ route('cart.show') }}" class="nav-link">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="badge">
                                    {{ Session::has('cart') ? count(Session::get('cart')->items) : '' }}
                                </span>
                            </a>
                        <li>
                        <li class="nav-item d-md-none">{{-- d-md-none - Скрыто на экранах шире md --}}
                            <a href="{{ route('cart.show') }}" class="nav-link">
                                <i class="fas fa-shopping-cart"></i> in youre cart
                                <span class="badge">
                                    {{ Session::has('cart') ? count(Session::get('cart')->items) : '' }}
                                </span>
                                products
                            </a>
                        <li>
                        {{-- cart --}}


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
                                <a id="navbarDropdown" 
                                    class="nav-link dropdown-toggle" 
                                    href="#" 
                                    role="button" 
                                    data-toggle="dropdown" 
                                    aria-haspopup="true"
                                    aria-expanded="false" 
                                    v-pre
                                >
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

                        <li class="nav-item d-md-none">{{-- d-md-none - Скрыто на экранах шире md --}}
                            <a class="nav-link" href="https://github.com/yakoffka/kk" target="_blank">GitHub</a>
                        <li>

                        {{-- search --}}
                        <li class="nav-item d-md-none">{{-- d-md-none - Скрыто на экранах шире md --}}
                            {{-- <form class="search" action="{{ route('search') }}" method="GET" role="search">
                                <input 
                                    style="width:100%; margin-top:5px; height:2em;" 
                                    type="search" 
                                    class="input-sm form-control" 
                                    name="query" 
                                    placeholder="Search products"
                                    value="{{ $query ?? '' }}"
                                >
                            </form> --}}
                            @include('layouts.partials.searchform')
                        <li>
                        {{-- search --}}

                    </ul>

                </div>

            </div>
        </nav>

        {{-- <div class="height2em"></div> --}}
       
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
                <div class="container p-0">
                    <div class="alert alert-success">
                        <div class="alert-title">{{ session('message') }}</div>
                    </div>
                </div>
            @endif

            

            <div class="container">
                @yield('content')
            </div>

        </main>

        <div class="container">

            @include('layouts.partials.separator')

            <div class="grey denial_responsibility">                
                Администрация Сайта не несет ответственности за размещённые Пользователями материалы (в т.ч. информацию и изображения), их содержание и качество.
            </div>
        </div>


        <div class="footer relative">

            <div class="container">

                <div class="copy">© Never trust yourself</div>

                <div class="row m-0">

                    {{-- @include('menu.main') --}}
                    <ul class="main_menu">
                        @include('menu.main')
                    </ul>


                    {{-- <a aria-label="Homepage" title="GitHub" class="footer-octicon d-none d-lg-block mx-lg-4" href="https://github.com/yakoffka/kk"> --}}
                    <a aria-label="Homepage" title="GitHub" class="footer-octicon mx-lg-4" href="https://github.com/yakoffka/kk" style="padding: 7px 7px 0;">
                        <svg width="34" height="34" class="octicon octicon-mark-github" viewBox="0 0 16 16" version="1.1" aria-hidden="true"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg>
                    </a>

                </div>
                
            </div>

            <div class="skew"></div>
            <div class="skew-bg"></div>

        </div>
    </div>

    {{-- toTop --}}
    <div id='toTop'><i class="fas fa-chevron-circle-up"></i></div>

    <script type="text/javascript">
		$(function(){
			$(window).scroll(function(){
				if($(this).scrollTop()!= 0){$('#toTop').fadeIn();
				}else{$('#toTop').fadeOut();}
			});
			$('#toTop').click(function(){$('body,html').animate({scrollTop:0},800);});
		});
	</script>
    {{-- toTop --}}

</body>
</html>
