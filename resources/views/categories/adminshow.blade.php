@extends('layouts.app')

@section('title', "Категория $category->title")

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('categories.adminshow', $category) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Просмотр категории '{{ $category->title }}'</h1>


    <div class="row">


        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            <h2>краткая информация</h2>
                
            <table class="blue_table overflow_x_auto">
                <tr><td class="th">id</td><td class="td">{{ $category->id }}</td></tr>
                <tr><td class="th">name</td><td class="td">{{ $category->name }}</td></tr>
                <tr><td class="th">slug</td><td class="td">{{ $category->slug }}</td></tr>
                <tr><td class="th">sort_order</td><td class="td">{{ $category->sort_order }}</td></tr>
                <tr><td class="th">title</td><td class="td">{{ $category->title }}</td></tr>
                <tr><td class="th">description</td><td class="td">{{ $category->description }}</td></tr>
                <tr><td class="th">visible</td><td class="td">{{ $category->visible }}</td></tr>
                <tr><td class="th">parent_id</td><td class="td">{{ $category->parent_id }}</td></tr>
                <tr><td class="th">added_by_user_id</td><td class="td">{{ $category->added_by_user_id }}</td></tr>
                <tr><td class="th">created_at</td><td class="td">{{ $category->created_at }}</td></tr>
                <tr><td class="th">updated_at</td><td class="td">{{ $category->updated_at }}</td></tr>
            </table>

            @if ( $category->countChildren() )
                {{-- table categories --}}
                <h2>В категории '{{ $category->title }}' находятся {{ $category->countChildren() }} подкатегорий:</h2>

                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th>id</th>
                        <th>наименование</th>
                        <th class="verticalTableHeader ta_c">id род. категории</th>
                        <th class="verticalTableHeader ta_c">sort order</th>
                        <th class="verticalTableHeader ta_c">видимость</th>
                        <th class="verticalTableHeader ta_c">кол. товаров</th>
                        <th class="verticalTableHeader ta_c">кол. подкатегорий</th>
                        <th class="actions3">actions</th>
                    </tr>

                    @foreach ( $category->children as $subcategory)
                        @categoryRow(['category' =>  $subcategory,])
                    @endforeach

                </table>
                {{-- /table categories --}}

            @elseif ( $category->countProducts() )
                {{-- table products --}}
                <h2>В категории '{{ $category->title }}' находятся {{ $category->countProducts() }} товаров:</h2>

                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>slug</th>
                        <th class="verticalTableHeader ta_c">manufacturer_id</th>
                        <th class="verticalTableHeader ta_c">visible</th>
                        <th class="verticalTableHeader ta_c">category_id</th>
                        <th class="verticalTableHeader ta_c">materials</th>
                        <th>description</th>
                        <th class="verticalTableHeader ta_c">year_manufacture</th>
                        <th class="verticalTableHeader ta_c">price</th>
                        <th class="verticalTableHeader ta_c">added_by_user_id</th>
                        <th class="verticalTableHeader ta_c">created_at</th>
                        <th class="verticalTableHeader ta_c">updated_at</th>

                        <th class="verticalTableHeader ta_c">images</th>
                        <th class="verticalTableHeader ta_c">куплено</th>

                        <th class="actions3">actions</th>
                    </tr>

                    @foreach ( $category->products as $product)
                        @productRow(['product' =>  $product,])
                    @endforeach

                </table>
                {{-- /table products --}}
            @endif

        </div>
    </div>
@endsection
