@extends('layouts.app')

@section('title', 'Edit product')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('products.edit', $product) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Edit product '{{ $product->name }}'</h1>


    <div class="row">

    
        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            @listImage(compact('product'))

            <form method="POST" action="{{ route('products.update', ['product' => $product->id]) }}" enctype="multipart/form-data">
                @csrf

                @method('PATCH')

                {{-- @if($product->image)
                    <div class="card-img-top b_image col-sm-4" style="background-image: url({{ asset('storage') }}/images/products/{{$product->id}}/{{$product->image}}_l{{ config('imageyo.res_ext') }});">
                        <div class="dummy"></div><div class="element"></div>
                    </div>
                @else
                @endif --}}

                {{-- @inpImage(['value' => old('image')]) --}}
                {{-- <div class="form-group">
                    <label for="images">Добавить изображения:</label><br>
                    <input type="file" name="images[]" multiple>
                </div> --}}
                @lfmImageButton(['id' => 'lfm_images', 'name' => 'imagespath', 'value' => old('imagespath') ?? ''])


                @input(['name' => 'name', 'value' => old('name') ?? $product->name, 'required' => 'required'])


                {{-- description --}}
                {!! $product->description !!}
                @if( config('settings.description_wysiwyg'))
                    @if ( config('settings.wysiwyg') == 'ckeditor' )
                        @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'description', 'value' => old('description') ?? $product->description])
                    @elseif ( config('settings.wysiwyg') == 'summernote' )
                        @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'description', 'value' => old('description') ?? $product->description])
                    @elseif ( config('settings.wysiwyg') == 'tinymce' )
                        @include('layouts.partials.wysiwyg.tinymce-textarea', 
                            ['name' => 'description', 'label' => 'Описание', 'value' => old('description') ?? $product->description])
                    @endif
                @else
                    @textarea(['name' => 'description', 'value' => old('description') ?? $product->description])                
                @endif
                {{-- /description --}}                  


                {{-- modification --}}
                {!! $product->modification !!}
                @if( config('settings.modification_wysiwyg'))
                    @if ( config('settings.wysiwyg') == 'ckeditor' )
                        @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'modification', 'value' => old('modification') ?? $product->modification])
                    @elseif ( config('settings.wysiwyg') == 'summernote' )
                        @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'modification', 'value' => old('modification') ?? $product->modification])
                    @elseif ( config('settings.wysiwyg') == 'tinymce' )
                        @include('layouts.partials.wysiwyg.tinymce-textarea', 
                            ['name' => 'modification', 'label' => 'Модификации', 'value' => old('modification') ?? $product->modification])
                    @elseif ( config('settings.wysiwyg') == 'srctablecode' )
                        @textarea(['name' => 'modification', 'label' => 'Модификации (режим преобразования исходного кода)', 'value' => old('description') ?? $product->modification])                
                    @endif
                @else
                    @textarea(['name' => 'modification', 'value' => old('modification') ?? $product->modification])                
                @endif
                {{-- /modification --}}                  


                {{-- workingconditions --}}
                {!! $product->workingconditions !!}
                @if( config('settings.workingconditions_wysiwyg'))
                    @if ( config('settings.wysiwyg') == 'ckeditor' )
                        @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'workingconditions', 'value' => old('workingconditions') ?? $product->workingconditions])
                    @elseif ( config('settings.wysiwyg') == 'summernote' )
                        @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'workingconditions', 'value' => old('workingconditions') ?? $product->workingconditions])
                    @elseif ( config('settings.wysiwyg') == 'tinymce' )
                        @include('layouts.partials.wysiwyg.tinymce-textarea', 
                            ['name' => 'workingconditions', 'label' => 'Условия работы', 'value' => old('workingconditions') ?? $product->workingconditions])
                    @endif
                @else
                    @textarea(['name' => 'workingconditions', 'value' => old('workingconditions') ?? $product->workingconditions])                
                @endif
                {{-- /workingconditions --}}                  


                {{-- @input(['name' => 'manufacturer', 'value' => old('manufacturer') ?? $product->manufacturer->title ?? '-']) --}}
                <div class="form-group">
                    <label for="manufacturer_id">manufacturer</label>
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

                @input(['name' => 'materials', 'value' => old('materials') ?? $product->materials])

                @input(['name' => 'year_manufacture', 'type' => 'number', 'value' => old('year_manufacture') ?? $product->year_manufacture])

                @input(['name' => 'price', 'type' => 'number', 'value' => old('price') ?? $product->price])

                <div class="form-group">
                    <label for="visible">visible product</label>
                    <select name="visible" id="visible">
                        <?php
                            if ( $product->visible ) {
                                echo '<option value="1" selected>visible</option><option value="0">invisible</option>';
                            } else {
                                echo '<option value="1">visible</option><option value="0" selected>invisible</option>';
                            }
                        ?>
                    </select>
                </div>


                {{-- parent category --}}
                <div class="form-group">
                    <label for="description">parent category</label>
                    <select name="category_id" id="category_id">
                        @foreach ( $categories as $category )
                            @if ( $category->id == 1 )
                            @elseif ( $category->countChildren() )
                                @foreach ( $categories as $subcategory )
                                    @if ( $subcategory->parent_id == $category->id )
                                        <option 
                                            value="{{ $subcategory->id }}"
                                            {{ $subcategory->id == $product->category_id ? ' selected' : ''}}
                                        >
                                            {{ $subcategory->parent->title }} > {{ $subcategory->title }}
                                        </option>
                                    @endif
                                @endforeach
                            @elseif ( !$category->countProducts() )
                                <option 
                                    value="{{ $category->id }}"
                                    {{ $category->id == $product->category_id ? ' selected' : ''}}
                                    >{{ $category->title }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                {{-- /parent category --}}

                
                <button type="submit" class="btn btn-primary form-control">edit product!</button>

            </form>
        </div>
    </div>
{{-- </div> --}}
@endsection
