@extends('layouts.theme_switch')


@section('title', __('__list_categories'))


@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('categories.adminindex',  auth()->user() ) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>

    <h1>{{ __('__list_categories') }}</h1>

    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

           <div class="row">

                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th class='w30'>{{ __('__id') }}</th>
                        <th>{{ __('__name') }}</th>
                        <th class='w30'><div class="verticalTableHeader ta_c">{{ __('__parent_category_id') }}</div></th>
                        <th class='w30'><div class="verticalTableHeader ta_c">{{ __('sort_order') }}</div></th>
                        <th class='w30'><div class="verticalTableHeader ta_c">{{ __('__seeable') }}</div></th>
                        <th class='w30'><div class="verticalTableHeader ta_c">{{ __('__parent_seeable') }}</div></th>
                        <th class='w60'><div class="verticalTableHeader ta_c">{{ __('__img') }}</div></th>
                        <th class='w30'><div class="verticalTableHeader ta_c">{{ __('__count_subcategories') }}</div></th>
                        <th class='w30'><div class="verticalTableHeader ta_c">{{ __('__count_products') }}</div></th>
                        <th class="actions3">{{ __('__actions') }}</th>
                    </tr>

                    @foreach ( $categories as $category )
                        @if ( isset($category->parent) && $category->parent->id === 1 && $category->id !== 1)
                            @categoryRow(['category' =>  $category, 'maincategory' => true])
                            @foreach($category->children as $subcategory)
                                @categoryRow(['category' =>  $subcategory,])
                            @endforeach
                        @endif
                    @endforeach

                </table>


                {{-- add new category --}}
                <a href="{{ route('categories.create') }}" class="btn btn-primary form-control pb-1">{{ __('__create_category') }}</a>
                <div class="row col-sm-12 pb-2"></div>
                {{-- /add new category --}}

            </div>
        </div>

    </div>{{-- <div class="row"> --}}

@endsection
