@extends('dashboard.layouts.app')

@section('title', $product->name . config('custom.product_title_append'))

@section('description', $product->name . config('custom.product_description_append'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('products.adminshow', $product) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            <div class="d-none d-md-block">@include('layouts.partials.searchform')</div>
        </div>
    </div>

    <h1 class="<?php if(!$product->visible){echo 'hide';}?>">{{ $product->name }}</h1>

    <div class="row">


        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-5 mb-2 wrap_b_image">

                    @if ( $product->images->count() > 1 )
                        @carousel(compact('product'))
                    @elseif ( $product->images->count() === 1)
                        <div 
                            class="card-img-top b_image" 
                            style="background-image: url({{ asset('storage') . $product->images->first()->path . '/' . $product->images->first()->name . '-l' . $product->images->first()->ext }});"
                        >
                            <div class="dummy"></div><div class="element"></div>
                        </div>
                    @else
                        <div 
                            class="card-img-top b_image" 
                            style="background-image: url({{ asset('storage') }}{{ config('imageyo.default_img') }});"
                        >
                            <div class="dummy"></div><div class="element"></div>
                        </div>
                    @endif
                </div>


                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 mb-2 specification">

                    {{-- <span class="grey">manufacturer: </span>{{ $product->manufacturer ?? '-' }}<br> --}}
                    <span class="grey">{{__('__manufacturer')}}: </span>{{ $product->manufacturer->title ?? '-' }}<br>
                    <span class="grey">{{__('__materials')}}: </span>{{ $product->materials ?? '-' }}<br>
                    <span class="grey">{{__('__category')}}: </span><a href="{{ route('categories.show', ['category' => $product->category->id]) }}">{{ $product->category->name}}</a><br>
                    <span class="grey">{{ __('__visible') }}: </span>{{ $product->visible ? 'visible' : 'invisible' }}<br>
                    <span class="grey">{{__('__date_manufactured')}}: </span>{{ $product->date_manufactured ?? '-' }}<br>
                    <span class="grey">{{__('__vendor_code')}}: </span>{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}<br>


                    @if ( config('settings.display_prices') and $product->price)
                        <span class="grey">{{__('__price')}}: </span>{{ $product->price }} &#8381;<br>
                    @else
                        <span class="grey">{{ config('settings.priceless_text') }}</span><br>
                    @endif


                    @permission('edit_products')
                        <span class="grey">{{__('added_by_user_id')}}: </span>{{ $product->creator->name }}<br>
                        <span class="grey">{{__('created_at')}}: </span>{{ $product->created_at }}<br>
                        @if($product->updated_at != $product->created_at)
                            <span class="grey">{{__('edited_by_user_id')}}: </span>{{ $product->editor->name }}<br>
                            <span class="grey">{{__('updated_at')}}: </span>{{ $product->updated_at }}<br>
                        @endif
                        <span class="grey">{{__('__views')}}: </span>{{ $product->views }}<br>
                    @endpermission


                    <div class="row product_buttons">

                        @guest

                            @if ( config('settings.display_cart') )
                                <div class="col-sm-12">
                                    @addToCart(['product_id' => $product->id])
                                </div>
                            @endif

                        @else

                            @if ( Auth::user()->can( ['edit_products', 'delete_products'], true ) )

                                <div class="col-sm-4">
                                    <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>
                                </div>

                                <div class="col-sm-4">
                                    @modalConfirmDestroy([
                                        'btn_class' => 'btn btn-outline-danger form-control',
                                        'cssId' => 'delele_',
                                        'item' => $product,
                                        'action' => route('products.destroy', ['product' => $product->id]), 
                                    ]) 
                                </div>

                                <div class="col-sm-4">
                                    <a href="{{ route('products.copy', ['product' => $product->id]) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-copy"></i>
                                    </a>
                                </div>

                            @elseif ( Auth::user()->can('edit_products') )

                                <div class="col-sm-12">
                                    <a href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-outline-success">
                                        <i class="fas fa-pen-nib"></i> edit
                                    </a>
                                </div>
                                
                            @else

                                @if ( config('settings.display_cart') )
                                    <div class="col-sm-12">
                                        @addToCart(['product_id' => $product->id])
                                    </div>
                                @endif

                            @endif

                        @endguest

                    </div>
                </div>


                {{-- информация о доставке на экранах lg --}}
               <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 mb-2 shipping">
                    <div class="d-none d-lg-block">{{-- d-none d-lg-block - Скрыто на экранах меньше lg --}}
                        @include('layouts.partials.shipping')
                    </div>
                </div>
                {{-- /информация о доставке на экранах lg --}}

            </div><br>


            <ul class="nav nav-tabs" id="myTab" role="tablist">


                @if ($product->description)
                    <li class="nav-item">
                        <a title="{{ __('__Description') }} {{ $product->name }}" class="nav-link active" id="home-tab" 
                            data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                            {{ __('__Description') }}
                        </a>
                    </li>
                @endif

                @if ($product->modification)
                    <li class="nav-item">
                        <a title="Модификации {{ $product->name }}" class="nav-link{{ !$product->description ? ' active' : '' }}" id="profile-tab" 
                            data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                            Модификации
                        </a>
                    </li>
                @endif

                @if ($product->workingconditions)
                    <li class="nav-item">
                        <a title="Условия работы {{ $product->name }}" class="nav-link{{ (!$product->description and !$product->modification) ? ' active' : '' }}" id="contact-tab" 
                            data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                            Условия работы
                        </a>
                    </li>
                @endif

            </ul>
            
            <div class="tab-content" id="myTabContent">

                {{-- description --}}
                @if ($product->description)
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        {!! $product->description !!}
                    </div>
                @endif
                {{-- /description --}}

                {{-- modification --}}
                @if ($product->modification)
                    <div class="tab-pane fade{{ !$product->description ? ' show active' : '' }}" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h2 class="ta_c">Модификации {{ $product->name }}</h2>
                        {!! $product->modification !!}
                    </div>
                @endif
                {{-- /modification --}}

                {{-- workingconditions --}}
                @if ($product->workingconditions)
                    <div class="tab-pane fade{{ (!$product->description and !$product->modification) ? ' show active' : '' }}" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <h2 class="ta_c">Условия работы и меры безопасности</h2>
                        {!! $product->workingconditions !!}
                    </div>
                @endif
                {{-- /workingconditions --}}

            </div>



            {{-- информация о доставке на экранах уже lg --}}
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 mb-2 shipping">
                <div class="d-lg-none .d-xl-block">{{-- d-none d-lg-block - Скрыт только на lg --}}
                    @include('layouts.partials.shipping')
                </div>
            </div>
            {{-- /информация о доставке на экранах lg --}}


            <!-- /product -->
            @include('layouts.partials.separator')



            <!-- comments -->
            <div class="row">
                <div class="col-md-12">

                    @if($product->comments->count())

                        <h2>Комментарии к товару {{ $product->name }} ({{ $product->comments->count() }})</h2>
                        <ul class='content list-group'>

                        @foreach ($product->comments as $num_comment => $comment)
                            <li class="list-group-item" id="comment_{{ $comment->id }}" >
                                <div class="comment_header">

                                    @if($comment->user_id == 0)
                                        Гость {{ $comment->user_name }}
                                    @else
                                        {{ $comment->creator ? $comment->creator->name : 'RIP' }}
                                    @endif


                                    <!-- created_at/updated_at -->
                                    @if($comment->updated_at == $comment->created_at)
                                        опубликвано {{ $comment->created_at }}:
                                    @else
                                        опубликвано {{ $comment->created_at }} (редактировано: {{ $comment->updated_at }}):
                                    @endif

                                    @auth
                                        @if( $comment->creator and $comment->creator->id == Auth::user()->id )
                                        <span class="blue">Ваш комментарий</span>
                                        @endif
                                    @endauth

                                    <div class="comment_buttons">

                                        <div class="comment_num">#{{-- $comment->id --}}{{ $num_comment+1 }}</div>

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
                                        <form action="{{ route('comments.destroy', ['comment' => $comment->id]) }}" method="POST">
                                            @csrf

                                            @method("DELETE")

                                            <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                        @endpermission
                                    </div>

                                </div>

                                <div class="comment_str">{!! $comment->body !!}</div>{{-- enable html entities!! --}}
                                        
                                <?php if ( (Auth::user() and Auth::user()->can('create_products') or Auth::user() and Auth::user()->id == $comment->user_id )) { ?>

                                    <!-- form edit -->
                                    <form action="/comments/{{ $comment->id }}" method="POST" class="collapse" id="collapse_{{ $comment->id }}">

                                        @method("PATCH")
                                        
                                        @csrf

                                        <textarea id="body_{{ $comment->id }}" name="body" cols="30" rows="4" 
                                            class="form-control card" placeholder="Add a comment"><?php echo str_replace('<br>', "\r\n", $comment->body); ?></textarea>
                                        <button type="submit" class="btn btn-success">редактировать</button>
                                    </form>
                                <?php } ?>

                            </li>

                        @endforeach

                        </ul>

                    @else

                        <h2>Отзывы к товару {{ $product->name }}</h2>
                        <p class="grey">Отзывов ещё нет — ваш может стать первым.</p>

                    @endif

                </div>
            </div>
            <!-- /comments -->


            <!-- comment on -->
            <div class="row">
                <div class="col-md-12">

                    <h2>оставьте свой комментарий</h2>

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
                            <!-- <label for="body">Add a comment</label> -->
                            <textarea id="body" name="body" cols="30" rows="4" class="form-control" placeholder="Add a your comment" required>{{ old('body') }}</textarea>                       
                        </div>
                        <button type="submit" class="btn btn-primary">отправить</button>
                    </form>

                </div>
            </div>
            <!-- /comment on -->
        </div>
        
    </div>{{-- <div class="row"> --}}

@endsection
