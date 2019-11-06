@extends('layouts.theme_switch')

@section('title', $category->name . config('custom.category_title_append'))

@section('description', $category->name . config('custom.category_description_append'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('categories.show', $category) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Категория "{{ $category->title }}"</h1>
    <div class="grey ta_r">количество подкатегорий в категории: {{ $categories->count() }}</div>


    <div class="row">
        @include('layouts.partials.aside')
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
           <div class="row">
                @foreach($categories as $category)

                    @if ( !config('settings.show_empty_category') and !$category->countProducts() and !$category->countChildren() )
                        @continue
                    @endif

                    @gridCategory(compact('category'))

                @endforeach
            </div>
        </div>
    </div>
@endsection
