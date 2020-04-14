@extends('layouts.theme_switch')

@section('title', __('Manufacturers_create'))

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('manufacturers.create') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>{{ __('Manufacturers_create') }}</h1>


    <div class="row">


        @include('dashboard.layouts.partials.aside')


        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">

            <form method="POST" action="{{ route('manufacturers.store') }}" enctype="multipart/form-data">

                @csrf

                @lfmImageButton(['id' => 'lfm_images', 'name' => 'imagepath', 'value' => old('imagepath')])

                <div class="row">
                    {{-- <div class="col-12 col-md-6">
                        @input(['name' => 'name', 'label' => __('name'), 'value' => old('name'), 'required' => 'required'])
                    </div>
                    <div class="col-12 col-md-6">
                        @input(['name' => 'title', 'label' => __('title'), 'value' => old('title')])
                    </div> --}}
                    {{-- name --}}
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="name">{{ __('__namerequired') }}*</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="{{__('Name')}}"
                                value="{{ old('name') }}" required>
                        </div>
                    </div>
                    {{-- /name --}}

                    {{-- title --}}
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="title">{{ __('title') }}</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="{{__('Title')}}"
                                value="{{ old('title') }}">
                        </div>
                    </div>
                    {{-- /title --}}

                    {{-- slug --}}
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="slug">{{ __('slug') }}</label>
                            <input type="text" id="slug" name="slug" class="form-control" placeholder="{{__('Slug')}}"
                                value="{{ old('slug') }}">
                        </div>
                    </div>
                    {{-- /slug --}}
                </div>

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


                <div class="row">
                    {{-- sort_order --}}
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label for="sort_order">{{ __('sort_order') }}</label>
                            <select name="sort_order" id="sort_order">
                                @for ( $i = 0; $i < 10; $i++ )
                                    @if ( 5 == $i )
                                        <option value="{{ $i }}" selected>{{ $i }}</option>
                                    @else
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                    </div>
                    {{-- sort_order --}}
                </div>

                <button type="submit" class="btn btn-primary form-control">{{ __('apply') }}</button>

            </form>
        </div>
    </div>
@endsection
