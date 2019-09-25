@extends('layouts.app')

@if ( !empty($category) and $category->id > 1 )
    @section('title', $category->name . config('custom.category_title_append'))
    @section('description', 'lkjlkjl'.$category->name . config('custom.category_description_append'))
@else
    @section('title', 'Каталог - ' . config('custom.main_title_append'))
    @section('description', 'Каталог - ' . config('custom.main_description'))
@endif


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

                    {{-- hide empty categories --}}
                    @if ( !config('settings.show_empty_category') and !$category->countProducts() and !$category->countChildren() )
                        @continue
                    @endif
                    {{-- /hide empty categories --}}

                    @gridCategory(compact('category'))

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
