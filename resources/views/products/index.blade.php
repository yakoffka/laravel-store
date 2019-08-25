@extends('layouts.app')


{{-- title --}}
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
@elseif ( !empty($category) )
@php
    $title = $category->name;
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


@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col col-sm-9">
            @if ( !empty($category) )
                {{ Breadcrumbs::render('categories.show', $category) }}
            @else
                {{ Breadcrumbs::render('catalog') }}
            @endif
        </div>
        <div class="col col-sm-3">
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ $title }} ({{ $products->total() }})</h1>

    <div class="row">
           
            
        {{-- aside --}}
        {{-- <div class="col col-sm-2 p-0 aside">     --}}
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 p-0 aside">    
            @include('layouts.partials.nav')
            @include('layouts.partials.separator')
            @include('layouts.partials.filters')
        </div>

        {{-- content --}}
        {{-- <div class="col col-sm-10 pr-0"> --}}
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10 pr-0">
            <div class="row">

                {{ $mess_null }}

                @foreach($products as $product)

                <div class="col-lg-4 col-md-6 product_card_bm">
                    <div class="card">

                        <h5 class="<?php if(!$product->visible){echo 'hide';}?>"><a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->name }}</a></h5>

                        <a href="{{ route('products.show', ['product' => $product->id]) }}">
                            @if($product->images->count())
                                @php 
                                    $img = $product->images->first();
                                @endphp

                                <div 
                                    class="card-img-top b_image" 
                                    style="background-image: url({{
                                        asset('storage') . $img->path . '/' . $img->name . '-m' . $img->ext
                                    }});"
                                >
                            @else
                                <div 
                                    class="card-img-top b_image" 
                                    style="background-image: url({{ asset('storage') }}{{ config('imageyo.default_img') }});"
                                >
                            @endif
                                <div class="dummy"></div><div class="element"></div>
                            </div>
                        </a>

                        <div class="card-body p-1">
                            <div class="card-text col-sm-12">
                                <span class="grey">
                                    @if($product->price)
                                        price: {{ $product->price }} &#8381;
                                    @else
                                        priceless
                                    @endif
                                </span>
                                <?php if(!$product->visible){echo '<span class="red">invisible</span>';}?>
                                <br>
                            </div>

                            <div class="row product_buttons center">

                                @guest

                                    <div class="col-sm-6">
                                        <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye" title="view"></i>
                                        </a>
                                    </div>
                                        
                                    <div class="col-sm-6">
                                        <a href="{{ route('cart.add-item', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                            <i class="fas fa-cart-plus" title="to cart"></i> 
                                        </a>
                                    </div>

                                @else

                                    @if ( Auth::user()->can( ['edit_products', 'delete_products'], true ) )
                                        <div class="col-sm-3 p-1">
                                            <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>

                                        <div class="col-sm-3 p-1">
                                            <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                                <i class="fas fa-pen-nib"></i>
                                            </a>
                                        </div>

                                        <div class="col-sm-3 p-1">
                                            <a href="{{ route('actions.product', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                                <i class="fas fa-history"></i>
                                            </a>
                                        </div>
                                        

                                        <div class="col-sm-3 p-1">
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

                                        <div class="col-sm-6 p-1">
                                            <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>

                                        <div class="col-sm-6 p-1">
                                            <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                                <i class="fas fa-pen-nib"></i>
                                            </a>
                                        </div>
                                        
                                    @else

                                        <div class="col-sm-6 p-1">
                                            <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i> view
                                            </a>
                                        </div>
                                        
                                        <div class="col-sm-6 p-1">
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
        </div>
        
    </div>{{-- <div class="row"> --}}
    
@endsection
