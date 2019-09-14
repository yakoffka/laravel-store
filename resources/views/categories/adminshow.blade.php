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

            <h2>Сводная информация</h2>
                
            <table class="blue_table overflow_x_auto">
                <tr><td class="th ta_r">id</td><td class="td ta_l">{{ $category->id }}</td></tr>
                <tr><td class="th ta_r">name</td><td class="td ta_l">{{ $category->name }}</td></tr>
                <tr><td class="th ta_r">slug</td><td class="td ta_l">{{ $category->slug }}</td></tr>
                <tr><td class="th ta_r">sort_order</td><td class="td ta_l">{{ $category->sort_order }}</td></tr>
                <tr><td class="th ta_r">title</td><td class="td ta_l">{{ $category->title }}</td></tr>
                <tr><td class="th ta_r">description</td><td class="td ta_l">{{ $category->description }}</td></tr>
                <tr><td class="th ta_r">visible</td><td class="td ta_l">{{ $category->visible }}</td></tr>
                <tr><td class="th ta_r">parent_id</td><td class="td ta_l">{{ $category->parent_id }}</td></tr>
                <tr><td class="th ta_r">added_by_user_id</td><td class="td ta_l">{{ $category->added_by_user_id }}</td></tr>
                <tr><td class="th ta_r">created_at</td><td class="td ta_l">{{ $category->created_at }}</td></tr>
                <tr><td class="th ta_r">updated_at</td><td class="td ta_l">{{ $category->updated_at }}</td></tr>
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
                <h2>Категория содержит {{ $category->countProducts() }} товаров:</h2>

                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th width="30">id</th>
                        <th>name</th>
                        {{-- <th>slug</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">manufacturer_id</th> --}}
                        <th class="verticalTableHeader ta_c">visible</th>
                        {{-- <th class="verticalTableHeader ta_c">category_id</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">materials</th> --}}
                        {{-- <th>description</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">year_manufacture</th> --}}
                        <th>price</th>
                        {{-- <th class="verticalTableHeader ta_c">added_by_user_id</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">created_at</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">updated_at</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">images</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">куплено</th> --}}
                        <th class="actions3">actions</th>
                        {{-- <th>p</th> --}}

                    </tr>

                    @foreach ( $category->products as $product )
                        @productRow(['product' =>  $product,])
                    @endforeach

                </table>
                {{-- /table products --}}

                {{-- massupdate --}}
                выполнить с выделенными товарами
                <form id="products_{{ $category->id }}_massupdate" action="{{ route('categories.massupdate', $category) }}" method="POST">
                    @csrf
                    @method("PATCH")
                    {{-- <button type="submit">mass</button> --}}
                    <input type="submit" name="action" value="delete">
                    <input type="submit" name="action" value="replace">
                    <input type="submit" name="action" value="invisible">
                    <input type="submit" name="action" value="visible">
                </form>
                {{-- /massupdate --}}

            @elseif ( !$category->countProducts() and !$category->countChildren() )
                <h2>Категория пуста</h2>                
            @endif

        </div>
    </div>
@endsection
