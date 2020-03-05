@extends('layouts.theme_switch')

@section('title', __('Manufacturers_show'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('manufacturers.show', $manufacturer) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ __('Manufacturers_show') }}</h1>


    <div class="row">


        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            {{-- table manufacturer --}}
            <table class="blue_table overflow_x_auto">
                <tr><td class="th ta_r">{{ __('__id') }}</td><td class="td ta_l">{{ $manufacturer->id }}</td></tr>
                <tr><td class="th ta_r">{{ __('__name') }}</td><td class="td ta_l">{{ $manufacturer->name }}</td></tr>
                <tr><td class="th ta_r">{{ __('__slug') }}</td><td class="td ta_l">{{ $manufacturer->slug }}</td></tr>
                <tr><td class="th ta_r">{{ __('sort_order') }}</td><td class="td ta_l">{{ $manufacturer->sort_order }}</td></tr>
                <tr><td class="th ta_r">{{ __('title') }}</td><td class="td ta_l">{{ $manufacturer->title }}</td></tr>
                <tr><td class="th ta_r">{{ __('description') }}</td><td class="td ta_l">{{ $manufacturer->description }}</td></tr>
                {{-- <tr><td class="th ta_r">{{ __('__img') }}</td><td class="td ta_l">{{ $manufacturer->publish }}</td></tr> --}}
                <tr><td class="th ta_r">{{ __('added_by_user_id') }}</td><td class="td ta_l">{{ $manufacturer->added_by_user_id }}</td></tr>
                <tr><td class="th ta_r">{{ __('edited_by_user_id') }}</td><td class="td ta_l">{{ $manufacturer->edited_by_user_id }}</td></tr>
                <tr><td class="th ta_r">{{ __('created_at') }}</td><td class="td ta_l">{{ $manufacturer->created_at }}</td></tr>
                <tr><td class="th ta_r">{{ __('updated_at') }}</td><td class="td ta_l">{{ $manufacturer->updated_at }}</td></tr>
            </table>
            {{-- /table manufacturer --}}


            <a href="{{ route('manufacturers.edit', $manufacturer) }}"
                class="btn btn-outline-success form-control">{{ __('Manufacturers_edit') }}
            </a>

            @if ( $manufacturer->countProducts() )
                {{-- table products --}}
                <br><br><h2>{{ $manufacturer->countProducts() }} товаров:</h2>

                <table class="blue_table overflow_x_auto">
                    <tr>
                        {{-- <th class="w30">id</th> --}}
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

                    @foreach ( $manufacturer->products as $product )
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

            @endif

        </div>
    </div>
@endsection
