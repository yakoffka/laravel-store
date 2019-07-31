@extends('layouts.app')

@if ( !empty($appends['manufacturers']) or !empty($appends['categories']) )
    @php
        $title = 'Filters Products';
        $mess_null = '';

        if ( $products->total() == 0 ) {
            $mess_null = 'нет товаров, удовлетворяющих заданным условиям';
        }
    @endphp
@elseif ( !empty($appends['category']) )
    @php
        $title = 'Category Products';
        $mess_null = '';

        if ( $products->total() == 0 ) {
            $mess_null = 'нет товаров, удовлетворяющих заданным условиям';
        }
    @endphp
@else
    @php
        $title = 'All Products';
        $mess_null = '';

        if ( $products->total() == 0 ) {
            $mess_null = 'нет товаров, удовлетворяющих заданным условиям';
        }
    @endphp
@endif


@section('title', $title)

{{-- @section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection --}}

@section('content')
{{-- <div class="container"> --}}

    <h1>{{ $title }} ({{ $products->total() }})</h1>

    {{-- @alert(['type' => 'primary', 'title' => 'Title Forbidden'])
        <strong>Whoops!</strong> Something went wrong!
    @endalert --}}

    <div class="row">

        {{ $mess_null }}

        @foreach($products as $product)

        <div class="col-lg-4 product_card_bm pr-0">
            <div class="card">

                <h5 class="<?php if(!$product->visible){echo 'hide';}?>"><a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->name }}</a></h5>

                <a href="{{ route('products.show', ['product' => $product->id]) }}">
                    @if($product->images->count())
                        @php 
                            $img = $product->images->first();
                        @endphp

                        {{-- {{dd($img)}} --}}

                        <div 
                            class="card-img-top b_image" 
                            style="background-image: url({{
                                asset('storage') . $img->path . '/' . $img->name . '-m' . $img->ext
                            }});"
                        >
                    @else
                        <div 
                            class="card-img-top b_image" 
                            style="background-image: url({{ asset('storage') }}/images/default/noimg_l.png);"
                        >
                    @endif
                        <div class="dummy"></div><div class="element"></div>
                    </div>
                </a>

                <div class="card-body">
                    <p class="card-text col-sm-12">
                        <span class="grey">
                            @if($product->price)
                                price: {{ $product->price }} &#8381;
                            @else
                                priceless
                            @endif
                        </span>
                        <?php if(!$product->visible){echo '<span class="red">invisible</span>';}?>
                        <br>
                    </p>

                    <div class="row product_buttons center">

                        @guest

                            <div class="col-sm-6">
                                <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye"></i> view
                                </a>
                            </div>
                                
                            <div class="col-sm-6">
                                <a href="{{ route('cart.add-item', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                    <i class="fas fa-shopping-cart"></i> to cart
                                </a>
                            </div>

                        @else

                            @if ( Auth::user()->can( ['edit_products', 'delete_products'], true ) )
                                <div class="col-sm-4">
                                    <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>

                                <div class="col-sm-4">
                                    <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                </div>

                                <div class="col-sm-4">
                                    {{-- <!-- form delete product -->
                                    <form action="{{ route('products.destroy', ['product' => $product->id]) }}" method="POST">
                                        @csrf

                                        @method("DELETE")

                                        <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                        </button>
                                    </form> --}}
                                    {{-- @modalConfirmAction(['button' => 'danger', 'cssId' => 'del_' . $product->id, 'item' => $product]) --}}
                                    @modalConfirmDestroy([
                                        'btn_class' => 'btn btn-outline-danger form-control',
                                        'cssId' => 'delele_',
                                        'item' => $product,
                                        'action' => route('products.destroy', ['product' => $product->id]), 
                                      ]) 
                                    
                                </div>

                            @elseif ( Auth::user()->can('edit_products') )

                                <div class="col-sm-6">
                                    <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>

                                <div class="col-sm-6">
                                    <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                </div>
                                
                            @else

                                <div class="col-sm-6">
                                    <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i> view
                                    </a>
                                </div>
                                
                                <div class="col-sm-6">
                                    @addToCart(['product_id' => $product->id])
                                </div>

                            @endif

                        @endguest

                    </div>
                </div>
            </div>
        </div>

        @endforeach

        <!-- pagination block -->
        {{-- @if($products->links())
            <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
        @endif --}}
        @if($products->appends($appends)->links())
            <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
        @endif


    </div>
{{-- </div> --}}
@endsection
