@extends('layouts.app')

@section('title', "Edit Category $category->title")

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('categories.edit', $category) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>edit '{{ $category->title }}' category</h1>


    <div class="row">

        
        @include('layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                
            <form method="POST" action="{{ route('categories.update', ['category' => $category->id]) }}" enctype="multipart/form-data">
                @csrf

                @method('PATCH')



                {{-- Standalone Image Button --}}
                <h2 class="mt-4">Standalone Image Button</h2>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                            <i class="far fa-image"></i> Выберите
                        </a>
                    </span>
                    <input id="thumbnail" class="form-control" type="text" name="filepath">
                </div>
                <div id="holder" style="margin-top:15px;max-height:100px;"></div>


                <script>
                    var route_prefix = "{{ url(config('lfm.url_prefix')) }}";
                </script>
                <script>
                    {!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/js/stand-alone-button.js')) !!}
                </script>
                {{-- <script src="{{ asset('vendor/lfm/js/stand-alone-button.js') }}"></script> --}}

                <script>
                    $('#lfm').filemanager('image', {prefix: route_prefix});
                    // $('#lfm2').filemanager('file', {prefix: route_prefix});
                </script>
                <style>
                    .popover {
                        top: auto;
                        left: auto;
                    }
                </style>
                {{-- <script>
                    $(document).ready(function(){
                        // Define function to open filemanager window
                        var lfm = function(options, cb) {
                            var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
                            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                            window.SetUrl = cb;
                        };
                    });
                </script> --}}
                {{-- change prefix --}}
                <script>
                    $(document).ready(function(){
                        // Define function to open filemanager window
                        var lfm = function(options, cb) {
                            var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
                            // var route_prefix = "{{ url(config('lfm.url_prefix')) }}";
                            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                            window.SetUrl = cb;
                        };
                    });
                </script>
                {{-- Standalone Image Button --}}






                @if($category->image)                    


                <div class="row">
                    <div class="col-sm-4">
                        <div class="card-img-top b_image" style="background-image: url({{ asset('storage') }}/images/categories/{{$category->id}}/{{$category->image}});">
                            <div class="dummy"></div><div class="element"></div>
                        </div>
                    </div>

                    <div class="col-sm-8">
                    <div class="form-group"> replace image
                        <input type="file" name="image" accept=".jpg, .jpeg, .png"
                            value="{{ old('image') }}">
                    </div>

                @else

                    <div class="col-sm-12">
                    <div class="form-group"> add image
                        <input type="file" name="image" accept=".jpg, .jpeg, .png"
                            value="{{ old('image') }}">
                    </div>

                @endif
                

                <div class="form-group">
                    <label for="name">name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Name Product"
                        value="{{ old('name') ?? $category->name }}" required>
                </div>

                <div class="form-group">
                    <label for="title">title</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Name Product"
                        value="{{ old('title') ?? $category->title }}" required>
                </div>

                <div class="form-group">
                    <label for="description">description</label>
                    <textarea id="description" name="description" cols="30" rows="4" class="form-control"
                        placeholder="description">{{ old('description') ?? $category->description }}</textarea>                       
                </div>

                {{-- ??? --}}
                <div class="form-group">
                    <label for="visible">visible</label>
                    <select name="visible" id="visible">
                        <?php
                            if ( $category->visible ) {
                                echo '<option value="1" selected>visible category</option><option value="0">invisible</option>';
                            } else {
                                echo '<option value="1">visible</option><option value="0" selected>invisible</option>';
                            }
                        ?>
                    </select>
                </div>


                {{-- parent category --}}
                <div class="form-group">
                    <label for="description">parent category</label>
                    <select name="parent_id" id="parent_id">
                        @foreach ( $categories as $parent_category )
                            {{-- @if ( $parent_category->id == 1 )
                            @elseif ( !$parent_category->countProducts() ) --}}
                            @if ( !$parent_category->countProducts() )
                                <option 
                                    value="{{ $parent_category->id }}"
                                    {{ $parent_category->id == $category->parent_id ? ' selected' : ''}}
                                    >{{ $parent_category->title }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                {{-- /parent category --}}


                <button type="submit" class="btn btn-primary form-control">edit category!</button>

            </div>
            </form>
        </div>
    </div>
@endsection
