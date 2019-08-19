@extends('layouts.app')

@section('title', 'result search')

@section('content')

    {{ Breadcrumbs::render('search') }}

    <h1 class="">Search Result</h1>

    <div class="row">

        {{-- aside --}}
        <div class="col col-sm-2 p-0 aside">    
            @include('layouts.partials.nav')
            @include('layouts.partials.separator')
            @include('layouts.partials.filters')
        </div>
        {{-- end aside --}}

        {{-- content --}}
        <div class="col col-sm-10 pr-0">
            

            @if ($products->total())

                <p>{{ $products->total() }} result for '{{ $query }}'</p>

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
                                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                                    {{ str_limit($product->description, 80) }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                                    {{ $product->price }}
                                </a>
                            </td>
                            {{-- <td>
                                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                                    в наличии
                                </a>
                            </td> --}}
                    @endforeach
                </table>
            @else

                no products
            
            @endif

            @if($products->appends($appends)->links())
                <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
            @endif

        </div>
        {{-- end content --}}
        
    </div>{{-- end row --}}

@endsection
