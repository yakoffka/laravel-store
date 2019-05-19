@extends('layouts.app')

@section('title')
{{ $product->name }}
@endsection

@section('content')
<div class="container">
    
    <h1>{{ $product->name }}</h1>
    
    <div class="row">

        <div class="col-md-4">

            @if($product->image)
            <div class="card-img-top b_image" style="background-image: url({{ asset('storage') }}/images/products/{{$product->id}}/{{$product->image}});">
            @else
            <div class="card-img-top b_image" style="background-image: url({{ asset('storage') }}/images/default/no-img.jpg);">
            @endif

                <div class="dummy"></div>
                <div class="element"></div>
            </div>
        </div>

        <div class="col-md-8">
            <h2>specification</h2>

            <span class="grey">manufacturer: </span>{{ $product->manufacturer }}<br>
            <span class="grey">materials: </span>{{ $product->materials }}<br>
            <span class="grey">year_manufacture: </span>{{ $product->year_manufacture }}<br>
            <span class="grey">артикул: </span>{{ $product->id }}<br>

            @if($product->price)
                <span class="grey">price: </span>{{ $product->price }} &#8381;
            @else
                <span class="grey">priceless</span>
            @endif
            
            <br><br>
            <button type="button" class="btn btn-outline-success">buy now</button>
            <a href="products/destroy/{{ $product->id }}" class="btn btn-outline-danger">destroy</a>
            <br>

        </div>
    </div><br>

    <div class="row">
        <div class="col-md-12">
            <h2>description product {{ $product->name }}</h2>
            <p>{{ $product->description }}</p>
        </div>
    </div>

</div>
@endsection
