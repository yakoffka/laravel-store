@extends('layouts.app')

@section('title')
{{ $product->name }}
@endsection

@section('content')
<div class="container">
    
    <h1>{{ $product->name }}</h1>
    
    <!-- product -->
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
                <span class="grey">price: </span>{{ $product->price }} &#8381;<br>
            @else
                <span class="grey">priceless</span><br>
            @endif

            @permission('edit_products')

                <!-- created_at -->
                <span class="grey">добавлен: </span>{{ $product->added_by_user_id }}<br>
                <span class="grey">дата добавления: </span>{{ $product->created_at }}<br>

                @if($product->updated_at != $product->created_at)

                    <!-- updated_at -->
                    <span class="grey">обновлен: </span>{{ $product->edited_by_user_id }}<br>
                    <span class="grey">дата обновления: </span>{{ $product->updated_at }}<br>

                @endif

            @endpermission


            {{-- <div class="product_buttons">

                <div class="col-sm-4">
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-cart"></i> buy now
                    </a>
                </div>


                @permission('edit_products')

                    <div class="col-sm-4">
                        <a href="{{ route('productsEdit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                            <i class="fas fa-pen-nib"></i> edit
                        </a>
                    </div>

                @endpermission


                @permission('delete_products')

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

                @endpermission

            </div> --}}

            <div class="row product_buttons">

                @guest

                    <div class="col-sm-12 padding_left_0">
                        <a href="#" class="btn btn-outline-success">
                            <i class="fas fa-shopping-cart"></i> buy now
                        </a>
                    </div>

                @else

                    @if ( Auth::user()->can( ['view_products', 'edit_products', 'delete_products'], true ) )

                        <div class="col-sm-6 padding_left_0">
                            <a href="{{ route('productsEdit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i> edit
                            </a>
                        </div>

                        <div class="col-sm-6">
                            <!-- form delete product -->
                            <form action="{{ route('productsDestroy', ['product' => $product->id]) }}" method='POST'>
                                @csrf

                                @method('DELETE')

                                <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash"></i> delete
                                </button>
                            </form>
                        </div>
                    @elseif ( Auth::user()->can( ['view_products', 'edit_products'], true ) )

                        <div class="col-sm-12 padding_left_0">
                            <a href="{{ route('productsEdit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i> edit
                            </a>
                        </div>

                    @elseif ( Auth::user()->can( 'view_products' ) )

                        <div class="col-sm-12 padding_left_0">
                            <a href="#" class="btn btn-outline-success">
                                <i class="fas fa-shopping-cart"></i> buy now
                            </a>
                        </div>
                        
                    @endif

                @endguest

            </div>


        </div>
    </div><br>

    <div class="row">
        <div class="col-md-12">
            <h2>description {{ $product->name }}</h2>
            <p>{{ $product->description }}</p>
        </div>
    </div>
    <!-- /product -->


    <!-- comments -->
    <div class="row">
        <div class="col-md-12">

            @if($product->comments->count())

                <h2>comments for {{ $product->name }} ({{ $product->comments->count() }})</h2>
                <ul class='content list-group'>

                @foreach ($product->comments as $comment)
                    <li class="list-group-item" id="comment_{{ $comment->id }}" >
                        <div class="comment_header">

                            @if($comment->user_id == 0)
                                Guest {{ $comment->user_name }}
                            @else
                                {{ $comment->user_name }}
                            @endif


                            <!-- created_at/updated_at -->
                            @if($comment->updated_at == $comment->created_at)
                                wrote {{ $comment->created_at }}:
                            @else
                                wrote {{ $comment->created_at }} (edited: {{ $comment->updated_at }}):
                            @endif

                            <div class="comment_buttons">

                                <div class="comment_num">#{{ $comment->id }}</div>

                                <?php if ( (Auth::user() and Auth::user()->can('create_products') or Auth::user() and Auth::user()->id == $comment->user_id )) { ?>

                                    <!-- button edit -->
                                    <button type="button" class="btn btn-outline-success edit" data-toggle="collapse" 
                                        data-target="#collapse_{{ $comment->id }}" aria-expanded="false" aria-controls="coll"
                                    >
                                        <i class="fas fa-pen-nib"></i>
                                    </button>

                                <?php } ?>

                                @permission('delete_comments')
                                <!-- delete comment -->
                                <form action="{{ route('commentsDestroy', ['comment' => $comment->id]) }}" method="POST">
                                    @csrf

                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                                @endpermission
                            </div>

                        </div>

                        <div class="comment_str">{!! $comment->comment_string !!}</div>{{-- enable html entities!! --}}
                                
                        <?php if ( (Auth::user() and Auth::user()->can('create_products') or Auth::user() and Auth::user()->id == $comment->user_id )) { ?>

                            <!-- form edit -->
                            <form action="/comments/{{ $comment->id }}" method="POST" class="collapse" id="collapse_{{ $comment->id }}">

                                @method("PATCH")
                                
                                @csrf

                                <textarea id="comment_string_{{ $comment->id }}" name="comment_string" 
                                    cols="30" rows="4" class="form-control card" placeholder="Add a comment"><?php echo str_replace('<br>', "\r\n", $comment->comment_string); ?>
                                </textarea>
                                <button type="submit" class="btn btn-success">edit comment</button>
                            </form>
                        <?php } ?>

                    </li>
                @endforeach

                </ul>

            @else

                <h2>comments for {{ $product->name }}</h2>

                <p class="grey">no comments for this product.</p>

            @endif

        </div>
    </div>
    <!-- /comments -->


    <!-- comment on -->
    <div class="row">
        <div class="col-md-12">

            <h2>leave your comment</h2>

            <form method="POST" action="/products/{{ $product->id }}/comments">
                @csrf

                @auth
                
                @else
                    <div class="form-group">
                        <!-- <label for="user_name">Your name</label> -->
                        <input type="text" id="user_name" name="user_name" class="form-control" placeholder="Your name" value="{{ old('user_name') }}" required>
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
    <!-- /comment on -->

</div>
@endsection
