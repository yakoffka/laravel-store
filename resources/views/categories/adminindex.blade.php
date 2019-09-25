@extends('layouts.app')


@section('title', 'список категорий')


@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('categories.adminindex',  auth()->user() ) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>список категорий</h1>


    <div class="row">
           
            
        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

           <div class="row">


                <table class="blue_table overflow_x_auto">
                    <tr>
                        <th>id</th>
                        <th>наименование</th>
                        <th class="verticalTableHeader ta_c">id род. категории</th>
                        <th class="verticalTableHeader ta_c">sort order</th>
                        <th class="verticalTableHeader ta_c">видимость</th>
                        <th class="verticalTableHeader ta_c">изображение</th>
                        <th class="verticalTableHeader ta_c">кол. товаров</th>
                        <th class="verticalTableHeader ta_c">кол. подкатегорий</th>
                        <th class="actions3">actions</th>
                    </tr>

                    @foreach($categories as $category)
                    @if ( $category->parent->id == 1 and $category->id != 1)
                        @categoryRow(['category' =>  $category, 'maincategory' => true])
                        @foreach($categories as $subcategory)
                            @if ( $subcategory->parent->id == $category->id )
                                @categoryRow(['category' =>  $subcategory,])
                            @endif
                        @endforeach
                    @endif
                    @endforeach

                </table>



                {{-- add new category --}}
                <a href="{{ route('categories.create') }}" class="btn btn-primary form-control pb-1">Создать новую категорию</a>
                <div class="row col-sm-12 pb-2"></div>
                {{-- /add new category --}}


                {{-- <!-- pagination block --> --}}
                {{-- @if($categories->links())
                    <div class="row col-sm-12 pagination">{{ $categories->links() }}</div>
                @endif --}}
                {{-- @if($categories->appends($appends)->links())
                    <div class="row col-sm-12 pagination">{{ $categories->links() }}</div>
                @endif --}}

            </div>
        </div>
        
    </div>{{-- <div class="row"> --}}
    
@endsection
