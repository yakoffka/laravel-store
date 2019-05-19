@extends('layouts.app')

@section('title')
catalog
@endsection

@section('content')
<div class="container">

    <h1>Products</h1>

    <div class="row">

        @foreach($products as $product)

        <div class="col-sm-4 product_card_bm">
            <div class="card">

                <h5><a href="/products/{{ $product->id }}">{{ $product->name }}</a></h5>

                <div class="center">
                    <a href="products/{{ $product->id }}" >
                        @if($product->image)
                            <img class="card-img-top img_lr" src="{{ asset('storage') }}/images/products/{{$product->id}}/{{$product->image}}" alt="{{ $product->name }}" style="">
                        @else
                            <img class="card-img-top img_lr" src="{{ asset('storage') }}/images/default/no-img.jpg" alt="no image">
                        @endif
                    </a>
                </div>

                <div class="card-body">
                    <p class="card-text">
                        <span class="grey">
                            @if($product->price)
                                price: {{ $product->price }} &#8381;
                            @else
                                priceless
                            @endif
                        </span><br>
                    </p>

                    <a href="products/{{ $product->id }}" class="btn btn-outline-primary">description</a>
                    <a href="products/destroy/{{ $product->id }}" class="btn btn-outline-danger">destroy</a>
                    
                </div>
            </div>
        </div>

        @endforeach

        <!-- pagination block -->
        @if($products->links())
            <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
        @endif

    </div>
</div>
@endsection
