@extends('layouts.app')

@section('title', "Список товаров")

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('products.adminindex') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Список всех товаров</h1>


    <div class="row">


        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                {{-- table products --}}

                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th width="30">id</th>
                        <th>name</th>
                        {{-- <th>slug</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">manufacturer_id</th> --}}
                        <th width="30" class="verticalTableHeader ta_c">visible</th>
                        <th width="30" class="verticalTableHeader ta_c">category_id</th>
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

                    @foreach ( $products as $product )
                        @productRow(['product' =>  $product, 'category' => true])
                    @endforeach

                </table>
                {{-- /table products --}}

            выполнить с выделенными товарами
            <form id="products_{{ 1 }}_massupdate" action="{{ route('categories.massupdate', 1) }}" method="POST">
                @csrf
                @method("PATCH")
                {{-- <button type="submit">mass</button> --}}
                <input type="submit" name="action" value="delete">
                <input type="submit" name="action" value="replace">
                <input type="submit" name="action" value="invisible">
                <input type="submit" name="action" value="visible">
            </form>

            <!-- pagination block -->
            {{-- @if($products->links())
                <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
            @endif --}}
            @if($products->appends($appends)->links())
                <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
            @endif

        </div>
    </div>
@endsection
