@extends('layouts.app')

@section('title', $category->title)

@section('content')
<div class="container">
    
    <h1>Products in {{ $category->title }} category</h1>
    <div class="description">description: {{ $category->description }}</div>
    <div class="description">visible: {{ $category->visible }}</div>
    <div class="description">parent category: {{ $category->parent->title }}</div>
    
    <!-- products -->
    <div class="row">

        {{-- {{ dd($category )}} --}}
        {{-- {{ dd($paginator->count()) }} --}}

        {{-- @if($category->products->count()) --}}
        @if($paginator->count())

            <!-- pagination block -->
            @if($paginator->links())
                <div class="row col-sm-12 pagination">{{ $paginator->links() }}</div>
            @endif
    
            @foreach($paginator as $product)

            <div class="col-lg-4 product_card_bm">
                <div class="card">

                    <h5 class="<?php if(!$product->visible){echo 'hide';}?>">
                        <a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->name }}</a>
                    </h5>

                    <a href="{{ route('products.show', ['product' => $product->id]) }}">

                        @if($product->image)
                            <div class="card-img-top b_image" style="background-image: url({{ asset('storage') }}/images/products/{{$product->id}}/{{$product->image}});">
                        @else
                            <div class="card-img-top b_image" style="background-image: url({{ asset('storage') }}/images/default/no-img.jpg);">
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
                                <?php if(!$product->visible){echo '<span class="red">invisible</span>';}?>
                            </span><br>
                        </p>

                        <div class="row product_buttons center">

                            @guest

                                <div class="col-sm-6">
                                    <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i> view
                                    </a>
                                </div>
                                    
                                <div class="col-sm-6">
                                    @addToCart(['product_id' => $product->id])
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
                                        <!-- form delete product -->
                                        <form action="{{ route('products.destroy', ['product' => $product->id]) }}" method="POST">
                                            @csrf

                                            @method("DELETE")

                                            <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
                                    
                                @endif

                                <div class="col-sm-6">
                                    <a href="{{ route('products.show', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i> view
                                    </a>
                                </div>
                                
                                <div class="col-sm-6">
                                    @addToCart(['product_id' => $product->id])
                                </div>

                            @endguest

                        </div>
                    </div>
                </div>
            </div>

            @endforeach

            <!-- pagination block -->
            @if($paginator->links())
                <div class="row col-sm-12 pagination">{{ $paginator->links() }}</div>
            @endif

        @else

            <h2>There are no products in this category..</h2>

        @endif

    </div>
    <!-- /products -->

</div>
@endsection
