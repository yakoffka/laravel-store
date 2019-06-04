@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="container">

    <h1>Categories</h1>

    <div class="row">


        @foreach($categories as $category)
        {{-- @if($category->id != 1) --}}
        {{-- @if( $category->products->count() ) --}}

        <div class="col-lg-6 product_card_bm">
            <div class="card">

                <h2 class="center">
                    <a href="{{ route('categories.show', ['category' => $category->id]) }}">
                        {{ $category->title }} ({{ $category->products->count() }})
                    </a>
                </h2>

                <a href="{{ route('categories.show', ['category' => $category->id]) }}">

                    @if($category->image)
                    <div class="card-img-top b_image"
                        style="background-image: url({{ asset('storage') }}/images/categories/{{$category->id}}/{{$category->image}});">
                        @else
                        <div class="card-img-top b_image"
                            style="background-image: url({{ asset('storage') }}/images/default/noimg.png);">
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
                                <i class="fas fa-eye"></i> view all in {{ $category->title }}
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
                                method='POST'>
                                @csrf

                                @method('DELETE')

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

        <!-- pagination block -->
        @if($categories->links())
        <div class="row col-sm-12 pagination">{{ $categories->links() }}</div>
        @endif

    </div>
</div>
@endsection
