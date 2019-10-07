@extends('dashboard.layouts.app')

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
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>slug</th>
                    <th width="30" class="verticalTableHeader ta_c">sort_order</th>
                    <th>title</th>
                    <th>description</th>
                    <th width="60" class="verticalTableHeader ta_c">image</th>
                    <th width="30" class="verticalTableHeader ta_c">{{ __('added_by_user_id') }}</th>
                    <th width="30" class="verticalTableHeader ta_c">{{ __('edited_by_user_id') }}</th>
                    <th>created_at</th>
                    <th>edited_at</th>
                </tr>

                <tr>
                    <td>{{ $manufacturer->id }}</td>
                    <td>{{ $manufacturer->name }}</td>
                    <td>{{ $manufacturer->slug }}</td>
                    <td>{{ $manufacturer->sort_order }}</td>
                    <td>{{ $manufacturer->title }}</td>
                    <td>{!! $manufacturer->description !!}</td>
                    {{-- <td>{{ $manufacturer->image }}</td> --}}

                    {{-- image --}}
                    <td>
                        @if($manufacturer->imagespath)
                            <div class="card-img-top b_image"
                                style="background-image: url({{ asset('storage') }}/images/manufacturers/{{$manufacturer->id}}/{{$manufacturer->imagespath}});">
                        @else
                            <div class="card-img-top b_image"
                                style="background-image: url({{ asset('storage') }}{{ config('imageyo.default_img') }});">
                        @endif
                            <div class="dummy perc100"></div>
                            <div class="element"></div>
                        </div>
                    </td>
                    {{-- image --}}

                    <td title="{{ $manufacturer->creator->name }}">{{ $manufacturer->creator->id }}</td>
                    <td title="{{ $manufacturer->editor->name ?? '' }}">{{ $manufacturer->editor->id ?? '-' }}</td>
                    <td>{{ $manufacturer->created_at }}</td>
                    <td>{{ $manufacturer->edited_at }}</td>
                </tr>
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
                        {{-- <th width="30">id</th> --}}
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
                        <th>name</th>
                        {{-- <th>slug</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">manufacturer_id</th> --}}
                        <th class="verticalTableHeader ta_c">видимость</th>
                        <th class="verticalTableHeader ta_c">видимость родителя</th>
                        <th class="verticalTableHeader ta_c">видимость прародителя</th>
                        <th width="30" class="verticalTableHeader ta_c">category_id</th>
                        <th width="30" class="verticalTableHeader ta_c">изображений</th>
                        {{-- <th class="verticalTableHeader ta_c">materials</th> --}}
                        {{-- <th>description</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">date_manufactured</th> --}}
                        <th>price</th>
                        {{-- <th class="verticalTableHeader ta_c">added_by_user_id</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">created_at</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">updated_at</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">images</th> --}}
                        {{-- <th class="verticalTableHeader ta_c">куплено</th> --}}
                        <th class="actions4">actions</th>
                        {{-- <th>p</th> --}}

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