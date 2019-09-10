@extends('layouts.app')

@section('title', 'Creating new product')

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
                <div class="form-group">
                    <label for="images">images</label>
                    <input type="file" name="images[]" multiple>
                </div>

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

                <div class="form-group">
                    <label for="description">parent category</label>
                    <select name="category_id" id="category_id">
                    <?php
                        foreach ( $categories as $parent_category ) {
                            echo '<option value="' . $parent_category->id . '">' . $parent_category->title . '</option>';
                        }
                    ?>
                    </select>
                </div>

                {{-- <div class="form-group">
                    <label for="description">description</label>
                    <textarea id="description" name="description" cols="30" rows="4" class="form-control" placeholder="description">{{ old('description') }}</textarea>                       
                </div> --}}
                {{-- @textarea(['name' => 'description', 'value' => old('description')]) --}}

                {{-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
                <textarea name="description" class="form-control my-editor">{!! old('description') !!}</textarea>
                <script>
                var editor_config = {
                    path_absolute : "/",
                    selector: "textarea.my-editor",
                    plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                    relative_urls: false,
                    file_browser_callback : function(field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                    if (type == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.open({
                        file : cmsURL,
                        title : 'Filemanager',
                        width : x * 0.8,
                        height : y * 0.8,
                        resizable : "yes",
                        close_previous : "no"
                    });
                    }
                };

                tinymce.init(editor_config);
                </script> --}}


                modification
                {{-- modification --}}
                @if( config('settings.modification_wysiwyg'))
                    @if ( config('settings.wysiwyg') == 'ckeditor' )
                        @include('layouts.partials.wysiwyg.ckeditor-textarea', ['name' => 'modification', 'value' => old('modification')])
                    @elseif ( config('settings.wysiwyg') == 'summernote' )
                        @include('layouts.partials.wysiwyg.summernote-textarea', ['name' => 'modification', 'value' => old('modification')])
                    @elseif ( config('settings.wysiwyg') == 'tinymce' )
                        @include('layouts.partials.wysiwyg.tinymce-textarea', ['name' => 'modification', 'value' => old('modification')])
                    @endif
                @else
                    @textarea(['name' => 'modification', 'value' => old('modification')])                
                @endif
                {{-- /modification --}}


                <button type="submit" class="btn btn-primary form-control">Create new product!</button>

            </form>
        </div>
    </div>
@endsection
