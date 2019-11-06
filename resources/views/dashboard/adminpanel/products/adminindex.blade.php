@extends('layouts.theme_switch')

@section('title', __('__list_products'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('products.adminindex') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ __('__list_products') }}</h1>


    <div class="row">


        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            {{-- table products --}}
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
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('__seeable') }}</div></th>
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('__parent_seeable') }}</div></th>
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('__grand_parent_seeable') }}</div></th>
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('__parent_category_id') }}</div></th>
                    <th width="60"><div class="verticalTableHeader ta_c">{{ __('__count_img') }}</div></th>
                    <th width="30"><div class="verticalTableHeader ta_c">{{ __('__price') }}</div></th>
                    <th class="actions4">{{ __('__actions') }}</th>
                </tr>

                @foreach ( $products as $product )
                    @productRow(['products' =>  $product, 'category' => true])
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

            {{-- pagination block --}}
            {{-- @if($products->links())
                <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
            @endif --}}
            @if($products->appends($appends)->links())
                <div class="row col-sm-12 pagination">{{ $products->links() }}</div>
            @endif

        </div>
    </div>
@endsection
