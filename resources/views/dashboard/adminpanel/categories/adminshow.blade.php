@extends('layouts.theme_switch')

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


        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">

            {{--<h2>Сводная информация</h2>

            <table class="blue_table overflow_x_auto">
                <tr><td class="th ta_r">{{ __('__id') }}</td><td class="td ta_l">{{ $category->id }}</td></tr>
                <tr><td class="th ta_r">{{ __('__name') }}</td><td class="td ta_l">{{ $category->name }}</td></tr>
                <tr><td class="th ta_r">{{ __('__slug') }}</td><td class="td ta_l">{{ $category->slug }}</td></tr>
                <tr><td class="th ta_r">{{ __('sort_order') }}</td><td class="td ta_l">{{ $category->sort_order }}</td></tr>
                <tr><td class="th ta_r">{{ __('title') }}</td><td class="td ta_l">{{ $category->title }}</td></tr>
                <tr><td class="th ta_r">{{ __('description') }}</td><td class="td ta_l">{{ $category->description }}</td></tr>
                <tr><td class="th ta_r">{{ __('imagepath') }}</td><td class="td ta_l">{{ $category->imagepath }}</td></tr>
                <tr><td class="th ta_r">{{ __('publish') }}</td><td class="td ta_l">{{ $category->publish }}</td></tr>
                <tr><td class="th ta_r">{{ __('parent_publish') }}</td><td class="td ta_l">{{ $category->parent->publish ? 'on' : 'off' }}</td></tr>
                <tr><td class="th ta_r">{{ __('__parent_category_id') }}</td><td class="td ta_l">{{ $category->parent_id }}</td></tr>
                <tr><td class="th ta_r">{{ __('added_by_user_id') }}</td><td class="td ta_l">{{ $category->added_by_user_id }}</td></tr>
                <tr><td class="th ta_r">{{ __('edited_by_user_id') }}</td><td class="td ta_l">{{ $category->edited_by_user_id }}</td></tr>
                <tr><td class="th ta_r">{{ __('created_at') }}</td><td class="td ta_l">{{ $category->created_at }}</td></tr>
                <tr><td class="th ta_r">{{ __('updated_at') }}</td><td class="td ta_l">{{ $category->updated_at }}</td></tr>
            </table>--}}

            @if ( $category->subcategories->count() )
                {{-- table categories --}}
                <h2>В категории '{{ $category->title }}' находятся {{ $category->subcategories->count() }} подкатегорий:</h2>

                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th class="w30">{{ __('__id') }}</th>
                        <th>{{ __('__name') }}</th>
                        <th class="w30"><div class="verticalTableHeader ta_c">{{ __('__parent_category_id') }}</div></th>
                        <th class="w30"><div class="verticalTableHeader ta_c">{{ __('sort_order') }}</div></th>
                        <th class="w30"><div class="verticalTableHeader ta_c">{{ __('publish') }}</div></th>
                        <th class="w30"><div class="verticalTableHeader ta_c">{{ __('parent_publish') }}</div></th>
                        <th class="w60"><div class="verticalTableHeader ta_c">{{ __('__img') }}</div></th>
                        <th class="w30"><div class="verticalTableHeader ta_c">{{ __('__count_subcategories') }}</div></th>
                        <th class="w30"><div class="verticalTableHeader ta_c">{{ __('__count_products') }}</div></th>
                        <th class="actions3">{{ __('__actions') }}</th>
                    </tr>

                    @foreach ( $category->subcategories as $subcategory)
                        @categoryRow(['category' =>  $subcategory,])
                    @endforeach

                </table>
                {{-- /table categories --}}

            @elseif ( $category->products->count() )
                {{-- table products --}}
                <h2>Категория содержит {{ $category->products->count() }} товаров:</h2>

                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th class="ta_c left_stylized_checkbox">
                            <input
                                form="products_massupdate"
                                type="checkbox"
                                name="check_total"
                                value=""
                                id="checkbox_total"
                                onClick="check_all_products(this.form,this.checked)"
                            >
                            <label class="empty_label" for="checkbox_total"
                                onClick="check_all_products(this.form,['check_total'].checked)"
                            >{{ __('__id') }}</label>
                            @php
                                $oForms = '';
                            @endphp
                        </th>
                        <th>{{ __('__name') }}</th>
                        <th class="w30"><div class="verticalTableHeader ta_c">{{ __('publish') }}</div></th>
                        <th class="w30"><div class="verticalTableHeader ta_c">{{ __('parent_publish') }}</div></th>
                        <th class="w30"><div class="verticalTableHeader ta_c">{{ __('__grand_parent_publish') }}</div></th>
                        <th class="w30"><div class="verticalTableHeader ta_c">{{ __('__parent_category_id') }}</div></th>
                        <th class="w60"><div class="verticalTableHeader ta_c">{{ __('__count_img') }}</div></th>
                        <th class="w30"><div class="verticalTableHeader ta_c">{{ __('__price') }}</div></th>
                        <th class="actions4">{{ __('__actions') }}</th>
                    </tr>

                    @foreach ( $category->products as $product )
                        @productRow(['product' => $product])
                        @php
                            $oForms .= 'oForm[\'products[' . $product->id . ']\'].checked = checked;';
                        @endphp
                    @endforeach

                    <script type="text/javascript">
                        function check_all_products(oForm,checked){{!! $oForms !!}}
                    </script>


                </table>
                {{-- /table products --}}

                {{-- massupdate --}}
                @formProductsMassupdate
                {{-- /massupdate --}}

            @elseif ( !$category->products->count() and !$category->subcategories->count() )
                <h2 class="blue center">Категория пуста</h2>

                <div class="row justify-content-center">
                    @if ( $category->parent->id === 1 )
                        <a href="{{ route('categories.create') }}?parent_id={{ $category->id }}"
                            class="btn btn-primary col-5 m-2">добавить подкатегорию</a>
                    @endif

                    <a href="{{ route('products.create') }}?parent_id={{ $category->id }}"
                        class="btn btn-primary col-5 m-2">добавить товар</a>
                </div>
            @endif

        </div>
    </div>
@endsection
