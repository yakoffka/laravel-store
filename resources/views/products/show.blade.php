@extends('layouts.app')

@section('title')
{{ $product->name }}
@endsection

@section('content')
<div class="container">
    
    <h1>{{ $product->name }}</h1>
    
    <div class="row">

        <div class="col-md-4 wrap_b_image">

            @if($product->image)
            <div class="card-img-top b_image" style="background-image: url({{ asset('storage') }}/images/products/{{$product->id}}/{{$product->image}});">
            @else
            <div class="card-img-top b_image" style="background-image: url({{ asset('storage') }}/images/default/no-img.jpg);">
            @endif

                <div class="dummy"></div><div class="element"></div>
            </div>
        </div>

        <div class="col-md-8">
            <h2>specification product</h2>

            <span class="grey">manufacturer: </span>{{ $product->manufacturer }}<br>
            <span class="grey">materials: </span>{{ $product->materials }}<br>
            <span class="grey">year_manufacture: </span>{{ $product->year_manufacture }}<br>
            <span class="grey">артикул: </span>{{ $product->id }}<br>

            @if($product->price)
                <span class="grey">price: </span>{{ $product->price }} &#8381;
            @else
                <span class="grey">priceless</span>
            @endif

            <div class="row product_buttons">

                <div class="col-sm-4">
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-cart"></i> buy now
                    </a>
                </div>

                <div class="col-sm-4">
                    <a href="{{ route('productsEdit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                        <i class="fas fa-pen-nib"></i> edit
                    </a>
                </div>

                <div class="col-sm-4">
                    <!-- form delete product -->
                    <form action="{{ route('productsDestroy', ['product' => $product->id]) }}" method='POST'>
                        @csrf

                        @method('DELETE')

                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i> delete
                        </button>
                    </form>
                </div>

            </div>

            
        </div>
    </div><br>

    <div class="row">
        <div class="col-md-12">
            <h2>description {{ $product->name }}</h2>
            <p>{{ $product->description }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h2>comments for {{ $product->name }}</h2>

            @if($product->comments->count())
                <ul class='content list-group'>

                @foreach ($product->comments as $comment)
                    <li class="list-group-item" id="comment_{{ $comment->id }}" >
                        <div class="comment_header">

                            @if($comment->guest_name)
                                Guest {{ $comment->guest_name }}
                            @else
                                User #{{ $comment->user_id }}
                            @endif


                            <!-- created_at/updated_at -->
                            @if($comment->updated_at == $comment->created_at)
                                wrote {{ $comment->created_at }}:
                            @else
                                wrote {{ $comment->created_at }} (edited: {{ $comment->updated_at }}):
                            @endif

                            <div class="comment_buttons">

                                <div class="comment_num">#{{ $comment->id }}</div>

                                <!-- button edit -->
                                <button type="button" class="btn btn-outline-success" data-toggle="collapse" 
                                    data-target="#collapse_{{ $comment->id }}" aria-expanded="false" aria-controls="coll" class="edit"
                                >
                                    <i class="fas fa-pen-nib"></i>
                                </button>

                                <!-- delete comment -->
                                <!-- <form action='/comments/destroy/{{ $comment->id }}' method='POST'> -->
                                <form action="{{ route('commentsDestroy', ['comment' => $comment->id]) }}" method="POST">
                                    @csrf

                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>

                        </div>

                        <div class="comment_str">{{$comment->comment_string}}</div>
                                
                        <!-- form edit -->
                        <form action="/comments/{{ $comment->id }}" 
                            method="POST" class="collapse" id="collapse_{{ $comment->id }}"
                        >
                            @method('PATCH')

                            @csrf

                            <textarea id="comment_string_{{ $comment->id }}" name="comment_string" 
                                cols="30" rows="4" class="form-control card" placeholder="Add a comment"
                            >{{$comment->comment_string}}</textarea>
                            <button type="submit" class="btn btn-success">edit comment</button>
                        </form>

                    </li>
                @endforeach

                </ul>
            @else
                <p class="grey">no comments for this product.</p>
            @endif

        </div>
    </div>


    <div class="row">
        <div class="col-md-12">

            <h2>leave your comment</h2>

            <form method="POST" action="/products/{{ $product->id }}/comments">
                @csrf
                <!-- <input type="hidden" name="product_id" value="{{ $product->id }}"> -->

                @auth
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="guest_name" value="">
                @else
                    <input type="hidden" name="user_id" value="0">
                    <div class="form-group">
                        <!-- <label for="guest_name">Your name</label> -->
                        <input type="text" id="guest_name" name="guest_name" class="form-control" placeholder="Your name" value="{{ old('guestName') }}" required>
                    </div>
                @endauth

                <div class="form-group">
                    <!-- <label for="comment_string">Add a comment</label> -->
                    <textarea id="comment_string" name="comment_string" cols="30" rows="4" class="form-control" placeholder="Add a your comment" required>{{ old('comment_string') }}</textarea>                       
                </div>
                <button type="submit" class="btn btn-primary">comment on</button>
            </form>

        </div>
    </div>

</div>
@endsection
