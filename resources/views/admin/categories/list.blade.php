@extends('layouts.app')


@section('title', 'список категорий')


@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{-- @if ( empty($directive) )
                {{ Breadcrumbs::render('admin.categories.index',  auth()->user() ) }}
            @else
                {{ Breadcrumbs::render('directives.index',  auth()->user() ) }}
            @endif --}}
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
                        <th class="verticalTableHeader ta_c">кол. товаров</th>
                        <th class="verticalTableHeader ta_c">кол. подкатегорий</th>
                        <th class="actions3">actions</th>
                    </tr>

                    @foreach($categories as $category)
                    @if ( $category->parent->id == 1 and $category->id != 1)
                        <tr class="{{ !$category->visible ? 'gray' : '' }}{{ $category->parent_id == 1 ? ' main_category' : '' }}">
                            <td>{{ $category->id }}</td>
                            <td class="ta_l">{{ $category->name }}</td>
                            <td>{{ $category->parent->id ?? '-' }}</td>
                            <td>{{ $category->sort_order }}</td>

                            {{-- <td>{{ $category->visible }}</td> --}}
                            <td>
                                {{-- @modalSelectAdm([
                                    'id' => $category->id . '_visible',
                                    'value' => $category->visible, 
                                    'options' => $taskspriorities, 
                                    'action' => route('tasks.update', ['task' => $task]),
                                    'select_name' => 'taskspriority_id',
                                ]) --}}
                                {{-- <div class="center_stylized_checkbox">
                                    <input 
                                        type="checkbox" 
                                        id="category_visible_{{ $category->id }}" 
                                        name="visible" 
                                        value="{{ $category->visible }}" 
                                        {{ $category->visible ? 'checked' : '' }}
                                    >
                                    <label for="category_visible_{{ $category->id }}"></label>
                                </div> --}}
                                @if ( $category->visible )
                                    <i class="far fa-eye"></i>
                                @else
                                    <i class="far fa-eye-slash"></i>
                                @endif
                            </td>

                            <td>{{ $category->countProducts() }}</td>
                            <td>{{ $category->countChildren() }}</td>

                            {{-- actions --}}
                            <td>

                                {{-- view --}}
                                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-primary"><i class="fas fa-eye"></i></a>
                                
                                {{-- edit --}}
                                <a href="{{ route('admin.categories.edit', ['category' => $category->id]) }}"
                                    class="btn btn-outline-success">
                                    <i class="fas fa-pen-nib"></i>
                                </a>

                                {{-- delete --}}
                                @if ( $category->countProducts() or $category->countChildren() )
                                    <button type="button" 
                                        class="btn btn-outline-second align-self-center" 
                                        title="Категория {{ $category->name }} не может быть удалена, пока в ней находятся товары или подкатегории."
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    @permission('delete_categories')
                                        @modalConfirmDestroy([
                                            'btn_class' => 'btn btn-outline-danger align-self-center',
                                            'cssId' => 'delele_',
                                            'item' => $category,
                                            'type_item' => 'категорию',
                                            'action' => route('admin.categories.destroy', $category), 
                                        ])
                                    @endpermission
                                @endif

                            </td>
                        </tr>

                        @foreach($categories as $subcategory)
                        @if ( $subcategory->parent->id == $category->id )

                            <tr class="{{ !$subcategory->visible ? 'gray' : '' }}{{ $subcategory->parent_id == 1 ? ' main_subcategory' : '' }}">
                                <td>{{ $subcategory->id }}</td>
                                <td class="ta_l subcategory">{{ $subcategory->name }}</td>
                                <td>{{ $subcategory->parent->id ?? '-' }}</td>
                                <td>{{ $subcategory->sort_order }}</td>

                                {{-- <td>{{ $subcategory->visible }}</td> --}}
                                <td>
                                    @if ( $subcategory->visible )
                                        <i class="far fa-eye"></i>
                                    @else
                                        <i class="far fa-eye-slash"></i>
                                    @endif
                                </td>

                                <td>{{ $subcategory->countProducts() }}</td>
                                <td>{{ $subcategory->countChildren() }}</td>

                                {{-- actions --}}
                                <td>

                                    {{-- view --}}
                                    <a href="{{ route('admin.categories.show', $subcategory) }}" class="btn btn-outline-primary"><i class="fas fa-eye"></i></a>
                                
                                    {{-- edit --}}
                                    <a href="{{ route('admin.categories.edit', ['category' => $subcategory->id]) }}"
                                        class="btn btn-outline-success">
                                        <i class="fas fa-pen-nib"></i>
                                    </a>

                                    {{-- delete --}}
                                    @if ( $subcategory->countProducts() or $subcategory->countChildren() )
                                        <button type="button" 
                                            class="btn btn-outline-second align-self-center" 
                                            title="Категория {{ $subcategory->name }} не может быть удалена, пока в ней находятся товары или подкатегории."
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        @permission('delete_categories')
                                            @modalConfirmDestroy([
                                                'btn_class' => 'btn btn-outline-danger align-self-center',
                                                'cssId' => 'delele_',
                                                'item' => $subcategory,
                                                'type_item' => 'категорию',
                                                'action' => route('admin.categories.destroy', $subcategory), 
                                            ])
                                        @endpermission
                                    @endif

                                </td>
                            </tr>

                        @endif
                        @endforeach
    

                    @endif
                    @endforeach

                </table>



                {{-- add new category --}}
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary form-control pb-1">Создать новую категорию</a>
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
