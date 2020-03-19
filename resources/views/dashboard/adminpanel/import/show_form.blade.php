@extends('layouts.theme_switch')
@section('title', __('Import_products'))
@section('description', __('Import_products'))
@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('products.import') }}
        </div>
        <div
            class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>

    <h1>{{__('Import_products')}}</h1>

    <div class="row">

        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">

            <form method="POST" action="{{ route('import.products') }}" enctype="multipart/form-data">
                @csrf


                <h2 class="mt-4">Загрузить файл импорта</h2>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="import_file" data-input="thumbnail_import_file" data-preview="holder_import_file" class="btn btn-primary text-white">
                            <i class="far fa-file-excel"></i> Выбрать
                        </a>
                    </span>
                    <input id="thumbnail_import_file" class="form-control" type="text" name="import_file" value="{{old('import_file')}}">
                </div>
                <div id="holder_import_file" style="margin-top:15px;max-height:100px;"></div>


                <h2 class="mt-4">Загрузить архив с изображениями</h2>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="import_archive" data-input="thumbnail_import_archive" data-preview="holder_import_archive" class="btn btn-primary text-white">
                            <i class="far fa-file-archive"></i> Выбрать
                        </a>
                    </span>
                    <input id="thumbnail_import_archive" class="form-control" type="text" name="import_archive" value="{{old('import_archive')}}">
                </div>
                <div id="holder_import_archive" style="margin-top:15px;max-height:100px;"></div>


            <script>
                var route_prefix = "{{ url(config('lfm.url_prefix')) }}";
            </script>

            <script src="{{ asset('vendor/lfm/js/stand-alone-button.js') }}"></script>

            <script>
                // $('#input_file').filemanager('image', {prefix: route_prefix});
                $('#import_file').filemanager('import_file', {prefix: route_prefix});
                $('#import_archive').filemanager('import_archive', {prefix: route_prefix});
            </script>

            <style>
                .popover {
                    top: auto;
                    left: auto;
                }
            </style>

            <script>
                $(document).ready(function () {
                    // Define function to open filemanager window
                    var lfm = function (options, cb) {
                        // var route_prefix = "{{ url(config('lfm.url_prefix')) }}";
                        window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                        window.SetUrl = cb;
                    };
                });
            </script>


                {{--csv file import: <input type="file" name="import_file" value="{{old('import_file')}}">
                archive images:  <input type="file" name="import_archive" value="{{old('import_archive')}}">--}}

            {{--@lfmImageButton(['id' => 'lfm_arch_images', 'name' => 'lfm_arch_images', 'value' => old('lfm_arch_images') ?? ''])--}}
            <button type="submit" class="btn btn-primary form-control">{{ __('apply') }}</button>
            </form>

        </div>
    </div>
@endsection
