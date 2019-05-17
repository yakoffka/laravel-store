@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <h1>Products</h1>
    </div>

    <div class="row">

        @foreach($products as $product)

        <div class="col-sm-4 product_card_bm">
            <div class="card">
                <h5>{{ $product->name }}</h5>
            </div>
        </div>

        @endforeach

    </div>
</div>
@endsection
