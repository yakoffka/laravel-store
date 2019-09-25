@extends('layouts.app')

@section('title', 'Создание нового товара')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('products.create') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Creating new product</h1>


    <div class="row">

    
        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                
                @csrf

                {{-- <div class="form-group">
                    <input type="file" name="image" accept=".jpg, .jpeg, .png" value="{{ old('image') }}">
                </div> --}}
                {{-- @inpImage(['value' => old('image')]) --}}
                {{-- <div class="form-group">
                    <label for="images">images</label>
                    <input type="file" name="images[]" multiple>
                </div> --}}
                @lfmImageButton(['id' => 'lfm_images', 'name' => 'imagespath', 'value' => old('imagespath') ?? ''])


                {{-- <div class="form-group">
                    <label for="name">name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Name Product" value="{{ old('name') }}" required>
                </div> --}}
                {{-- @inpName(['value' => old('name')]) --}}
                @input(['name' => 'name', 'value' => old('name'), 'required' => 'required'])

                {{-- <div class="form-group">
                    <label for="manufacturer">manufacturer</label>
                    <input type="text" id="manufacturer" name="manufacturer" class="form-control" placeholder="manufacturer" value="{{ old('manufacturer') }}">
                </div> --}}
                {{-- @input(['name' => 'manufacturer', 'value' => old('manufacturer')]) !!! manufacturer_id --}}

                {{-- <div class="form-group">
                    <label for="materials">materials</label>
                    <input type="text" id="materials" name="materials" class="form-control" placeholder="materials" value="{{ old('materials') }}">
                </div> --}}
                <div class="form-group">
                    <label for="manufacturer_id">manufacturer</label>
                    <select name="manufacturer_id" id="manufacturer_id">
                    <?php
                        foreach ( $manufacturers as $manufacturer ) {
                            echo '<option value="' . $manufacturer->id . '">' . $manufacturer->title . '</option>';
                        }
                    ?>
                    </select>
                </div>

                @input(['name' => 'materials', 'value' => old('materials')])

                {{-- <div class="form-group">
                    <label for="year_manufacture">year_manufacture</label>
                    <input type="number" id="year_manufacture" name="year_manufacture" class="form-control"  placeholder="year_manufacture" value="{{ old('year_manufacture') }}">
                </div> --}}
                @input(['name' => 'year_manufacture', 'type' => 'number', 'value' => old('year_manufacture')])

                {{-- <div class="form-group">
                    <label for="price">price</label>
                    <input type="number" id="price" name="price" class="form-control" placeholder="price" value="{{ old('price') }}">
                </div> --}}
                @input(['name' => 'price', 'type' => 'number', 'value' => old('price')])

                {{-- <div class="form-group">
                    <label for="description">visible product</label>
                    <select name="visible" id="visible">
                        <option value="1" selected>visible</option>
                        <option value="0">invisible</option>
                    </select>
                </div> --}}
                @select(['name' => 'visible', 'options' => [['value' => '1', 'title' => 'visible'], ['value' => '0', 'title' => 'invisible']]])


                {{-- parent category --}}
                <div class="form-group">
                    <label for="description">parent category</label>
                    <select name="category_id" id="category_id">
                        @foreach ( $categories as $category )
                            @if ( $category->id == 1 )
                            @elseif ( $category->countChildren() )
                                @foreach ( $categories as $subcategory )
                                    @if ( $subcategory->parent_id == $category->id )
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->parent->title }} > {{ $subcategory->title }}</option>
                                    @endif
                                @endforeach
                            @elseif ( !$category->countProducts() )
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                {{-- /parent category --}}


                {{-- description --}}
                {{ old('description') }}
                @if ( config('settings.description_wysiwyg') == 'ckeditor' )
                    @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'description', 'label' => 'Описание (редактор ckeditor)', 'value' => old('description')])
                @elseif ( config('settings.description_wysiwyg') == 'summernote' )
                    @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'description', 'label' => 'Описание (редактор summernote)', 'value' => old('description')])
                @elseif ( config('settings.description_wysiwyg') == 'tinymce' )
                    @include('layouts.partials.wysiwyg.tinymce-textarea', ['name' => 'description', 'label' => 'Описание (редактор tinymce)', 'value' => old('description')])
                @else
                    @textarea(['name' => 'description', 'label' => 'Описание (обычный режим)', 'value' => old('description')])                
                @endif
                {{-- /description --}}                  


                {{-- modification --}}
                {{ old('modification') }}
                @if ( config('settings.modification_wysiwyg') == 'ckeditor' )
                    @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'modification', 'label' => 'Модификации (редактор ckeditor)', 'value' => old('modification')])
                @elseif ( config('settings.modification_wysiwyg') == 'summernote' )
                    @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'modification', 'label' => 'Модификации (редактор summernote)', 'value' => old('modification')])
                @elseif ( config('settings.modification_wysiwyg') == 'tinymce' )
                    @include('layouts.partials.wysiwyg.tinymce-textarea', ['name' => 'modification', 'label' => 'Модификации (редактор tinymce)', 'value' => old('modification')])
                @elseif ( config('settings.modification_wysiwyg') == 'srctablecode' )
                    @textarea(['name' => 'modification', 'label' => 'Модификации (режим исходного кода таблицы)', 'value' => old('modification')])                
                @else
                    @textarea(['name' => 'modification', 'label' => 'Модификации (обычный режим)', 'value' => old('modification')])                
                @endif
                {{-- /modification --}}                 
                

                {{-- workingconditions --}}
                {{ old('workingconditions') }}
                @if ( config('settings.workingconditions_wysiwyg') == 'ckeditor' )
                    @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'workingconditions', 'label' => 'Условия работы (редактор ckeditor)', 'value' => old('workingconditions')])
                @elseif ( config('settings.workingconditions_wysiwyg') == 'summernote' )
                    @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'workingconditions', 'label' => 'Условия работы (редактор summernote)', 'value' => old('workingconditions')])
                @elseif ( config('settings.workingconditions_wysiwyg') == 'tinymce' )
                    @include('layouts.partials.wysiwyg.tinymce-textarea', ['name' => 'workingconditions', 'label' => 'Условия работы (редактор tinymce)', 'value' => old('workingconditions')])
                @else
                    @textarea(['name' => 'workingconditions', 'label' => 'Условия работы (обычный режим)',  'value' => old('workingconditions')])
                @endif
                {{-- /workingconditions --}}                  


                <button type="submit" class="btn btn-primary form-control">Create new product!</button>

            </form>
        </div>
    </div>
@endsection
