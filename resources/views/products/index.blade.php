@extends('layouts.theme_switch')


{{-- title and description --}}
{{-- filters --}}
@if ( !empty($appends['manufacturers']) || !empty($appends['categories']) )
    @php
        $h1 = 'Фильтр товаров';
        $title = 'Фильтр товаров';
        $filter = true;
        if ( $products->total() === 0 ) {
            $mess_null = 'нет товаров, удовлетворяющих заданным условиям';
        }
    @endphp

    {{-- $category --}}
@elseif ( !empty($category) )
    @php
        $h1 = "Категория '$category->title'";
        $title = $category->name . config('custom.category_title_append');
        if ( $products->total() === 0 ) {
            $mess_null = 'в данной категории ещё нет товаров';
        }
        $description = $category->name . config('custom.category_description_append') . ' Страница ' . $products->currentPage() . ' из ' . $products->lastPage();
    @endphp

    {{-- search --}}
@elseif ( !empty($query) )
    @php
        $h1 = 'Результаты поиска по запросу ' . '\'' . $query . '\'';
        $title = 'Найдено ' . $products->total() . ' товаров по запросу: ' . $query;
        // $description = 'Результаты поиска по запросу: ' . $query . '. Найдено ' . $products->total() . ' результатов.' . $products->total() > $products->count() ? 'Страница ' . $products->currentPage() . ' из ' . $products->lastPage() : '';
        $description = 'Результаты поиска по запросу: ' . $query . '. Найдено ' . $products->total() . ' результатов.' . 'Страница ' . $products->currentPage() . ' из ' . $products->lastPage();
        if ( $products->total() === 0 ) {
            $h1 = 'По запросу: "' . $query . '" не найдено ни одного товара';
            $mess_null = 'Сожалеем, но товаров по данному запросу не найдено. Попробуйте исправить запрос и повторить поиск еще раз.';
        }
    @endphp

    {{-- catalog all --}}
@else
    @php
        $h1 = 'Каталог товаров';
        $title = 'Каталог товаров' . config('custom.category_description_append') . ' Страница ' . $products->currentPage() . ' из ' . $products->lastPage();;
        $mess_null = '';

        if ( $products->total() === 0 ) {
            $mess_null = 'в данной категории ещё нет товаров';
        }
    @endphp
@endif
{{-- title and description --}}


@section('title', $title)

@section('description', $description ?? config('custom.category_description_append'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            @if ( isset( $filter ) && $filter )
                {{ Breadcrumbs::render('filter') }}
            @elseif ( !empty($category) )
                {{ Breadcrumbs::render('categories.show', $category) }}
            @else
                {{ Breadcrumbs::render('catalog') }}
            @endif
        </div>
        <div
            class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ $h1 }}</h1>
    <div class="grey ta_r">всего товаров: {{ $products->total() }}</div>

    <div class="row">


        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
            <div class="row">

                {{ $mess_null ?? '' }}

                @foreach($products as $product)

                    <div class="col-lg-4 col-md-6 product_card_bm">
                        <div class="card">

                            @include('layouts.partials.product_button')

                            <div class="price card-text">{!! $product->getActualPrice() !!}</div>


                            <a href="{{ route('products.show', $product) }}">
                                <div class="card-img-top b_image"
                                     style="background-image: url({{ asset('storage') }}{{$product->full_image_path_m}});">
                                    <div class="dummy perc100"></div>
                                    <div class="element"></div>
                                </div>
                            </a>


                            <h2 class="product_card_h2">
                                <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                            </h2>


                        </div>
                    </div>

                @endforeach

                {{-- pagination block --}}
                @if ( empty($appends) )
                    @if($products->links())
                        <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
                    @endif
                @else
                    @if($products->appends($appends)->links())
                        <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
                    @endif
                @endif
                {{-- /pagination block --}}


            </div>
        </div>

    </div>{{-- <div class="row"> --}}

@endsection
