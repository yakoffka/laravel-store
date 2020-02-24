@extends('layouts.theme_switch')

@section('title', 'Каталог - ' . config('custom.main_title_append'))

@section('description', 'Каталог - ' . config('custom.main_description'))

@section('content')
    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('catalog') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>

    <h1>Каталог</h1>
    <div class="grey ta_r">количество подкатегорий в категории: {{ $globalCategories->count() }}</div>

    <div class="row">

        @include('layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
           <div class="row">
                @foreach($globalCategories as $category)
                    @categoryGrid(compact('category'))
                @endforeach
            </div>
        </div>

    </div>
@endsection
