@extends('layouts.app')

@section('title', 'Копирование товара ' . $product->name)

@section('description', 'Копирование товара ' . $product->name . '.')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('products.copy', $product) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Копирование товара '{{ $product->name }}'</h1>


    <div class="row">

    
        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            @listImage(compact('product'))

            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="boxes left_stylized_checkbox">
                    <input type="checkbox" id="copy_img" name="copy_img" value="{{ $product->id }}" checked>
                    <label for="copy_img">Копировать изображения копируемого товара</label>
                </div>

                @lfmImageButton(['id' => 'lfm_images', 'name' => 'imagespath', 'value' => old('imagespath') ?? ''])

                @input(['name' => 'name', 'value' => old('name') ?? $product->name . '-copy', 'required' => 'required'])

                {{-- manufacturer, materials, date_manufactured, price --}}
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="manufacturer_id">{{ __('manufacturer') }}</label><br>
                            <select name="manufacturer_id" id="manufacturer_id">
                            <?php
                                foreach ( $manufacturers as $manufacturer ) {
                                    if ( $product->manufacturer_id == $manufacturer->id ) {
                                        echo '<option value="' . $manufacturer->id . '" selected>' . $manufacturer->title . '</option>';
                                    } else {
                                        echo '<option value="' . $manufacturer->id . '">' . $manufacturer->title . '</option>';
                                    }
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        @input(['name' => 'materials', 'label' => __('materials'), 'value' => old('materials') ?? $product->materials])
                    </div>
                    <div class="col-12 col-md-3">
                        @input(['name' => 'date_manufactured', 'label' => __('date_manufactured'), 'type' => 'date', 'value' => old('date_manufactured') ?? $product->date_manufactured])
                    </div>
                    <div class="col-12 col-md-3">
                        @input(['name' => 'price', 'type' => 'number', 'label' => __('price'), 'value' => old('price') ?? $product->price])
                    </div>
                </div>
                {{-- manufacturer, materials, date_manufactured, price --}}

                {{-- description --}}
                @if ( config('settings.description_wysiwyg') == 'ckeditor' )
                    @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'description', 'label' => 'Описание (редактор ckeditor)', 'value' => old('description') ?? $product->description])
                @elseif ( config('settings.description_wysiwyg') == 'summernote' )
                    @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'description', 'label' => 'Описание (редактор summernote)', 'value' => old('description') ?? $product->description])
                @elseif ( config('settings.description_wysiwyg') == 'tinymce' )
                    @include('layouts.partials.wysiwyg.tinymce-textarea', ['name' => 'description', 'label' => 'Описание (редактор tinymce)', 'value' => old('description') ?? $product->description])
                @else
                    @textarea(['name' => 'description', 'label' => 'Описание (обычный режим)', 'value' => old('description') ?? $product->description])                
                @endif
                {{-- /description --}}                  


                {{-- modification --}}
                @if ( config('settings.modification_wysiwyg') == 'ckeditor' )
                    @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'modification', 'label' => 'Модификации (редактор ckeditor)', 'value' => old('modification') ?? $product->modification])
                @elseif ( config('settings.modification_wysiwyg') == 'summernote' )
                    @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'modification', 'label' => 'Модификации (редактор summernote)', 'value' => old('modification') ?? $product->modification])
                @elseif ( config('settings.modification_wysiwyg') == 'tinymce' )
                    @include('layouts.partials.wysiwyg.tinymce-textarea', ['name' => 'modification', 'label' => 'Модификации (редактор tinymce)', 'value' => old('modification') ?? $product->modification])
                @elseif ( config('settings.modification_wysiwyg') == 'srctablecode' )
                    @textarea(['name' => 'modification', 'label' => 'Модификации (режим исходного кода таблицы)', 'value' => old('modification') ?? $product->modification])                
                @else
                    @textarea(['name' => 'modification', 'label' => 'Модификации (обычный режим)', 'value' => old('modification') ?? $product->modification])                
                @endif
                {{-- /modification --}}                 
                

                {{-- workingconditions --}}
                @if ( config('settings.workingconditions_wysiwyg') == 'ckeditor' )
                    @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'workingconditions', 'label' => 'Условия работы (редактор ckeditor)', 'value' => old('workingconditions') ?? $product->workingconditions])
                @elseif ( config('settings.workingconditions_wysiwyg') == 'summernote' )
                    @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'workingconditions', 'label' => 'Условия работы (редактор summernote)', 'value' => old('workingconditions') ?? $product->workingconditions])
                @elseif ( config('settings.workingconditions_wysiwyg') == 'tinymce' )
                    @include('layouts.partials.wysiwyg.tinymce-textarea', ['name' => 'workingconditions', 'label' => 'Условия работы (редактор tinymce)', 'value' => old('workingconditions') ?? $product->workingconditions])
                @else
                    @textarea(['name' => 'workingconditions', 'label' => 'Условия работы (обычный режим)',  'value' => old('workingconditions') ?? $product->workingconditions])
                @endif
                {{-- /workingconditions --}}                  


                <div class="row">
                    {{-- sort_order --}}
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="sort_order">{{ __('sort_order') }}</label>
                            <select name="sort_order" id="sort_order">
                                @for ( $i = 0; $i < 10; $i++ )
                                    @if ( $product->sort_order == $i )
                                        <option value="{{ $i }}" selected>{{ $i }}</option>
                                    @else
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>
                    {{-- sort_order --}}

                    {{-- parent category --}}
                    <div class="col-12 col-md-7">
                        <div class="form-group">
                            <label for="description">{{ __('category') }}</label>
                            <select name="category_id" id="category_id">
                                @foreach ( $categories as $category )
                                    @if ( $category->countProducts() )
                                        <option value="{{ $category->id }}"
                                            @if ( $product->category_id == $category->id )
                                                selected
                                            @endif
                                        >
                                            {{ $category->parent->name }} > {{ $category->title }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- /parent category --}}

                    {{-- visible --}}
                    <div class="form-group col-12 col-md-2">
                        <div class="right_stylized_checkbox">
                            <input type="checkbox" id="visible" name="visible"
                                @if ( $product->visible )
                                    checked
                                @endif
                            >
                            <label for="visible">{{ __('visible') }}</label>
                        </div>
                    </div>
                    {{-- /visible --}}
                </div>

                
                <button type="submit" class="btn btn-primary form-control">{{ __('apply') }}</button>

            </form>
        </div>
    </div>
@endsection
