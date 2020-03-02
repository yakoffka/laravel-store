@extends('layouts.theme_switch')

@section('title', $product->name . config('custom.product_title_append'))

@section('description', $product->name . config('custom.product_description_append'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('products.adminshow', $product) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            <div class="d-none d-md-block">@include('layouts.partials.searchform')</div>
        </div>
    </div>

    <h1 class="
        @if ( !$product->seeable )
         hide
        @endif
    ">
        {{ $product->name }}
    </h1>

    <div class="row">


        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-5 mb-2 wrap_b_image">

                    @if ( $product->images->count() > 1 )
                        @carousel(compact('product'))
                    @elseif ( $product->images->count() === 1)
                        <div
                            class="card-img-top b_image"
                            style="background-image: url({{ asset('storage') . $product->images->first()->path . '/' . $product->images->first()->name . '-l' . $product->images->first()->ext }});"
                        >
                            <div class="dummy"></div><div class="element"></div>
                        </div>
                    @else
                        <div
                            class="card-img-top b_image"
                            style="background-image: url({{ asset('storage') }}{{ config('imageyo.default_img') }});"
                        >
                            <div class="dummy"></div><div class="element"></div>
                        </div>
                    @endif
                </div>


                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 mb-2 specification">

                    {{-- <span class="grey">manufacturer: </span>{{ $product->manufacturer ?? '-' }}<br> --}}
                    <span class="grey">{{__('__manufacturer')}}: </span>{{ $product->manufacturer->title ?? '-' }}<br>
                    <span class="grey">{{__('__materials')}}: </span>{{ $product->materials ?? '-' }}<br>
                    <span class="grey">{{__('__category')}}: </span><a href="{{ route('categories.show', ['category' => $product->category->id]) }}">{{ $product->category->name}}</a><br>
                    <span class="grey">{{ __('__seeable') }}: </span>{{ $product->seeable ? 'seeable' : 'inseeable' }}<br>
                    <span class="grey">{{__('__date_manufactured')}}: </span>{{ $product->date_manufactured ?? '-' }}<br>
                    <span class="grey">{{__('__vendor_code')}}: </span>{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}<br>


                    {{-- @if ( config('settings.display_prices') && $product->price > 0 )--}}
                    @if ( $product->price > 0 && config('settings.display_prices') )
                        <span class="grey">{{__('__price')}}: </span>{{ $product->price }} &#8381;<br>
                    @else
                        <span class="grey">{{ config('settings.priceless_text') }}</span><br>
                    @endif


                    @permission('edit_products')
                        <span class="grey">{{__('added_by_user_id')}}: </span>{{ $product->creator->name }}<br>
                        <span class="grey">{{__('created_at')}}: </span>{{ $product->created_at }}<br>
                        @if($product->updated_at !== $product->created_at)
                            <span class="grey">{{__('edited_by_user_id')}}: </span>{{ $product->editor->name }}<br>
                            <span class="grey">{{__('updated_at')}}: </span>{{ $product->updated_at }}<br>
                        @endif
                        <span class="grey">{{__('__count_views')}}: </span>{{ $product->count_views }}<br>
                    @endpermission


                    <div class="row product_buttons">

                        @guest

                            @if ( config('settings.display_cart') )
                                <div class="col-sm-12">
                                    @addToCart(['product_id' => $product->id])
                                </div>
                            @endif

                        @else

                            @if ( auth()->user()->can( ['edit_products', 'delete_products'], true ) )

                                <div class="col-sm-4">
                                    <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                </div>

                                <div class="col-sm-4">
                                    @modalConfirmDestroy([
                                        'btn_class' => 'btn btn-outline-danger form-control',
                                        'cssId' => 'delete_',
                                        'item' => $product,
                                        'action' => route('products.destroy', ['product' => $product->id]),
                                    ])
                                </div>

                                <div class="col-sm-4">
                                    <a href="{{ route('products.copy', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-copy"></i>
                                    </a>
                                </div>

                            @elseif ( auth()->user()->can('edit_products') )

                                <div class="col-sm-12">
                                    <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                        <i class="fas fa-pen-nib"></i> edit
                                    </a>
                                </div>

                            @else

                                @if ( config('settings.display_cart') )
                                    <div class="col-sm-12">
                                        @addToCart(['product_id' => $product->id])
                                    </div>
                                @endif

                            @endif

                        @endguest

                    </div>
                </div>


                {{-- информация о доставке на экранах lg --}}
               <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 mb-2 shipping">
                    <div class="d-none d-lg-block">{{-- d-none d-lg-block - Скрыто на экранах меньше lg --}}
                        @include('layouts.partials.shipping')
                    </div>
                </div>
                {{-- /информация о доставке на экранах lg --}}

            </div><br>


            <ul class="nav nav-tabs" id="myTab" role="tablist">


                @if ($product->description)
                    <li class="nav-item">
                        <a title="{{ __('__Description') }} {{ $product->name }}" class="nav-link active" id="home-tab"
                            data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                            {{ __('__Description') }}
                        </a>
                    </li>
                @endif

                @if ($product->modification)
                    <li class="nav-item">
                        <a title="Модификации {{ $product->name }}" class="nav-link{{ !$product->description ? ' active' : '' }}" id="profile-tab"
                            data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                            Модификации
                        </a>
                    </li>
                @endif

                @if ($product->workingconditions)
                    <li class="nav-item">
                        <a title="Условия работы {{ $product->name }}" class="nav-link{{ (!$product->description and !$product->modification) ? ' active' : '' }}" id="contact-tab"
                            data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                            Условия работы
                        </a>
                    </li>
                @endif

            </ul>

            <div class="tab-content" id="myTabContent">

                {{-- description --}}
                @if ($product->description)
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        {!! $product->description !!}
                    </div>
                @endif
                {{-- /description --}}

                {{-- modification --}}
                @if ($product->modification)
                    <div class="tab-pane fade{{ !$product->description ? ' show active' : '' }}" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h2 class="ta_c">Модификации {{ $product->name }}</h2>
                        {!! $product->modification !!}
                    </div>
                @endif
                {{-- /modification --}}

                {{-- workingconditions --}}
                @if ($product->workingconditions)
                    <div class="tab-pane fade{{ (!$product->description and !$product->modification) ? ' show active' : '' }}" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <h2 class="ta_c">Условия работы и меры безопасности</h2>
                        {!! $product->workingconditions !!}
                    </div>
                @endif
                {{-- /workingconditions --}}

            </div>


            {{-- информация о доставке на экранах уже lg --}}
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 mb-2 shipping">
                <div class="d-lg-none .d-xl-block">{{-- d-none d-lg-block - Скрыт только на lg --}}
                    @include('layouts.partials.shipping')
                </div>
            </div>
            {{-- /информация о доставке на экранах lg --}}

            @include('layouts.partials.separator')
            @include('layouts.partials.comment')
            @include('layouts.partials.separator')
            @include('layouts.partials.comment_on')
        </div>

    </div>{{-- <div class="row"> --}}

@endsection
