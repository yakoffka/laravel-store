<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- <!-- CSRF Token --> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- https://connect.yandex.ru/pdd/ --}}
    {{-- <meta name="yandex-verification" content="d8186fcbcc7d8b8f" /> --}}
    <meta name="yandex-verification" content="{{env('YANDEX_VERIFICATION', '')}}" />

    {{-- <title>@yield('title') {{ config('custom.product_title_append') }}</title> --}}
    <title>@yield('title')</title>

    <meta name="description" content="@yield('description')"/>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery/1.11.2/jquery.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
  
    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com"> --}}
    <link href="{{ asset('fonts/proxima-nova/style.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/yo.css') }}" rel="stylesheet">
    <style>
        div.fw-background {
            background: #ffa500;
            background: linear-gradient(to right, #ffa500, #ea7500);
        }
    </style>
    @role('manager')<link href="{{ asset('css/manager.css') }}" rel="stylesheet">@endrole
    @role('admin')<link href="{{ asset('css/admin.css') }}" rel="stylesheet">@endrole
    @role('owner')<link href="{{ asset('css/owner.css') }}" rel="stylesheet">@endrole


    {{-- wysiwyg --}}
    @if (
        config('settings.description_wysiwyg') == 'ckeditor' or
        config('settings.modification_wysiwyg') == 'ckeditor' or
        config('settings.workingconditions_wysiwyg') == 'ckeditor'
    )
        <script src="{{ asset('wysiwyg/ckeditor/4.5.11/ckeditor.js') }}"></script>
        <script src="{{ asset('wysiwyg/ckeditor/4.5.11/adapters/jquery.js') }}"></script>
    @endif

    {{-- summernote --}}
    @if (
        config('settings.description_wysiwyg') == 'summernote' or
        config('settings.modification_wysiwyg') == 'summernote' or
        config('settings.workingconditions_wysiwyg') == 'summernote'
    )
        <link href="{{ asset('wysiwyg/summernote/summernote.css') }}" rel="stylesheet">
        <script src="{{ asset('wysiwyg/summernote/summernote.js') }}"></script>
    @endif

    {{-- tinymce --}}
    @if (
        config('settings.description_wysiwyg') == 'tinymce' or
        config('settings.modification_wysiwyg') == 'tinymce' or
        config('settings.workingconditions_wysiwyg') == 'tinymce'
    )
       <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script>
            var editor_config = {
                path_absolute : "/",
                selector: "textarea.tinymce-editor",
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
                file_browser_callback : function(field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;
        
                    var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                    if (type == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }
        
                    tinyMCE.activeEditor.windowManager.open({
                        file : cmsURL,
                        title : 'Filemanager',
                        width : x * 0.8,
                        height : y * 0.8,
                        resizable : "yes",
                        close_previous : "no"
                    });
                }
            };
        
            tinymce.init(editor_config);
        </script>
        {{-- <script src="{{ asset('wysiwyg/tinymce/4/tinymce.min.js') }}"></script> --}}
    @endif


    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="128x128" href="/favicon.png">
</head>
<body>
    {{-- <div id="app"> смотри resources/js/app.js закомментировал, тк не нашел лучшего решения для работы unisharp laravel-filemanager --}}
    <div id="app_">

        <div class="fw-background">{{-- from https://datatables.net/examples/basic_init/table_sorting.html --}}
            <div class="container relative">
                <a class="logo d-none d-md-block" href="{{ url('/') }}">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
                    {{-- <img src="/laravel_white.png" alt="logo"> --}}
                    <img src="{{ asset('storage') }}/images/common/logo_{{ config('custom.store_theme') }}.png" alt="logo">
                    
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

                
                {{-- main_menu --}}{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
                <ul class="main_menu d-none d-md-block">
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

                        {{-- d-md-none - Скрыто на экранах шире md --}}
                        {{-- <li class="nav-item d-md-none">
                            <a class="nav-link" href="/home">Home</a>
                        <li> --}}

                        {{-- d-md-none - Скрыто на экранах шире md --}}
                        {{-- <li class="nav-item d-md-none">
                            <a class="nav-link" href="/products">Catalog</a>
                        <li> --}}



                        {{-- cart --}}
                        @if ( config('settings.display_cart') )

                            {{-- d-none d-md-block - Скрыто на экранах меньше md --}}
                            <li class="nav-item d-none d-md-block">
                                <a href="{{ route('cart.show') }}" class="nav-link">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="badge">
                                        {{ (Session::has('cart') and Session::get('cart')->total_qty) ? Session::get('cart')->total_qty : '' }}
                                    </span>
                                </a>
                            <li>

                            {{-- d-md-none - Скрыто на экранах шире md --}}
                            <li class="nav-item d-md-none">
                                @if (Session::has('cart') and Session::get('cart')->total_qty)
                                    <a href="{{ route('cart.show') }}" class="nav-link">
                                        <i class="fas fa-shopping-cart"></i>
                                        in youre cart {{ Session::get('cart')->total_qty }} products
                                    </a>
                                @else
                                    <i class="fas fa-shopping-cart"></i> youre cart is empty
                                @endif
                            <li>
                        @endif
                        {{-- cart --}}


                        {{-- Authentication Links --}}
                        @guest

                            @if (Route::has('register') and config('settings.display_login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            
                            @if (Route::has('register') and config('settings.display_registration'))
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
                                    {{-- {{ Auth::user()->roles->first()->name }} --}}
                                    {{ Auth::user()->name }}<span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('dashboard') }}">{{__('Dashboard')}}</a>
                                    @permission('view_orders')
                                        <a class="dropdown-item" href="{{ route('orders.adminindex') }}">{{__('AllOrders')}}</a>
                                    @else
                                        <a class="dropdown-item" href="{{ route('orders.index') }}">{{__('Orders')}}</a>
                                    @endpermission
                                    <a class="dropdown-item" href="{{ route('users.show', ['user' => Auth::user()->id]) }}">{{__('Profile')}}</a>
                
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
                        {{-- Authentication Links --}}


                        {{-- d-md-none - Скрыто на экранах шире md --}}
                        {{-- <li class="nav-item d-md-none">
                            <a class="nav-link" href="https://github.com/yakoffka/kk" target="_blank">GitHub</a>
                        <li> --}}

                        {{-- search --}}{{-- d-md-none - Скрыто на экранах шире md --}}
                        <li class="nav-item d-md-none">
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
                {{-- <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Holy guacamole!</strong> Something went wrong..
                    <ol>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ol>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> --}}
                <div class="fixed_alert alert alert-danger alert-dismissible fade show" role="alert">
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


        {{-- videobackground --}}
        @if(!empty($videobackground) and false)
            <style>
                header {
                    position: relative;
                    background-color: black;
                    height: 100vh;
                    min-height: 25rem;
                    width: 100%;
                    overflow: hidden;
                }
            
                header video {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    min-width: 100%;
                    min-height: 100%;
                    width: auto;
                    height: auto;
                    z-index: 0;
                    -ms-transform: translateX(-50%) translateY(-50%);
                    -moz-transform: translateX(-50%) translateY(-50%);
                    -webkit-transform: translateX(-50%) translateY(-50%);
                    transform: translateX(-50%) translateY(-50%);
                }
            
                header .container {
                    position: relative;
                    z-index: 2;
                }
            
                header .overlay {
                    position: absolute;
                    top: 0;
                    /* top: -2em; */
                    left: 0;
                    height: 100%;
                    width: 100%;
                    background-color: black;
                    opacity: 0.5;
                    /* z-index: 1; */
                }
            
                @media (pointer: coarse) and (hover: none) {
                    header {
                        background: url('https://source.unsplash.com/XT5OInaElMw/1600x900') black no-repeat center center scroll;
                    }
                    header video {
                        display: none;
                    }
                }

                /* yo */
            
                header {
                    position: relative;
                    z-index: -2;
                    top: -3em;
                }
                header .overlay {
                    z-index: 1;
                }
            </style>
            
            <header>
                <div class="overlay">
                    <div class="container h-100">
                        <div class="d-flex h-100 text-center align-items-center">
                            <div class="w-100 text-white">
                            </div>
                        </div>
                    </div>            
                </div>
                <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
                    <source src="https://storage.googleapis.com/coverr-main/mp4/Mt_Baker.mp4" type="video/mp4">
                </video>
                <div class="container h-100">
                    <div class="d-flex h-100 text-center align-items-center">
                        <div class="w-100 text-white">
                    </div>
                </div>
            </header>
            <section class="my-5">
                <div class="container">
                    <div class="row">
                    <div class="col-md-8 mx-auto">
                        <p>The HTML5 video element uses an mp4 video as a source. Change the source video to add in your own background! The header text is vertically centered using flex utilities that are build into Bootstrap 4.</p>
                        <p>The overlay color can be changed by changing the <code>background-color</code> of the <code>.overlay</code> class in the CSS.</p>
                        <p>Set the mobile fallback image in the CSS by changing the background image of the header element within the media query at the bottom of the CSS snippet.</p>
                        <p class="mb-0">
                        Created by <a href="https://startbootstrap.com">Start Bootstrap</a>
                        </p>
                    </div>
                    </div>
                </div>
            </section>
            {{-- <header>
                <div class="overlay">
                    <div class="container h-100">
                        <div class="d-flex h-100 text-center align-items-center">
                            <div class="w-100 text-white">
                                <h1 class="display-1">Канат-Комплект</h1>
                                <p class="lead mb-0">Грузоподъемное оборудование собственного производства</p>
                                <div class="h1"><i class="blue fas fa-chevron-circle-down"></i></div>
                            </div>
                        </div>
                    </div>            
                </div>
                <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
                    <source src="{{ asset('videos/futaj.mp4') }}" type="video/mp4">
                </video>
            </header> --}}
        @else
        @endif
        {{-- /videobackground --}}
 
        <main class="py-4">

            {{-- @alert(['type' => 'primary', 'title' => 'roles/create'])
            @endalert --}}
            @if( !empty($success))
            @alert(['type' => 'primary', 'title' => 'success'])
                {{ $success }}
            @endalert
            @endif

            @if( session('message'))
                {{-- <div class="container p-0">
                    <div class="alert alert-success">
                        <div class="alert-title">{{ session('message') }}</div>
                    </div>
                </div> --}}
                <div class="fixed_alert alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="container">
                @yield('content')
            </div>

        </main>

        <div class="container">

            @include('layouts.partials.separator')

            <div class="grey denial_responsibility">                
                settings.email_categories_created = '{{ config('settings.email_categories_created') }}'. Администрация Сайта не несет ответственности за размещённые Пользователями материалы (в т.ч. информацию и изображения), их содержание и качество.
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
                    <a target="_blank" aria-label="Homepage" title="GitHub" class="footer-octicon mx-lg-4" href="https://github.com/yakoffka/kk" style="padding: 7px 7px 0;">
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
