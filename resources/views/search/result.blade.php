@extends('layouts.app')

@section('title', 'Результаты поиска по запросу: ' . $query)

@section('description', 'Поиск товаров. ' . config('custom.main_description'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('search') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>

    <h1 class="">Поиск товаров</h1>

    <div class="row">

        {{-- aside --}}
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 p-0 aside">    
            @include('layouts.partials.nav')
            @include('layouts.partials.separator')
            @include('layouts.partials.filters')
        </div>
        {{-- end aside --}}

        {{-- content --}}
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
            

            @if ($products->total())

                <p>Найдено {{ $products->total() }} результатов для запроса '{{ $query }}'</p>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Наименование</th>
                            <th>Описание</th>
                            <th>Цена</th>
                            {{-- <th>Наличие</th> --}}
                        </tr>
                    </thead>
                    @foreach( $products as $product )
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td>
                                {{ str_limit($product->description, 80) }}
                            </td>
                            <td>
                                {{ $product->price }}
                            </td>
                            {{-- <td>
                                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                                    в наличии
                                </a>
                            </td> --}}
                    @endforeach
                </table>
            @else

                <h5>К сожалению по поисковому запросу "{{ $query }}" не найдено ни одного товара.</h5>
                <h5>Попробуйте уточнить запрос или воспользуйтесь <a href="{{ route('products.index') }}">каталогом</a>.</h5>
            
            @endif

            @if($products->appends($appends)->links())
                <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
            @endif

        </div>
        {{-- end content --}}
        
    </div>{{-- end row --}}

@endsection
