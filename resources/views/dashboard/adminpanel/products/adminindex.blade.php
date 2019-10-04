@extends('dashboard.layouts.app')

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
                        >
                            id
                        </label>
                        @php
                            $oForms = '';
                        @endphp
                    </th>
                    <th>наименование</th>
                    {{-- <th>slug</th> --}}
                    {{-- <th class="verticalTableHeader ta_c">manufacturer_id</th> --}}
                    <th class="verticalTableHeader ta_c">видимость</th>
                    <th class="verticalTableHeader ta_c">видимость родителя</th>
                    <th class="verticalTableHeader ta_c">видимость прародителя</th>
                    <th width="30" class="verticalTableHeader ta_c">категория</th>
                    <th width="30" class="verticalTableHeader ta_c">изображений</th>
                    {{-- <th class="verticalTableHeader ta_c">materials</th> --}}
                    {{-- <th>description</th> --}}
                    {{-- <th class="verticalTableHeader ta_c">date_manufactured</th> --}}
                    <th>цена</th>
                    {{-- <th class="verticalTableHeader ta_c">added_by_user_id</th> --}}
                    {{-- <th class="verticalTableHeader ta_c">created_at</th> --}}
                    {{-- <th class="verticalTableHeader ta_c">updated_at</th> --}}
                    {{-- <th class="verticalTableHeader ta_c">images</th> --}}
                    {{-- <th class="verticalTableHeader ta_c">куплено</th> --}}
                    <th class="actions4">действия</th>
                    {{-- <th>p</th> --}}

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
