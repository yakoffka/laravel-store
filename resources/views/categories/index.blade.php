@extends('layouts.app')

@section('title', 'Catalog')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            @if ( !empty($category) and $category->id > 1 )
                {{ Breadcrumbs::render('categories.show', $category) }}
            @else
                {{ Breadcrumbs::render('catalog') }}
            @endif
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>
        @if ( !empty($category) and $category->id > 1 )
            Категория "{{ $category->title }}"
        @else
            Каталог
        @endif
    </h1>

    <div class="grey ta_r">количество подкатегорий в категории: {{ $categories->count() }}</div>


    <div class="row">


        @include('layouts.partials.aside')


        {{-- content --}}
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
           <div class="row">
            @foreach($categories as $category)
                {{-- @if($category->id != 1) --}}
                {{-- @if( $category->products->count() ) --}}

                {{-- hide empty categories --}}
                @if ( !config('settings.show_empty_category') and !$category->countProducts() and !$category->countChildren() )
                    @continue
                @endif
                {{-- /hide empty categories --}}

                <div class="col-lg-4 col-md-6 product_card_bm">
                    <div class="card">

                        <h2 class="product_card_h2">
                            <a href="{{ route('categories.show', ['category' => $category->id]) }}">
                                {{ $category->title }}
                            </a>
                        </h2>

                        <a href="{{ route('categories.show', ['category' => $category->id]) }}">
                            @if($category->image)
                                <div class="card-img-top b_image"
                                    style="background-image: url({{ asset('storage') }}/images/categories/{{$category->id}}/{{$category->image}});">
                            @else
                                <div class="card-img-top b_image"
                                    style="background-image: url({{ asset('storage') }}{{ config('imageyo.default_img') }});">
                            @endif

                                <div class="dummy perc50"></div>
                                <div class="element"></div>
                            </div>
                        </a>


                        <div class="card-body">

                            <div class="row product_buttons center">

                                @guest

                                    <div class="col-sm-12">
                                        <a href="{{ route('categories.show', ['category' => $category->id]) }}"
                                            class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i> view all
                                        </a>
                                    </div>

                                @else

                                    @if ( Auth::user()->can( ['view_categories', 'edit_categories', 'delete_categories'], true ) )
                                        <div class="col-sm-4">
                                            <a href="{{ route('categories.show', ['category' => $category->id]) }}"
                                                class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>

                                        <div class="col-sm-4">
                                            <a href="{{ route('categories.edit', ['category' => $category->id]) }}"
                                                class="btn btn-outline-success">
                                                <i class="fas fa-pen-nib"></i>
                                            </a>
                                        </div>

                                        <div class="col-sm-4">
                                            <!-- form delete category -->
                                            <form action="{{ route('categories.destroy', ['category' => $category->id]) }}"
                                                method="POST">
                                                @csrf

                                                @method("DELETE")

                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                    @elseif ( Auth::user()->can( ['view_categories', 'edit_categories'], true ) )

                                        <div class="col-sm-6">
                                            <a href="{{ route('categories.show', ['category' => $category->id]) }}"
                                                class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>

                                        <div class="col-sm-6">
                                            <a href="{{ route('categories.edit', ['category' => $category->id]) }}"
                                                class="btn btn-outline-success">
                                                <i class="fas fa-pen-nib"></i>
                                            </a>
                                        </div>

                                    @elseif ( Auth::user()->can( 'view_categories' ) )

                                        <div class="col-sm-12">
                                            <a href="{{ route('categories.show', ['category' => $category->id]) }}"
                                                class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i> view
                                            </a>
                                        </div>

                                    @endif

                                @endguest

                            </div>
                        </div>
                    </div>
                </div>

            {{-- @endif --}}
            {{-- @endif --}}
            @endforeach
            </div>
        </div>

        {{-- <!-- pagination block -->
        @if($categories->links())
        <div class="row col-sm-12 pagination">{{ $categories->links() }}</div>
        @endif --}}

    </div>
    
{{-- </div> --}}
@endsection
